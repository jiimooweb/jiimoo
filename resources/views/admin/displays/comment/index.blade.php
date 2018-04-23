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
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>所属文章</th>
                                <th>粉丝昵称</th>
                                <th>评论内容</th>
                                <th>操作</th>
                            </tr>
                            @foreach($comments as $comment)
                            <tr>
                                <th>{{$comment->article->title}}</th>
                                <th>{{$article->user->nickname}}</th>
                                <th>{{$article->content}}</th>   
                                <th>
                                    <a type="button" class="btn" href="comments/{{$article->id}}/delete" >删除</a>
                                </th>
                            </tr>
                            @endforeach
                      </tbody>
                    </table>
                    {{$comments->links()}}
                </div>

            </div>
        </div>
    </div>
</section>
 @endsection
 