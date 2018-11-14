<?php

namespace App\Models\Reservations;

use App\Models\Model;
use Illuminate\Support\Facades\DB;

class FanReservation extends Model
{
    protected $table = 'fan_reservation';

    public function reservation()
    {
        return $this->hasOne('App\models\Rreservations\reservation');
    }

    public static function index(Reservation $reservation)
    {
        return DB::table('fan_reservation')
        ->leftJoin('fans', 'fan_reservation.fan_id', '=', 'fans.id')
        ->where('reservation_id','=', $reservation->id)
        ->get();

    }
}
