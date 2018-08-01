<?php
// const resertvationController = '\App\Api\Controllers\Resertvations\resertvationController';S
Route::group(['prefix' => 'booking'], function () {
    Route::apiResource('/settings', '\App\Api\Controllers\Booking\SettingController', ['only' => ['index', 'store', 'update']]);
});
