<?php

Route::get('admin/displays/infos', '\App\Admin\Controllers\Displays\BasicInfoController@index')->name('displays_basic_info_index');
Route::get('admin/displays/infos/create', '\App\Admin\Controllers\Displays\BasicInfoController@create')->name('displays_basic_info_create');
Route::post('admin/displays/infos', '\App\Admin\Controllers\Displays\BasicInfoController@store')->name('displays_basic_info_store');
Route::get('admin/displays/infos/{info}/show', '\App\Admin\Controllers\Displays\BasicInfoController@show')->name('displays_basic_info_show');
Route::get('admin/displays/infos/{info}/edit', '\App\Admin\Controllers\Displays\BasicInfoController@edit')->name('displays_basic_info_edit');
Route::get('admin/displays/infos/{info}/delete', '\App\Admin\Controllers\Displays\BasicInfoController@delete')->name('displays_basic_info_delete');


Route::resource('admin/reservations', '\App\Admin\Controllers\Resertvations\ResertvationController');