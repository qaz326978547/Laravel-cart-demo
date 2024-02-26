<?php

use Illuminate\Http\Request;
use App\Models\Product  ;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/products', 'ProductController@getProducts');



Route::group(['prefix'=>'auth'], function() {
    Route::post('/register','AuthController@register');
    Route::post('/login','AuthController@login');
    Route::group(['middleware'=>'auth:api'], function() {
        Route::get('/user','AuthController@user');
        Route::post('/logout','AuthController@logout');
    });
});

Route::group(['middleware'=>'auth:api'], function() {
    Route::group(['prefix'=>'cart'], function() {
        Route::get('/','CartController@getCart');
        Route::post('/add','CartItemController@addCartItem');
        Route::post('/update','CartItemController@updateCartItem');
        Route::post('/delete','CartItemController@deleteCartItem');
        Route::post('/checkout','CartController@checkout');
    });
    Route::group(['prefix'=> 'product'], function() {
        Route::post('/update', 'ProductController@updateProduct');
        Route::get('/{id}', 'ProductController@getProduct');
        Route::post('/products', 'ProductController@createProduct');
    });

    Route::group(['prefix'=> 'order'], function() {
    Route::get('/', 'OrderController@getOrder');
    });
});






