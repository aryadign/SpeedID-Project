<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportCommentController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {}

    public function store(Request $request, Report $report)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $this->reportService->addComment($report, $request->user(), $validated['comment']);

        return back()->with('success', 'Komentar berhasil ditambahkan');
    }
}
