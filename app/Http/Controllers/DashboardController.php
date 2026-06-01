<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index(Request $request)
    {
        if ($request->user()->hasRole('Admin')) {
            $data = $this->dashboardService->getAdminDashboard();
            return view('dashboard.admin', $data);
        }

        $data = $this->dashboardService->getUserDashboard($request->user());
        return view('dashboard.user', $data);
    }
}
