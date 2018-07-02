<?php

namespace App\Utils;

class OrderStatus
{
    const CANCEL = -1;  //取消
    const UNPAID = 0;   //未支付
    const PAID = 1;     //支付，待确认
    const CONFIRM = 2;  //确认（接单）,待发货
    const DISTRIBUTION = 3;  //配送（发货），待完成
    const FINISH = 4;   //完成
}