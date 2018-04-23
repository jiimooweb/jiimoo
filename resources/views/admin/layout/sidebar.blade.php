<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="treeview active">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>基本信息</span>
                    <span class="pull-right-container"></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{env('APP_URL')}}/admin/displays/infos"><i class="fa fa-circle-o"></i> 基本信息</a></li>
                </ul>
            </li>   
            <li class="active treeview">
                <a href="{{env('APP_URL')}}/admin/displays/articles">
                    <i class="fa fa-dashboard"></i> <span>文章管理</span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{env('APP_URL')}}/admin/displays/articles"><i class="fa fa-circle-o"></i> 文章列表</a></li>
                    <li><a href="{{env('APP_URL')}}/admin/displays/article_cates"><i class="fa fa-circle-o"></i> 分类管理</a></li>
                    <li><a href="{{env('APP_URL')}}/admin/displays/comments"><i class="fa fa-circle-o"></i> 评论管理</a></li>
                </ul>
            </li> 
            <li class="active treeview">
                <a href="{{env('APP_URL')}}/admin/displays/products">
                    <i class="fa fa-dashboard"></i> <span>产品管理</span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{env('APP_URL')}}/admin/displays/products"><i class="fa fa-circle-o"></i> 产品列表</a></li>
                    <li><a href="{{env('APP_URL')}}/admin/displays/product_cates"><i class="fa fa-circle-o"></i> 分类管理</a></li>
                </ul>
            </li>  
            <li class="active treeview">
                <a href="{{env('APP_URL')}}/admin/displays/activitys">
                    <i class="fa fa-dashboard"></i> <span>活动管理</span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{env('APP_URL')}}/admin/displays/activitys"><i class="fa fa-circle-o"></i> 活动列表</a></li>
                </ul>
            </li>           
            <li class="active treeview">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>其他</span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{env('APP_URL')}}/admin/displays/colunms"><i class="fa fa-circle-o"></i> 专栏管理</a></li>
                    <li><a href="{{env('APP_URL')}}/admin/displays/swipers"><i class="fa fa-circle-o"></i> 轮播图管理</a></li>
                    <li><a href="{{env('APP_URL')}}/admin/displays/suggests"><i class="fa fa-circle-o"></i> 建议列表</a></li>
                    
                </ul>
            </li>      
            
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>