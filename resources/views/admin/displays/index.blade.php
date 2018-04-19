@extends('admin.layout.main')

@section('content')
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-10 col-xs-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">基本资料管理</h3>
                </div>
                <a type="button" class="btn " href="{{route('displays_basic_info_create')}}" >添加信息</a>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>名称</th>
                                <th>logo</th>
                                <th>联系电话</th>
                                <th>地址</th>    
                                <th>操作</th>
                            </tr>
                            @if($info)
                            <tr>
                                <th>{{$info->name}}</th>
                                <th><img src="{{env('APP_URL').$info->logo}}" alt=""></th>
                                <th>{{$info->tel}}</th>
                                <th>{{$info->address}}</th>    
                                <th>
                                    <a type="button" class="btn" href="{{route('displays_basic_info_show',['info' => $info->id])}}" >编辑</a>
                                    <a type="button" class="btn" href="{{route('displays_basic_info_delete',['info' => $info->id])}}" >删除</a>
                                </th>
                            </tr>
                            @endif
                      </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</section>
 @endsection
 