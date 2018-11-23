<!DOCTYPE html>
<html>
	<head>
		<title>Tie-@yield('title')</title>
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<link href="/assets/css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
		<!-- Custom Theme files -->
		<link href="/assets/css/login-style.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="/assets/css/custom-styles.css" rel="stylesheet" type="text/css" />
		<!--js-->
		<script src="/assets/js/jquery.min.js"></script>
		<script src="/assets/js/bootstrap.min.js"></script>
		<script src="/assets/js/vue.js"></script> 
		<!--icons-css-->
		<link href="/assets/css/font-awesome.css" rel="stylesheet"> 
		<link rel="icon" href="/assets/logo.ico" type="img/x-ico" />
		<!--Google Fonts-->
		<link href='https://fonts.googleapis.com/css?family=Carrois+Gothic' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Work+Sans:400,500,600' rel='stylesheet' type='text/css'>
		<style type="text/css">
			body,html{
			margin: 0;
			padding:0;
			height: 100%;
			width: 100%; 
		}
		.container1{
			width:100%;
			min-height:100%;
			position: relative;
			padding: 0 30px 0 0;
			overflow-x: ;
			
		}
		.content{
			padding-bottom: 50px;
		}
		.header{
			width: 100%;
			bottom:0px;
			background-color: #D0E9C6;
		}
		.footer{
			width: 100%;
			height:50px;
			position: absolute;
			bottom:0px;
			left:0px;
			background-color: #D0E9C6;
			}
		
		#sorts,#forum,#post,#admin,#sortforum,#userpermission,#usersetting{
			padding: 0 40px 0px 40px;
			margin: 10px;
		}


		</style>
	</head>
	<body>
		<div class="container1">
			<header id="head" role="banner" class="header">
				<nav class="navbar navbar-default navbar-static-top main-navigation" role="navigation" id="site_navigation">
					<div class="nav">
						<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
				                <span class="sr-only">Toggle navigation</span>
				                <span class="icon-bar"></span>
				                <span class="icon-bar"></span>
				                <span class="icon-bar"></span>
		           			</button>
		
						</div>
						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse navbar-ex1-collapse">
							<ul class="nav navbar-nav">
								<li class="menu-item">
									<a href="/">首页</a>
								</li>
								<li id="menu-item-7" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-has-children menu-item-7 dropdown">
									<a title="分类" href="#" data-toggle="dropdown" class="dropdown-toggle">分类 <span class="caret"></span></a>
									<ul role="menu" class=" dropdown-menu">
										<li id="menu-item-8567" class="menu-item" v-for="sort in sorts">
											<a v-bind:title="sort.sort_name" v-bind:href="getSortUrl(sort.id)">@{{ sort.sort_name  }}</a>
										</li>
										
									</ul>
								</li>
								
								<li id="menu-item-3999" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-3999">
									<a title="问答" href="#" data-toggle="dropdown" class="dropdown-toggle">问答 <span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li class="menu-item">
											<a href="https://laravelacademy.org/laravel-docs-5_1" target="_blank">5.1中文手册</a>
										</li>
										<li class="menu-item">
											<a href="https://laravelacademy.org/laravel-tutorial-5_1" target="_blank">5.1基础教程</a>
										</li>
									</ul>
								</li>
								<li class="menu-item">
									<a href="/admin">系统设置</a>
								</li>
							</ul>

							@if(Auth::check())
								<ul class="nav navbar-nav navbar-right">
									<li>
										<a href="{{ route('get_usersetting') }}"><img src="{{ Auth::user()->user_image }}" style="height: 50px;width: 50px;" /></a>
									</li>
									<li>
										<a href="{{ route('get_usersetting') }}">{{ Auth::user()->name }}</a>
									</li>
									<li>
										<a href="{{ route('get_logout') }}">退出</a>									
									</li>
								</ul>

							@else	
								<ul class="nav navbar-nav navbar-right">
									<li>
										<a href="{{ route('get_login') }}">登录</a>
									</li>
									<li>
										<a href="{{ route('get_register') }}">注册</a>
									</li>
								</ul>
							@endif
						</div>
					</div>
				</nav>
			</header>

			<div id="content" class="content-wrapper">
				<div class="content">
					@yield('content')
				</div>								
			</div>
			<footer class="footer">
					<p class="text-center last-line">Copyright {{ date("Y") }} &copy;  <a href="{{ route('get_usersetting') }}" target="_blank">FFHH</a></p>
			</footer>
		</div>
		<script src="/assets/js/controllers/head.js"></script>
			
	</body>
</html>