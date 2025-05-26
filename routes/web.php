<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\NewPage;
use App\Http\Controllers\UserApplicationController;
use App\Livewire\ApplicationFlow;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/new-page', NewPage::class)->name('new.page');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::get('/application/start', [UserApplicationController::class, 'startOrResumeApplication'])->name('application.start');

Route::get('/application/{application_id}', ApplicationFlow::class)->name('application.show');
    // The ApplicationFlow Livewire component will handle step progression internally.
    // We might add specific POST routes here later if needed for actions not easily handled by Livewire.

    // Route to view submitted applications (example)
    Route::get('/my-applications', [UserApplicationController::class, 'index'])->name('applications.index');
