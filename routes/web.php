<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyContentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StepCompletionController;
use App\Http\Controllers\TutorialController;
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

Route::get('/companies/create', [CompanyController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('companies.create');

Route::post('/companies', [CompanyController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('companies.store');

Route::get('/companies/{company_id}', [CompanyController::class, 'show'])
    ->whereNumber('company_id')
    ->middleware(['auth', 'verified'])
    ->name('companies.show');

Route::post('/contents', [ContentController::class, 'store'])->name('contents.store');
Route::delete('/contents/{content}', [ContentController::class, 'destroy'])->name('contents.destroy');

Route::post('/companies/{company}/contents', [CompanyContentController::class, 'store'])->name('companies.contents.store');

Route::resource('/tutorials/categories', CategoryController::class)
    ->middleware(['auth', 'verified'])
    ->names('tutorials.categories')
    ->except(['edit', 'update']);

Route::resource('/tutorials', TutorialController::class)
    ->middleware(['auth', 'verified'])
    ->names('tutorials');

Route::post('/steps/{step}/toggle-completion', [StepCompletionController::class, 'toggle'])
    ->middleware(['auth', 'verified'])
    ->name('steps.toggle-completion');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Tenant routes (tenant roles only)
Route::prefix('manage')
    ->middleware(['auth', 'verified', 'tenant.admin'])
    ->name('manage.')
    ->group(function () {
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    });

// Admin UI routes (system roles only)
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
