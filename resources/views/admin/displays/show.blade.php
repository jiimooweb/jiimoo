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
                        <h3 class="box-title">添加基本信息</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="{{route('displays_basic_info_edit',['id' => $info->id])}}" method="GET" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">名称</label>
                                <input type="text" class="form-control" name="name" value="{{$info->name}}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">logo</label>
                                <input type="file" class="form-control" name="logo" value="{{$info->logon}}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">联系电话</label>
                                <input type="number" class="form-control" name="tel" value="{{$info->tel}}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">地址</label>
                                <input type="text" class="form-control" name="address" value="{{$info->address}}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">简介</label>
                                <input type="text" class="form-control" name="intro" value="{{$info->intro}}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">详情</label>
                                <div>
                                @component('admin.layout.ueditor',['name' => 'desc'])
                                    {!! $info->desc !!}
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
 