<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
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

Route::view('/','products');
Route::post('/save',[ProductController::class, 'save'])->name('save.product');
Route::get('/fetchProducts',[ProductController::class, 'fetchProducts'])->name('fetch.products');
Route::get('/getProductDetails', [ProductController::class, 'getProductDetails'])->name('get.product.details');
Route::post('/updateProduct', [ProductController::class, 'updateProduct'])->name('update.product');
Route::post('/deleteProduct', [ProductController::class, 'deleteProduct'])->name('delete.product');
