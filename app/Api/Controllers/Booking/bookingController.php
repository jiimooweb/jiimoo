<?php

namespace App\Api\Controllers\Resertvations;

use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use App\Models\Reservations\Reservation;
use App\Models\Reservations\FanReservation;

class ResertvationController extends Controller
{

    public function index()
    {
        $reservations = Reservation::all();
        // dd($reservations);
        // return view('admin.reservations.index', compact('reservations'));
        return response()->json(["status"=>"success","data"=>compact('reservations')]);
    }

    public function create()
    {
        return view('admin.reservations.create');
    }
    public function record()
    {
        return view('admin.reservations.record');
    }

    public function store()
    {
        $this->validate(request(), [
            'reservation_name' => 'required',
            'keyword' => 'required',
            'started_at' => 'required',
            'termination_at' => 'required|after:started_at',
            'prompt' => 'required',
        ]);

        $data = request([
            'reservation_name', 'keyword', 'started_at', 'termination_at', 'prompt'
        ]);
        dd($data);
        // $data['logo'] = 'storage/'.request()->file('logo')->storePublicly(md5(time()));
        
        // BasicInfo::create($data);

        return back();
    }

    public function show(Reservation $reservation)
    {
        // $fan_reservation = FanReservation::all();
        // $fan_reservations = FanReservation::where('reservation_id',$reservation->id)->get();         
        $fan_reservations = FanReservation::index($reservation);         
        // dd($fan_reservations);        
        return view('admin.reservations.show', compact('fan_reservations'));
    }
    public function create_fan()
    {

        return view('admin.reservations.create_fan');
    }

    public function edit()
    {
        // $this->validate(request(),[
        //     'name' => 'required',
        //     'tel' => 'required',
        //     'address' => 'required',
        //     'logo' => 'required|file'
        // ]);

        // $data = request([
        //     'name', 'tel', 'address', 'intro', 'desc'
        // ]);

        // $data['logo'] = 'storage/'.request()->file('logo')->storePublicly(md5(time()));
        
        // BasicInfo::where('id', $info->id)->update($data);
        return view('admin.reservations.edit');
    }


    public function delete(BasicInfo $info)
    {
        $info->delete();
        return redirect('admin.displays.index');
    }
}
