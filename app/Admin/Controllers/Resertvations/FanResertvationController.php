<?php

namespace App\Admin\Controllers\Resertvations;

use Illuminate\Http\Request;
use App\Admin\Controllers\Controller;
use App\Models\Reservations\Reservation;

class FanResertvationController extends Controller
{
    
    public function index() 
    {
        $reservations = Reservation::all();
        return view('admin.reservation_fan.index', compact('reservations'));
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
        $this->validate(request(),[
            'name' => 'required',
            'tel' => 'required',
            'address' => 'required',
            'logo' => 'required|file'
        ]);

        $data = request([
            'name', 'tel', 'address', 'intro', 'desc'
        ]);

        $data['logo'] = 'storage/'.request()->file('logo')->storePublicly(md5(time()));
        
        BasicInfo::create($data);

        return back();
    }

    public function show(BasicInfo $info)
    {
        
        return view('admin.displays.show',compact('info'));
    }

    public function edit(BasicInfo $info) 
    {
        $this->validate(request(),[
            'name' => 'required',
            'tel' => 'required',
            'address' => 'required',
            'logo' => 'required|file'
        ]);

        $data = request([
            'name', 'tel', 'address', 'intro', 'desc'
        ]);

        $data['logo'] = 'storage/'.request()->file('logo')->storePublicly(md5(time()));
        
        BasicInfo::where('id', $info->id)->update($data);

        return back();
    }

    public function delete(BasicInfo $info)
    {
        $info->delete();
        return redirect('admin.displays.index');
    }
}
