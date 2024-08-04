<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusinessProfileController;

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

Route::get('/', [BusinessProfileController::class, 'index']);
Route::get('/profil/{slug}', [BusinessProfileController::class, 'detail'])->name('profil');
