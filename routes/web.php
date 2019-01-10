<?php
Auth::routes();

/*
Route::get('/', 'HomeController@index')->name('home');


Route::post('auth', 'HomeController@auth')->name('auth');

Route::get('settings', 'HomeController@viewSettings')->name('view.settings');
Route::post('settings', 'HomeController@saveSettings')->name('save.settings');

Route::post('expense', 'HomeController@saveExpense')->name('save.expense');
*/



Route::group([
    'prefix' => 'google',
    'middleware' => ['auth']
], function(){
    Route::get('auth', 'GoogleController@auth')->name('google.auth.view');
    //Route::post('auth', 'GoogleController@handleAuth')->name('google.auth.handle');
});



Route::group([
    'middleware' => ['auth', 'auth.google'],
], function(){

    Route::get('/', 'BudgetController@index')->name('home');
    Route::post('expense', 'BudgetController@saveExpense')->name('save.expense');
    Route::get('expense', 'BudgetController@showExpenses')->name('show.expense');
});



Route::group([
    'prefix' => 'setting',
    'middleware' => ['auth']
], function(){
    Route::get('/', 'SettingController@viewSettings')->name('view.settings');
    Route::post('/', 'SettingController@saveSettings')->name('save.settings');
});



