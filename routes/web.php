<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\NodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Node;
use App\Models\File;
use App\Models\Year;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome'); // Add ->name('welcome') here.

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'totalNodes' => Node::count(),
        'totalFiles' => File::count(),
        'totalYears' => Year::count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::resource('nodes', NodeController::class);

    // khusus upload file
    Route::post('/nodes/{node}/files', [FileController::class, 'store'])->name('files.store');

    Route::delete('/nodes/{node}', 'App\Http\Controllers\NodeController@destroy')->name('nodes.destroy');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');

    // Laravel Route
    Route::put('/nodes/{node}/toggle-complete', [NodeController::class, 'toggleComplete'])->name('nodes.toggleComplete');
});

Route::get('/public', [PublicController::class, 'index'])->name('public.index');

Route::get('/public/nodes', [PublicController::class, 'index'])->name('public.nodes');


require __DIR__ . '/auth.php';
