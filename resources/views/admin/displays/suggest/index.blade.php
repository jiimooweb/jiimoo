@extends('admin.layout.main')

@section('content')
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-10 col-xs-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">建议列表</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>姓名</th>
                                <th>联系电话</th>
                                <th>邮箱</th>
                                <th>提交时间</th>
                                <th>建议内容</th>
                                <th>操作</th>
                            </tr>
                            @foreach($suggests as $suggest)
                            <tr>
                                <th>{{$suggest->name}}</th>
                                <th>{{$suggest->mobile}}</th>
                                <th>{{$suggest->email}}</th>
                                <th>{{$suggest->created_at}}</th>
                                <th>{{$suggest->content}}</th>
                                <th>
                                    <a type="button" class="btn" href="suggests/{{$suggest->id}}/delete" >删除</a>
                                </th>
                            </tr>
                            @endforeach
                      </tbody>
                    </table>
                    {{$suggests->links()}}
                </div>

            </div>
        </div>
    </div>
</section>
 @endsection
 