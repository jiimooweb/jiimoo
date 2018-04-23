<?php

namespace App\Admin\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\ProductCate;
use App\Admin\Controllers\Controller;

class ProductCateController extends Controller
{
    
    public function index() 
    {
        $productCates = ProductCate::paginate(20);
        return view('admin.displays.product_cate.index', compact('productCates'));
    }

    public function create() 
    {
        return view('admin.displays.product_cate.create');
    }

    public function store() 
    {   
        $this->validate(request(),[
            'name' => 'required|min:2|max:6',
        ]);
        
        ProductCate::create(request(['name']));

        return back();
    }

    public function edit(ProductCate $productCate) 
    {
        return view('admin.displays.product_cate.edit', compact('productCate'));
    }

    public function update(ProductCate $productCate) 
    {
        $this->validate(request(),[
            'name' => 'required|min:2|max:6',
        ]);
        
        ProductCate::where('id', $productCate->id)->update(request(['name']));

        return back();
    }

    public function delete(ProductCate $productCate)
    {   
        // TODO:判断删除权限
        $productCate->delete();
        return redirect('admin/displays/product_cates');
    }
}
