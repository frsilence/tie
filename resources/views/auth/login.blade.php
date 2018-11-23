<!DOCTYPE HTML>
<html>
<head>
<title>登录</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Shoppy Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<link href="/assets/css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
<!-- Custom Theme files -->
<link href="/assets/css/login-style.css" rel="stylesheet" type="text/css" media="all"/>
<link rel="icon" href="/assets/logo.ico" type="img/x-ico" />
<!--js-->
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script> 
<!--icons-css-->
<link href="/assets/css/font-awesome.css" rel="stylesheet"> 
<!--Google Fonts-->
<link href='https://fonts.googleapis.com/css?family=Carrois+Gothic' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Work+Sans:400,500,600' rel='stylesheet' type='text/css'>
<!--static chart-->
</head>
<body>  
<div class="login-page">
    <div class="login-main">    
         <div class="login-head">
                <h1>Login</h1>
            </div>
            <div class="login-block">
                <form method="POST" action="{{ route('post_login') }}">
                {!! csrf_field() !!}
                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-sm-2" style="margin-left: 5%">邮箱</label>
                        <div class="col-md-9 col-sm-7">
                            <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required="email" autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-md-2 col-sm-2" style="margin-left: 5%">密码</label>
                        <div class="col-md-9 col-sm-7">
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>
                    </div>
                    <div class="forgot-top-grids">
                        <div class="forgot-grid">
                            <ul>
                                <li>
                                    <input type="checkbox" name="remember" id="remember" value="">
                                    <label for="remember"><span></span>记住我</label>
                                </li>
                            </ul>
                        </div>
                        <div class="forgot">
                            <a href="#">忘记密码?</a>
                        </div>
                        <div class="clearfix"> </div>
                    </div>
                    <input type="submit" value="登录">  
                    <h3>没有账号?<a href="{{ route('get_register') }}">  点击注册</a></h3>                
                </form>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul style="color:red;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                @endif
            </div>
      </div>
</div>
<!--inner block end here-->
<!--copy rights start here-->
<div class="copyrights">
     <p>Copyright &copy; 2018.Company name All rights reserved.</p>
</div>  
<!--COPY rights end here-->

<!--scrolling js-->
        <script src="/assets/js/jquery.nicescroll.js"></script>
        <script src="/assets/js/scripts.js"></script>
        <!--//scrolling js-->
<script src="/assets/js/bootstrap.min.js"> </script>
<!-- mother grid end here-->
</body>
</html>