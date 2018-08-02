<?php

namespace App\Api\Controllers\Booking;

// use App\Models\Foods\Setting;
use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
// use App\Http\Requests\Foods\SettingRequest;

class SettingController extends Controller
{

    public function index()
    {
        // $setting = Setting::first();

        // if(isset($setting)) {
        //     $setting['offer'] = isset($setting['offer']) ? json_decode($setting['offer']) : null;
        // }
        $json_string = '{
            "id": 2,
            "notificationEmail": null,
            "supportEmail": null,
            "phone": null,
            "banner": [{
                "type": "product",
                "url": "http://nzr2ybsda.qnssl.com/images/24978/Fj7UjcsUaTlL7d8e-Bt7DEaorVh-.jpg?imageMogr2/strip/thumbnail/800x450\u003e/quality/90!/interlace/1/format/jpg",
                "value": 26226
            }, {
                "type": "product",
                "url": "http://nzr2ybsda.qnssl.com/images/24978/Fg1JkXLbhVsW5cUI8A2hj-hZ3fyd.jpg?imageMogr2/strip/thumbnail/800x450\u003e/quality/90!/interlace/1/format/jpg",
                "value": 26226
            }, {
                "type": "product",
                "url": "http://nzr2ybsda.qnssl.com/images/24978/Fo-TEebPyUh75wQc5GPZjqaC5g4Y.jpg?imageMogr2/strip/thumbnail/800x450\u003e/quality/90!/interlace/1/format/jpg",
                "value": 26226
            }],
            "categoryOrder": {},
            "orderList": {},
            "paymentGateway": ["offline"],
            "reserveCycle": 1,
            "pickPeople": true,
            "pickDate": true,
            "pickTime": true
        } ';
        $obj = json_decode($json_string);
        return response()->json(['status' => 'success', 'data' => $obj]);
    }

    public function store(SettingRequest $requset)
    {
        $data = request()->all();
        $data['offer'] = isset($data['offer']) ? json_encode($data['offer']) : null;

        $setting = Setting::create($data);

        if ($setting) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！', 'data' => $setting]);
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);
    }


    public function update(SettingRequest $requset)
    {
        $data = request()->all();
        $data['offer'] = $data['offer'] ? json_encode($data['offer']) : null;

        if (Setting::where('id', request()->setting)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);
    }

}
