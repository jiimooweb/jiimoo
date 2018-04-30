<?php

namespace App\Admin\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\Swiper;
use App\Models\Displays\ProductCate;
use App\Admin\Controllers\Controller;

class SwiperController extends Controller
{
    
    public function index() 
    {
        $swipers = Swiper::where('display', 1)->orderBy('created_at','asc')->get();
        // return view('admin.displays.swiper.index', compact('swipers'));

        foreach($swipers as &$swiper) {
            $swiper['image'] = env('APP_URL').$swiper['image'];
        }
        return response()->json($swipers);
    }

    public function create() 
    {
        return view('admin.displays.swiper.create');
    }

    public function store() 
    {   
        $this->validate(request(),[
            'image' => 'required',
            // 'url' => 'required',
            'remake' => 'required',
            'display' => 'required|integer'
        ]);

        $data = request([
            'remake', 'display'
        ]);

        $data['image'] = '/storage/'.request()->file('image')->storePublicly(md5(time()));
        
        
        Swiper::create($data);

        return back();
    }

    public function show(Swiper $swiper)
    {
        // TODO: 待开发
    }

    public function edit(Swiper $swiper) 
    {
        return view('admin.displays.swiper.edit', compact('swiper'));
    }

    public function update(Swiper $swiper) 
    {
        // TODO:判断更新权限
        
        $this->validate(request(),[
            'image' => 'required',
            'url' => 'required',
            'remake' => 'required',
            'display' => 'required|integer'
        ]);

        $data = request([
            'remake', 'display'
        ]);

        $data['image'] = '/storage/'.request()->file('image')->storePublicly(md5(time()));
        
        Swiper::where('id', $swiper->id)->update($data);

        return back();
    }

    public function destroy(Swiper $swiper)
    {
        // TODO:判断删除权限
        $swiper->delete();
        return redirect('admin/displays/swipers');
    }
}
