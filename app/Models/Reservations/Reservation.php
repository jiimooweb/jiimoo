<?php

namespace App\Models\Reservations;

use App\Models\Model;

class Reservation extends Model
{

    public function FanReservation()
    {
        return $this->hasOne('App\models\FanReservation');
    }
}
