@extends('admin.layout.main')

@section('content')
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-10 col-xs-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">产品分类管理</h3>
                </div>
                <a type="button" class="btn " href="product_cates/create" >添加分类</a>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>名称</th>
                                <th>操作</th>
                            </tr>
                            @foreach($productCates as $productCate)
                            <tr>
                                <th>{{$productCate->name}}</th>
                                <th>
                                    <a type="button" class="btn" href="product_cates/{{$productCate->id}}/edit" >编辑</a>
                                    <a type="button" class="btn" href="product_cates/{{$productCate->id}}/delete" >删除</a>
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
 