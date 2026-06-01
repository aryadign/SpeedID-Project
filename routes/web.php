<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmergencyContactController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\NewsCategoryController;
use App\Http\Controllers\NewsPostController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QueueTicketController;
use App\Http\Controllers\ReportCommentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceSlotController;
use App\Http\Controllers\SOSController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// SpeedQ — Queue
Route::prefix('queue')->name('queue.')->middleware('auth')->group(function () {
    Route::get('/', [QueueTicketController::class, 'create'])->name('booking');
    Route::post('/', [QueueTicketController::class, 'store'])->name('book');
    Route::get('/tickets', [QueueTicketController::class, 'index'])->name('tickets');
    Route::get('/tickets/{ticket}', [QueueTicketController::class, 'show'])->name('tickets.show');
    Route::get('/display/{service}', [QueueTicketController::class, 'display'])->name('display');
});

Route::get('/queue/current/{service}', [QueueTicketController::class, 'current'])
    ->name('queue.current');

// SpeedReport
Route::prefix('reports')->name('reports.')->middleware('auth')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/create', [ReportController::class, 'create'])->name('create');
    Route::post('/', [ReportController::class, 'store'])->middleware('throttle:reports')->name('store');
    Route::get('/{report}', [ReportController::class, 'show'])->name('show');
    Route::post('/{report}/status', [ReportController::class, 'updateStatus'])->name('status');
    Route::post('/{report}/comments', [ReportCommentController::class, 'store'])->name('comments.store');
});

Route::get('/track', [ReportController::class, 'track'])->name('reports.track');
Route::post('/track', [ReportController::class, 'track']);

// SpeedSOS
Route::get('/sos/active', [SOSController::class, 'active'])->name('sos.active');

Route::prefix('sos')->name('sos.')->middleware('auth')->group(function () {
    Route::get('/', [SOSController::class, 'index'])->name('index');
    Route::post('/', [SOSController::class, 'store'])->middleware('throttle:sos')->name('store');
    Route::get('/{sos}', [SOSController::class, 'show'])->name('show');
    Route::post('/{sos}/status', [SOSController::class, 'updateStatus'])->name('status');
});

// Polling Endpoints (JSON)
Route::middleware('auth')->prefix('poll')->name('poll.')->group(function () {
    Route::get('/queue/{ticket}/status', [QueueTicketController::class, 'apiStatus'])->name('queue.status');
    Route::get('/reports/{report}/status', [ReportController::class, 'apiStatus'])->name('report.status');
    Route::get('/notifications', [NotificationController::class, 'unread'])->name('notifications');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

// SpeedNews
Route::prefix('news')->name('news.')->group(function () {
    Route::get('/', [NewsPostController::class, 'index'])->name('index');
    Route::get('/{post}', [NewsPostController::class, 'show'])->name('show');
});

Route::get('/news/emergency/alerts', [NewsPostController::class, 'emergencyAlerts'])
    ->name('news.emergency');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Institutions
    Route::resource('institutions', InstitutionController::class)->except(['show']);

    // Services
    Route::resource('institutions.services', ServiceController::class)
        ->shallow()
        ->except(['show']);

    // Service Slots
    Route::resource('services.slots', ServiceSlotController::class)
        ->shallow()
        ->except(['show', 'edit', 'update']);

    // Queue Management
    Route::prefix('queue')->name('queue.')->group(function () {
        Route::get('/', [QueueTicketController::class, 'index'])->name('index');
        Route::get('/{ticket}', [QueueTicketController::class, 'show'])->name('show');
    });

    // Report Management
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/{report}', [ReportController::class, 'show'])->name('show');
        Route::post('/{report}/status', [ReportController::class, 'updateStatus'])->name('status');
        Route::post('/{report}/comments', [ReportCommentController::class, 'store'])->name('comments.store');
    });

    // SOS Management
    Route::prefix('sos')->name('sos.')->group(function () {
        Route::get('/monitoring', [SOSController::class, 'adminMonitoring'])->name('monitoring');
        Route::get('/', [SOSController::class, 'index'])->name('index');
        Route::get('/{sos}', [SOSController::class, 'show'])->name('show');
        Route::post('/{sos}/status', [SOSController::class, 'updateStatus'])->name('status');
    });

    // Emergency Contacts
    Route::resource('contacts', EmergencyContactController::class)->except(['show', 'create', 'edit']);

    // News Management
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/', [NewsPostController::class, 'adminIndex'])->name('index');
        Route::get('/create', [NewsPostController::class, 'create'])->name('create');
        Route::post('/', [NewsPostController::class, 'store'])->name('store');
        Route::get('/{post}/edit', [NewsPostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [NewsPostController::class, 'update'])->name('update');
        Route::delete('/{post}', [NewsPostController::class, 'destroy'])->name('destroy');
        Route::resource('categories', NewsCategoryController::class)
            ->except(['show', 'create', 'edit']);
    });
});

Route::get('/search', [SearchController::class, 'index'])->name('search');

require __DIR__ . '/auth.php';
