<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


Route::group(['namespace' => 'Wisdom\Openform\Controllers'], function () {
    Route::group(['prefix' => 'openform'], function () {
        Route::group(['prefix' => 'api'], function () {
            Route::get('form/{id}', 'ApiOpenFormController@getForm');
            Route::post('form', 'ApiOpenFormController@postAnswer');
        });
    });
    Route::resource('form', 'OpenFormController');
    Route::get('form/publish/{id}', 'OpenFormController@publish');
    Route::get('form/report/{id}', 'OpenFormController@report');
});




