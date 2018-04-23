@extends('admin.layout.main')

@section('content')

<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-10 col-xs-6">
            <div class="box">
                <!-- /.box-header -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">编辑产品</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="../{{$product->id}}" method="POST" enctype="multipart/form-data">
                        {{method_field('PUT')}}
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">产品名称</label>
                                <input type="text" class="form-control" name="name" value="{{$product->name}}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">分类</label>
                                <select name="cate_id">
                                    @foreach($cates as $cate)
                                    <option value="{{$cate['id']}}" @if($cate['id']==$product->id) selected @endif >{{$cate['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">价格</label>
                                <input type="text" class="form-control" name="price" value="{{$product->price}}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">是否显示</label>                     
                                <label><input type="radio" name="display" value="1" @if($product->display == 1)  checked @endif>是</label>
                                <label><input type="radio" name="display" value="0" @if($product->display == 1) checked @endif>否</label>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">描述</label>
                                <div>
                                @component('admin.layout.ueditor',['name' => 'desc'])
                                    {!! $product->desc !!}"
                                @endcomponent
                                </div>
                            </div>
                        </div>
                        @include('admin.layout.error')
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
 