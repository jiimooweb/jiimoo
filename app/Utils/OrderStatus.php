<?php

namespace App\Utils;

class OrderStatus
{
    const REFUND_SUCCESS = -3;  //退款成功
    const REFUND = -2;  //退款
    const CANCEL = -1;  //取消
    const UNPAID = 0;   //未支付, 待付款
    const PAID = 1;     //支付，待确认
    const CONFIRM = 2;  //确认（接单）,待发货
    const SUCCESS = 3;  //已完成
}