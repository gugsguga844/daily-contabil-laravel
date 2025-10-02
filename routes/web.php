<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/tutorials', function () {
    return Inertia::render('Tutorials/Index');
})->middleware(['auth', 'verified'])->name('tutorials.index');

Route::get('/companies', [CompanyController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('companies.index');

Route::get('/companies/{company_id}', [CompanyController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('companies.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin UI routes (system users only)
Route::prefix('admin')
    ->middleware(['auth', 'verified', 'admin.role'])
    ->name('admin.')
    ->group(function () {
        // Temporary simple dashboard route
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/Dashboard');
        })->name('dashboard');

        // Offices onboarding
        Route::get('/offices/create', [\App\Http\Controllers\Admin\OfficeController::class, 'create'])
            ->name('offices.create');
        Route::post('/offices', [\App\Http\Controllers\Admin\OfficeController::class, 'store'])
            ->name('offices.store');
    });

require __DIR__.'/auth.php';
