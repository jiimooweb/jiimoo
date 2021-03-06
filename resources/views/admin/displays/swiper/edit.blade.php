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
                    <form role="form" action="../{{$swiper->id}}" method="POST" enctype="multipart/form-data">
                        {{method_field('PUT')}}
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">图片</label>
                                <input type="file" name="image">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">链接内容</label>
                                <input type="text" class="form-control" name="remake" value="{{$swiper->remake}}">                                
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">是否显示</label>
                                <label>
                                    <input type="radio" name="display" value="1" @if($swiper->display == 1)checked @endif>是
                                </label>
                                <label>
                                    <input type="radio" name="display" value="0" @if($swiper->display == 0)checked @endif>否
                                </label>
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
 