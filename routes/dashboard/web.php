<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\CategoryController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;




Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']],
    function () {
      Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function(){
  
         Route::get('/','WelcomeController@index')->name('welcome');

           //category Route
           Route::resource('categories','CategoryController')->except(['show']);

          //product Route
          Route::resource('products','ProductController')->except(['show']);

          //client Route
          Route::resource('clients','clientController')->except(['show']);
          Route::resource('clients.orders','Client\OrderController')->except(['show']);

            // order Routes
           Route::resource('orders','OrderController');
           Route::get('/orders/{order}/products','OrderController@product')->name('orders.products');

           //user Route
           Route::resource('users','UserController')->except(['show']);
        
          });
       


   });   //Dashboard Routes








