<?php

namespace App\Observers;

use App\Models\Model;

class ModelObserver
{
    /**
     * 监听用户创建的事件。
     *
     * @param  User  $user
     * @return void
     */
    public function created(Model $model)
    {
        dd($model);
    }

    /**
     * 监听用户删除事件。
     *
     * @param  User  $user
     * @return void
     */
    public function deleting(Model $model)
    {
        //
    }
}
