<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Report;
use App\Models\ReportCategory;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {
        $this->authorizeResource(Report::class, 'report');
    }

    public function index(Request $request)
    {
        $query = Report::with(['category', 'user']);

        if ($request->user() && !$request->user()->hasRole('Admin')) {
            $query->where('user_id', $request->user()->id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(10);
        $categories = ReportCategory::where('is_active', true)->get();
        return view('speedreport.index', compact('reports', 'categories'));
    }

    public function create()
    {
        $categories = ReportCategory::where('is_active', true)->get();
        return view('speedreport.create', compact('categories'));
    }

    public function store(StoreReportRequest $request)
    {
        $data = $request->validated();
        $report = $this->reportService->createReport($request->user(), $data);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $this->reportService->uploadMedia($report, $file);
            }
        }

        return redirect()->route('reports.show', $report->id)
            ->with('success', 'Laporan berhasil dikirim');
    }

    public function show(Report $report)
    {
        $report->load(['category', 'user', 'media', 'comments.user']);
        return view('speedreport.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:terkirim,diverifikasi,proses,selesai,ditolak',
            'note' => 'nullable|string|max:500',
        ]);

        $this->reportService->updateStatus($report, $validated['status']);

        if (!empty($validated['note'])) {
            $this->reportService->addComment($report, $request->user(), $validated['note']);
        }

        return back()->with('success', 'Status laporan diperbarui');
    }

    public function track(Request $request)
    {
        $request->validate(['code' => 'required|string|size:8']);
        $report = Report::with('category')->where('tracking_code', $request->code)->firstOrFail();
        return view('speedreport.track', compact('report'));
    }

    public function apiStatus(Report $report)
    {
        $report->load('media', 'comments.user');
        $activities = $report->activities()->latest()->get();

        return response()->json([
            'status' => $report->status,
            'updated_at' => $report->updated_at,
            'activities' => $activities->map(fn ($a) => [
                'description' => $a->description,
                'created_at' => $a->created_at->format('d M Y, H:i'),
                'notes' => $a->properties['old_status'] ?? null,
            ]),
            'comment_count' => $report->comments->count(),
            'last_comment' => $report->comments->last()?->body,
        ]);
    }
}
