<?php
Route::get('/home/{table?}', 'HomeController@index')->name('home');