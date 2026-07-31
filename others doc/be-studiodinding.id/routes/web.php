<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortofolioController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AboutController;
// use App\Http\Controllers\TaskController;
// use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('pages.home');
});

Route::get('getLogo', function () {
    return view('pages.logo');
});

Route::group(['prefix' => 'portfolio'], function () {
    Route::get('/', [PortofolioController::class, 'list']);
    Route::get('commercial', [PortofolioController::class, 'commercial']);
    Route::get('residential', [PortofolioController::class, 'residential']);
    Route::get('details/{portofolio}', [PortofolioController::class, 'details']);
});

Route::group(['prefix' => 'about'], function () {
    Route::get('/', [AboutController::class, 'index']);
    Route::get('/{people}', [AboutController::class, 'detail']);
});

Route::group(['prefix' => 'contact'], function () {
    Route::get('/', [ContactController::class, 'index']);
    Route::post('submit', [ContactController::class, 'submit']);
});