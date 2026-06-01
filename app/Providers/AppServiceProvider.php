<?php

namespace App\Providers;

use App\Models\NewsPost;
use App\Models\QueueTicket;
use App\Models\Report;
use App\Models\SOSRequest;
use App\Policies\NewsPolicy;
use App\Policies\QueuePolicy;
use App\Policies\ReportPolicy;
use App\Policies\SOSPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureRateLimiting();

        Gate::policy(Report::class, ReportPolicy::class);
        Gate::policy(QueueTicket::class, QueuePolicy::class);
        Gate::policy(NewsPost::class, NewsPolicy::class);
        Gate::policy(SOSRequest::class, SOSPolicy::class);
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->input('email') . '|' . $request->ip());
        });

        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        RateLimiter::for('reports', function (Request $request) {
            return Limit::perHour(5)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('sos', function (Request $request) {
            return Limit::perMinutes(10, 3)->by($request->user()?->id ?: $request->ip());
        });
    }
}
