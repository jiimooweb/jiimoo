<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>任意门网络工作室</title>
<meta name="description" content="任意门网络工作室是华南网络开发的新锐品牌。任意门网络工作室专注于各类互联网产品开发，业务涉及门户网站、信息管理系统、公众号建设开发、小程序开发等。">
<meta name="keywords" content="任意门网络工作室,小程序开发,中山小程序开发,微信公众号,系统定制">
<script src="jquery-1.7.1.min.js"></script>
<script>
	$(function(){
		$(".nav li").hover(function() {
			$(".second-nav",this).slideDown(100);
			/* Stuff to do when the mouse enters the element */
		}, function() {
			$(".second-nav",this).slideUp(100);
			// $(".second-nav").hide();
			/* Stuff to do when the mouse leaves the element */
		});
	})
</script>
</head>
<body>
    <div class="head">
    	<div class="content">
    		<div class="logo"><img src="{{asset('rdoorweb/logo3.png')}}" height="100%" alt=""></div>
    		<!-- <div class="login-box">
    			<a href="login.html"  class="n2">登录</a>
    			<a href="register.html" class="n1">注册</a>
    		</div> -->
    		<div class="nav">
	    		<ul>
	    			<li>
	    				<a href="/" class="current-nav">首页</a>
	    			</li>
	    			<!-- <li  style="position:relative;">
	    				<a href="javascript:;" class="h-nav">平台特色</a>
	    				<ul class="second-nav">
	    					<li>
	    						<a href="/">平台介绍</a>
	    					</li>
	    					<li>
	    						<a href="/">开发流程</a>
	    					</li>
	    				</ul>
	    			</li>
	    			<li>
	    				<a href="/">开发支持</a>
	    			</li>
	    			<li>
	    				<a href="/">关于我们</a>
					</li> -->
					<li>
						<a href="/backend" target="_blank">开发后台</a>
					</li>
	    		</ul>
    		</div>
    	</div>
    </div>
    <div class="banner">
    	<img src="{{asset('rdoorweb/banner.jpg')}}" height="668" width="1919" alt="">
    </div>
    <div class="p-box">
    	<div class="content">
    		<h1>任意门网络工作室</h1>
    		<h3>
					任意门网络工作室是华南网络开发的新锐品牌。任意门网络工作室专注于各类互联网产品开发，业务涉及门户网站、信息管理系统、公众号建设开发、小程序开发等。
					任意门网络工作室为各类商户企业提供智能商业服务整体解决方案，致力于通过产品和服务，用技术让商业变得更智慧，具备更多的商业价值。
    		</h3>
    	</div>
    </div>
    <div style="clear:both;"></div>
    <div class="p-box bg-grey">
    	<div class="content">
    		<ul>
    			<li class="trangle">
    				<a href="">
    					<img src="{{asset('rdoorweb/img1.png')}}" height="215" width="198" alt="">
    					<span>
    						<h2>小程序定制开发</h2>
    						<p>根据客户门店实际运用情况，进行专业的符合客户实际需求的功能定制，满足并提高客户的工作效率。</p>
    					</span>
    				</a>
    			</li>
    			<li class="trangle">
    				<a href="">
    					<img src="{{asset('rdoorweb/img2.png')}}" height="215" width="198" alt="">
    					<span>
    						<h2>网站定制开发</h2>
    						<p>给中小型公司提供多功能型网站开发，并在上线后进行维护。</p>
    					</span>
    				</a>
    			</li>
    			<li class="trangle">
    				<a href="">
    					<img src="{{asset('rdoorweb/img3.png')}}" height="215" width="198" alt="">
    					<span>
    						<h2>高端系统定制开发</h2>
    						<p>基于web端技术实现客户的系统需求，如类ERP，OA系统。</p>
    					</span>
    				</a>
    			</li>
    		</ul>
    	</div>
    </div>
    <div class="p-box">
    	<div class="content">
    		<h1>小程序场景化应用</h1>
    		<ul style="margin-top:40px;display:inline-block">
    			<li class="trangle scen">
    				<img src="{{asset('rdoorweb/icon1.png')}}" height="68" width="68" alt="">
    				<span>
						<h2>商品展示</h2>
						<p>丰富的模板快速套用，页面模版组件，灵活配置，支持多种商品形态，支持微信支付，安全可靠</p>
    				</span>
    			</li>
    			<li class="trangle scen">
    				<img src="{{asset('rdoorweb/icon2.png')}}" height="68" width="68" alt="">
					<span>
						<h2>活动营销</h2>
						<p>会员卡、优惠券、拼团、秒杀等，高效获客，团购、返现等，转化率飙升</p>
					</span>
    			</li>
    			<li class="trangle scen">
    				<img src="{{asset('rdoorweb/icon3.png')}}" height="68" width="68" alt="">
					<span>
						<h2>推广需求</h2>
						<p>依托微信10亿用户，共享流量分红，海量好货资源，快速、低门槛地在周边展示商家，吸引用户</p>
					</span>
    			</li>
    			<li class="trangle scen">
    				<img src="{{asset('rdoorweb/icon4.png')}}" height="68" width="68" alt="">
					<span>
						<h2>客户管理</h2>
						<p>支付即会员，强大的积分体制，粘住老客户最佳途径，标签管理，精准分组</p>
					</span>
    			</li>
    			<li class="trangle scen">
    				<img src="{{asset('rdoorweb/icon5.png')}}" height="68" width="68" alt="">
					<span>
						<h2>数据分析</h2>
						<p>多维度分析会员与分销商，商品流量实时统计，订单管理，一目了然，销量统计，提升转换</p>
					</span>
    			</li>
    		</ul>
    	</div>
    </div>
    <div class="p-box bg-grey" style="padding-bottom:0">
    	<div class="content">
    		<h1>专业的解决方案</h1>
    		<h3>
    			针对不同行业的门店，通过需求分析对不同的店家进行专业定制，给你最合适的解决方案，并且无需维护，没有后顾之忧
    		</h3>
    		<img src="{{asset('rdoorweb/ban1.png')}}" height="271" width="410" alt="">
    	</div>
    </div>
    <div class="p-box advantage">
    	<div class="content">
    		<span>我们的优势</span>
    		<ul>
    			<li>
    				高度定制并且可扩展
    			</li>
    			<li>提供图片云服务</li>
				<li>公有云/私有云服务器</li>
				<li>价格实惠，周期短</li>
    		</ul>
    	</div>
    </div>
    <div class="p-box footer">
    	<div class="content">
    		<div class="f1" style="width:100%;">
    			<ul>
    				<li>
        				Copyright © 2011-2018 www.rdoorweb.com. All Rights Reserved 中山火炬开发区任意门网络工作室版权所有
					</li>
					<a href="http://www.miitbeian.gov.cn" target="_blank" style="color: #666"> 粤IPC备18057166号-1</a>
    			</ul>
    		</div>
    	</div>
    </div>
</body>
</html>


<style>
html,body{
	width: 100%;
	margin: 0 auto;
	padding:0;
}
body{
	font-size: 14px;
	font-family: "微软雅黑";
	color: #333;
}
a{
	text-decoration: none;
}
img{
	border: none;
}
ul,li{
	margin: 0;
	padding: 0;
	list-style: none;
}
h1,h2,h3,h4,h5,h6,p{
	margin-bottom: 0;
	margin-top: 0;
	font-weight: normal;
}
input[type="button"],button{
	border: none;
}
.head,.footer,.bg-grey,.banner{
	min-width: 1024px;
}
.content{
	width: 976px;
	margin: 0 auto;
	padding: 0 24px;
	text-align: center;
}
.head{
	width: 100%;
	height: 80px;
	border-bottom:1px solid #bdbdbd;
}
.logo{
	width: 132px;
	height: 70px;
	margin-top: 5px;
	float: left;
}
.logo img{
	height: 100%;
	width: auto;
}
.nav{
	margin-top: 34px;
	font-size:16px;
	float: right;
}
.nav ul li{
	text-align: center;
	float: left;
}
.nav ul li a{
	display: block;
	width: 80px;
	padding: 10px 5px;
	color: #333;
}
.nav ul li a:hover{
	border-bottom:4px solid #0277bd;
}
.nav ul li a.current-nav{
	border-bottom:4px solid #0277bd;
}
ul.second-nav{
	display: none;
	background: rgba(239,239,239,0.9);
	border-radius: 3px;
	border: 1px solid #eee;
	box-shadow: 0 2px 2px 2px rgba(59, 56, 56, 0.3);
	position: absolute;
	top:40px;
	left: 0;
}
ul.second-nav li{
	float: none;
	border-bottom: 1px solid #ccc;
}
ul.second-nav li a{
	display: block;
	padding: 10px 5px;
}
ul.second-nav li a:hover{
	color:#039be5;
	border-bottom:none;
}
.login-box{
	margin:45px 0 0 30px;
	float: right;
	font-size: 12px;
}
.login-box a.n1,.login-box a.n2{
	padding: 5px 10px;
	border: 1px solid #499de1;
}
.login-box a.n1{
	background: #499de1;
	color: #fff;
}
.login-box a.n2{
	color: #499de1;

}
.banner{
	margin: 0 auto;
}
.banner img{
	width: 100%;
	height: auto;
}
.p-box{
	width: 100%;
	margin: 0 auto;
	padding: 56px 0;
}
.bg-grey{
	display: inline-block;
	min-height: 220px;
	background: #f5f5f5;
}
.grey{
	background: #f5f5f5;
}
.p-box h1{
	font-size: 30px;
	margin-bottom: 16px;
}
.p-box h3{
	color: #666;
}
.p-box ul li{
	float: left;
	width: 33.3%;
	text-align: center;
}
.p-box ul li.trangle img{
	width: 50%;
	height: 50%;
}
.p-box ul li.trangle span,.platform ul li.pf span{
	display: block;
	padding: 0 40px;
	color: #919191;
	line-height: 22px;
}
.p-box ul li.trangle span h2,.platform ul li.pf h2{
	margin-bottom: 8px;
	font-size: 18px;/*18px;*/
	color: #333;
}
.tools ul{
	width: 100%;
}
.tools ul li.pf span a{
	display: block;
	margin-top: 10px;
	color: #919191;
}
.tools ul li.pf span a:hover{
	color: #333;
}
.btn-demo ,.btn-look,.btn-down{
	padding-left: 30px;
}
.btn-demo{
	background: url(icon-demo.png) no-repeat center left;
}
.btn-look{
	background: url(icon-look.png) no-repeat center left;
}
.btn-down{
	background: url(icon-down.png) no-repeat center left;
}
.p-box ul li.scen{
	height: 130px;
}
.p-box ul li.scen img{
	width: 68px;
	height: 68px;
	float: left;
}
.p-box ul li.scen span{
	margin-left: 40px;
	text-align: left;
}
.advantage{
	padding: 24px 0;
	height: 26px;
	line-height: 26px;
}
.advantage span{
	display: block;
	float: left;
	font-size: 18px;
}
.advantage ul{
	margin-left: 130px;
}
.advantage ul li{
	width: auto;
	margin-right: 60px;
	color: #919191;
	list-style: disc;
}
.platform h1{
	color: #0277bd;
}
.platform ul li{
	width: 100%;
}
.pf .pic{
	width: 30%;
	text-align: right;
	float: left;
}
.platform ul li.pf span{
	margin-top: 30px;
	padding: 0;
	width: 70%;
	text-align: left;
	float: left;
}
.platform ul li.pf span.left{
	text-align: right;
}
.pf .pic img{
	margin-right:30px;
}
.pf .pic-right img{
	text-align: left;
	margin-left: 30px;
}
.pf .pic-right{
	text-align: left;
}
.p-box ul li.aboutus{
	height: auto;
}
.p-box ul li.aboutus img{
	width: 30px;
	height: 30px;
}
.p-box ul li.aboutus span{
	margin-left: 0;
}
.contact a{
	display: block;
	margin:0 auto;
	width: 120px;
	height: 40px;
	text-align: center;
	line-height: 40px;
	font-size: 20px;
	border: 1px solid #039be5;
	color: #039be5;
}
.contact a:hover{
	background:#039be5;
	color: #fff; 
}
.doc-panel{
	width: 207px;
	float: left;
	text-align: left;
}
.doc-panel ul li{
	text-align: left;
}
.doc-panel ul li a{
	float: none;
	line-height: 24px;
	color: #333;
}
.doc-panel ul li.scond-panel a{
	padding-left: 10px;
	color: #666;
}
.doc-content{
	margin-left: 210px;
	text-align: left;
}
.doc-panel h2,.doc-content h2{
	text-align: left;
	padding-bottom: 10px;
}
.doc-content h3{
	color: #333;
	font-size: 18px;
	margin-bottom: 10px;
	margin-top: 10px;
}
.login{
	margin: 0 auto;
	width: 300px;
}
.filter label{
	text-align: left;
	position: absolute;
	top: 29px;
	left: 30px;
	color: #919191;
}
.filter input{
	margin-top: 20px;
	height: 32px;
	line-height: 32px;
	border: 1px solid #ccc;
	border-radius: 2px;
}
.login .filter{
	position: relative;
}
.login .filter input{
	width: 270px;
	padding-left: 30px;
}
.username{
	background: url(user.png) no-repeat 2% center;
}
.pwd{
	background: url(lock.png) no-repeat 2% center;
}
.email{
	background: url(email.png) no-repeat 2% center;
}
.phone{
	background: url(phone.png) no-repeat 2% center;
}
.validinput{
	width: 142px;
	background: url(validate.png) no-repeat 2% center;
}
.login .filter input.btn-login{
	padding: 0;
	width:300px;
	height: 38px;
	background: #039be5;
	color: #fff;
	font-size: 18px;
	border: none;
}
.f-pwd{
	padding-top: 10px;
	float: left;
	color: #919191;
}
.f-pwd a{
	color:#333;
}
.f-pwd a:hover{
	color: #039be5;
}
@media(min-height: 768px){
	.ct-login{
		margin: 100px auto;
	}

}
@media(min-height: 900px){
	.ct-login{
		margin: 180px auto;
	}

}
.validate{
	display: none;
	color: #FCAB2B;
	text-align: left;
}
a.telvalidate{
	display: inline-block;
	margin-left:10px;
	padding:8px 5px;
	color:#333;
	background:#e6e6e6;
	border-radius: 2px;
}
.footer{
	background: #ddd;
	min-height: 50px;
}
.footer .f1{
	width: 25%;
	float: left;
}
.footer h3{
	margin-bottom: 10px;
}
.footer ul li{
	font-size: 13px;
	width: 100%;
	color: #919191;
	float: none;
}
.footer ul li a{
	color: #919191;
}
.footer ul li a:hover{
	color: #333;
}
.console{
	margin: 0 auto;
	min-width: 976px;
	/*max-width: 1440px;*/
	text-align: center;
}
.con-left{
	width: 266px;
	min-height: 768px;
	float: left;
}
.tab{
	width: 75px;
	float: left;
	background: #0288d1;
	min-height: 768px;
}
.tab li{
	width: 75px;
	height: 65px;
	line-height: 65px;
	float: left;
}
.tab li a{
	color: #fff;
}
.con{
	width: 190px;
	float: left;
	min-height: 768px;
	background: #f5f5f5;
	border-right:1px solid #ddd;
}
.con .list-nav{
	padding: 28px 0 0 0;
	background: #f5f5f5;
}
.con .list-nav li{
	width: 190px;
	background: #f5f5f5;
	float: left;
}
.list-nav h3{
margin-bottom: 20px;
}
.nav2{

}
.nav2 a{
	display: block;
	padding: 10px 0;
	margin-bottom: 3px;
	color: #333;
}
.nav2 a:hover{
	background: #fff;
}
.nav2 a.current{
	color: #29b6f6;
	background: #fff;
}
.con-right{
	padding:0 24px;
	overflow: hidden;
}
.con-right h1{
	font-size: 24px;
	margin-top: 24px;
	padding-bottom: 5px;
	margin-bottom: 16px;
	text-align: left;
	border-bottom: 1px solid #ddd;
}
.searchbox{
	margin-bottom: 20px;
	text-align: right;
}
/*table*/
table{
	width: 100%;
	border-collapse: collapse;
	border-spacing: 0px;
	border: 1px solid #ccc;
	margin-bottom: 20px;
}
#datalist th {
    font-weight: bold;
    text-align: center;
    height: 35px;
    line-height: 35px;
    border: 1px solid #ccc;
    background: #dadada;
}
#datalist td{
	height: 32px;
	line-height: 32px;
	text-align: center;
	border: 1px solid #ccc;
}
input.btn-edit,input.btn-delete{
	padding-left: 15px;
	cursor: pointer;
}
input.btn-edit{
	background: url(modify.png) no-repeat center left;
}
input.btn-delete{
	background: url(delete.png) no-repeat center left;
}
</style>