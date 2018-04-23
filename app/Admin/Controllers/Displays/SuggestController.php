<?php

namespace App\Admin\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\Suggest;
use App\Admin\Controllers\Controller;

class SuggestController extends Controller
{
    
    public function index() 
    {
        $suggests = Suggest::orderBy('created_at','desc')->paginate(20);          
        return view('admin.displays.suggest.index', compact('suggests'));
    }


    public function delete(Suggest $suggest)
    {
        // TODO:判断删除权限
        $suggest->delete();
        return redirect('admin/displays/suggests');
    }
}
