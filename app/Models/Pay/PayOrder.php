<?php

namespace App\Models\Pay;

use Illuminate\Database\Eloquent\Model;

class PayOrder extends Model
{
    protected $table = 'pay_orders';

    public function generateOrderNo()
    {
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

}
