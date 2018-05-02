<?php

namespace App\Providers;

use App\Models\Model;
use App\Observers\ModelObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191); 
        // \App\Models\Coupons\CouponRecord::created(function($model){
        //     $model->xcx_id = 1;
        //     $model->save();
        // });
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
