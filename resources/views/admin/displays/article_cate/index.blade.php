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
                <a type="button" class="btn " href="article_cates/create" >添加分类</a>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>名称</th>
                                <th>操作</th>
                            </tr>
                            @foreach($articleCates as $articleCate)
                            <tr>
                                <th>{{$articleCate->name}}</th>
                                <th>
                                    <a type="button" class="btn" href="article_cates/{{$articleCate->id}}/edit" >编辑</a>
                                    <a type="button" class="btn" href="article_cates/{{$articleCate->id}}/delete" >删除</a>
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
 