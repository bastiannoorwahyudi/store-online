<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index')->name('home');

Route::get('/categories', 'CategoryController@index')->name('categories');
Route::get('/categories/{id}', 'CategoryController@detail')->name('categories-detail');

Route::get('/detail/{id}', 'DetailController@index')->name('detail');
Route::post('/detail/{id}', 'DetailController@add')->name('detail-add');

// integrasi modul checkout dengan midtrans
Route::post('/checkout/callback', 'CheckoutController@process')->name('midtrans-callback');

Route::get('/success', 'CartController@success')->name('success');
Route::get('/register/success', 'Auth\RegisterController@success')->name('register-success');

// Hak Akses ke transaksi, wajib login
Route::group(['middleware' => ['auth']], function(){
    Route::get('/cart', 'CartController@index')->name('cart');
    Route::delete('/cart/{id}', 'CartController@delete')->name('cart-delete');

    Route::post('/checkout', 'CheckoutController@process')->name('checkout');

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/dashboard/products', 'DashboardProductsController@index')->name('dashboard-products');
    Route::get('/dashboard/products/create', 'DashboardProductsController@create')->name('dashboard-products-create');
    Route::post('/dashboard/products', 'DashboardProductsController@store')->name('dashboard-products-store');
    Route::get('/dashboard/products/{id}', 'DashboardProductsController@details')->name('dashboard-products-details');
    Route::post('/dashboard/products/{id}', 'DashboardProductsController@update')->name('dashboard-products-update');
    Route::post('/dashboard/products/gallery/upload', 'DashboardProductsController@uploadGallery')->name('dashboard-products-gallery-upload');
    Route::get('/dashboard/products/gallery/delete/{id}', 'DashboardProductsController@deleteGallery')->name('dashboard-products-gallery-delete');

    Route::get('/dashboard/transactions', 'DashboardTransactionsController@index')->name('dashboard-transaction');
    Route::get('/dashboard/transactions/{id}', 'DashboardTransactionsController@details')->name('dashboard-transaction-details');
    Route::post('/dashboard/transactions/{id}', 'DashboardTransactionsController@update')->name('dashboard-transaction-update');
    
    Route::get('/dashboard/settings', 'DashboardSettingController@store')->name('dashboard-settings-store');
    Route::get('/dashboard/account', 'DashboardSettingController@account')->name('dashboard-settings-account');
    Route::post('/dashboard/account/{redirect}', 'DashboardSettingController@update')->name('dashboard-settings-redirect');
});

// route hanya bisa di akses bagi admin
Route::prefix('admin')
    ->namespace('Admin')
    ->middleware(['auth', 'admin'])
    ->group(function(){
        Route::get('/', 'DashboardController@index')->name('admin-dashboard');
        Route::resource('category', 'CategoryController');
        Route::resource('user', 'UserController');
        Route::resource('product', 'ProductController');
        Route::resource('product-gallery', 'ProductGalleryController');
    }); 

Auth::routes();

