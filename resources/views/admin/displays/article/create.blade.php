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
                        <h3 class="box-title">添加文章</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="../articles" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">标题</label>
                                <input type="text" class="form-control" name="title">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">分类</label>
                                <select name="cate_id">
                                    @foreach($cates as $cate)
                                    <option value="{{$cate['id']}}">{{$cate['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">作者</label>
                                <input type="text" class="form-control" name="author">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">点击数</label>
                                <input type="text" class="form-control" name="click">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">内容</label>
                                <div>
                                @component('admin.layout.ueditor',['name' => 'content'])

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
 