@extends('admin.layout.main')

@section('content')
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-10 col-xs-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">产品管理</h3>
                </div>
                <a type="button" class="btn " href="products/create" >添加产品</a>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>产品名称</th>
                                <th>价格</th>
                                <th>所属分类</th>
                                <th>是否显示</th>
                                <th>操作</th>
                            </tr>
                            @foreach($products as $product)
                            <tr>
                                <th>{{$product->name}}</th>
                                <th>{{$product->price}}</th>
                                <th>{{$product->category->name}}</th>    
                                @if($product->display)
                                <th>是</th>    
                                @else
                                <th>否</th>    
                                @endif
                                <th>
                                    <a type="button" class="btn" href="products/{{$product->id}}/edit" >编辑</a>
                                    {{-- <a type="button" class="btn" href="products/{{$product->id}}/edit" >查看评论</a> --}}
                                    <a type="button" class="btn" href="products/{{$product->id}}/delete" >删除</a>
                                </th>
                            </tr>
                            @endforeach
                      </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</section>
 @endsection
 