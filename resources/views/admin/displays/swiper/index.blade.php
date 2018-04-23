@extends('admin.layout.main')

@section('content')
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-10 col-xs-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">文章管理</h3>
                </div>
                <a type="button" class="btn " href="swipers/create" >添加文章</a>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>图片</th>
                                <th>链接内容</th>
                                <th>是否显示</th>
                                <th>操作</th>
                            </tr>
                            @foreach($swipers as $swiper)
                            <tr>
                                <th><img width="100" src="{{env('APP_URL').$swiper->image}}" alt=""></th>
                                <th><a href="{{$swiper->url}}">{{$swiper->remake}}</a></th>    
                                @if($swiper->display == 1)
                                <th>显示</th>
                                @else
                                <th>是否</th>
                                @endif
                                <th>
                                    <a type="button" class="btn" href="swipers/{{$swiper->id}}/edit" >编辑</a>
                                    {{-- <a type="button" class="btn" href="swipers/{{$swiper->id}}/edit" >查看评论</a> --}}
                                    <a type="button" class="btn" href="swipers/{{$swiper->id}}/delete" >删除</a>
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