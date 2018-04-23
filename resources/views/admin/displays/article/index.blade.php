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
                <a type="button" class="btn " href="articles/create" >添加文章</a>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>文章标题</th>
                                <th>所属分类</th>
                                <th>评论数</th>
                                <th>点击数</th>
                                <th>操作</th>
                            </tr>
                            @foreach($articles as $article)
                            <tr>
                                <th>{{$article->title}}</th>
                                <th>{{$article->category->name}}</th>
                                <th>{{$article->comments_count}}</th>    
                                <th>{{$article->click}}</th>    
                                <th>
                                    <a type="button" class="btn" href="articles/{{$article->id}}/edit" >编辑</a>
                                    {{-- <a type="button" class="btn" href="articles/{{$article->id}}/edit" >查看评论</a> --}}
                                    <a type="button" class="btn" href="articles/{{$article->id}}/delete" >删除</a>
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
 