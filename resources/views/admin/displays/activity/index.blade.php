@extends('admin.layout.main')

@section('content')
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-10 col-xs-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">活动管理</h3>
                </div>
                <a type="button" class="btn " href="activitys/create" >添加活动</a>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>活动标题</th>
                                <th>报名时间</th>
                                <th>活动时间</th>
                                <th>报名人数</th>
                                <th>活动状态</th>
                                <th>操作</th>
                            </tr>
                            @foreach($activitys as $activity)
                            <tr>
                                <th>{{$activity->name}}</th>
                                <th>{{$activity->sign_start_time}} <br/>- {{$activity->sign_end_time}}</th>
                                <th>{{$activity->start_time}} <br/>- {{$activity->end_time}}</th>
                                <th>{{$activity->signlists_count}}</th>    
                                @if($activity->status == 0)
                                <th>未开始</th>    
                                @elseif($activity->status == 1)
                                <th>活动进行中</th>    
                                @else
                                <th>已结束</th>    
                                @endif
                                <th>
                                    {{-- TODO: 查看详情/报名人数--}}                                    
                                    {{-- TODO: 用ajax改变活动状态 --}} 
                                    <a type="button" class="btn" href="activitys/{{$activity->id}}/edit" >编辑</a>
                                    
                                    <a type="button" class="btn" href="activitys/{{$activity->id}}/delete" >删除</a>
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
 