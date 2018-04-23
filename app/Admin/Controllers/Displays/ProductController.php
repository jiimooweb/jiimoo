<?php

namespace App\Admin\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\Product;
use App\Models\Displays\ProductCate;
use App\Admin\Controllers\Controller;

class ProductController extends Controller
{
    
    public function index() 
    {
        $products = Product::orderBy('created_at','desc')->paginate(6);
        $products->load('category');                
        return view('admin.displays.product.index', compact('products'));
    }

    public function create() 
    {
        $cates = ProductCate::all();
        return view('admin.displays.product.create', compact('cates'));
    }

    public function store() 
    {   
        $this->validate(request(),[
            'name' => 'required',
            'cate_id' => 'required',
            'display' => 'integer',
            'desc' => 'required'
        ]);

        $data = request([
            'name', 'cate_id', 'price', 'display', 'desc'
        ]);
        
        Product::create($data);

        return back();
    }

    public function show(Product $product)
    {
        // TODO: 待开发
    }

    public function edit(Product $product) 
    {
        $cates = ProductCate::all();
        return view('admin.displays.product.edit', compact('cates', 'product'));
        
    }

    public function update(Product $product) 
    {
        // TODO:判断更新权限
        
        $this->validate(request(),[
            'name' => 'required',
            'cate_id' => 'required',
            'display' => 'integer',
            'desc' => 'required'
        ]);

        $data = request([
            'name', 'cate_id', 'price', 'display', 'desc'
        ]);
        
        Product::where('id', $product->id)->update($data);

        return back();
    }

    public function delete(Product $product)
    {
        // TODO:判断删除权限
        $product->delete();
        return redirect('admin/displays/products');
    }
}
