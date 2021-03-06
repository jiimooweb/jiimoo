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
                        <h3 class="box-title">编辑活动</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="../{{$activity->id}}" method="POST" enctype="multipart/form-data">
                        {{method_field('PUT')}}
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">活动标题</label>
                                <input type="text" class="form-control" name="name" value="{{$activity->name}}" >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">限定名额</label>
                                <input type="text" class="form-control" name="places" value="{{$activity->places}}">
                                <div class="help-block">0为不限制名额</div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">报名类型</label>
                                <label>
                                    <input type="radio" name="sign_type" value="0" @if($activity->sign_type == 0)checked @endif>
                                    免费
                                </label>
                                <label>
                                    <input type="radio" name="sign_type" value="1" @if($activity->sign_type == 1)checked @endif>
                                    收费
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">报名价格</label>
                                <input type="text" class="form-control" name="sign_price" value="{{$activity->price}}">
                                <div class="help-block">当报名类型为收费时，请填写该项</div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">报名时间</label>
                                <input type="text" class="form-control pull-right reservationtime" name="sign_time">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">活动时间</label>
                                <input type="text" class="form-control pull-right reservationtime"  name="activity_time">
                                
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">内容</label>
                                <div>
                                @component('admin.layout.ueditor',['name' => 'content'])
                                    {!! $activity->content !!}
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

@section('script')
<script>
$('.reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 10, locale: {
                    format: 'YYYY-MM-DD HH:mm',
                    separator: ' ~ ',
                    applyLabel: "应用",
                    cancelLabel: "取消",
                    resetLabel: "重置",
                }});
</script>
@endsection