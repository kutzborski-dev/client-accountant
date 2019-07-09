<?php

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

Auth::routes();

Route::group(['middleware' => 'auth'], function()
{
    Route::get('/', 'PagesController@index');

    Route::get('/home', function()
    {
        return redirect('/');
    });

    Route::resource('customers', 'CustomersController');

    Route::resource('bills', 'BillsController');

    Route::resource('offers', 'OffersController');

    Route::get('search', 'SearchController@index')->name("search");
    Route::get('sort', 'SortController@index')->name("sort");
    Route::get('bills/pdf/{bill}', 'BillsController@generateBill')->name("bill_pdf");
    Route::get('settings/{page?}', 'SettingsController@provider')->name("settings");
    Route::patch('settings/{page?}', 'SettingsController@provider');
});
