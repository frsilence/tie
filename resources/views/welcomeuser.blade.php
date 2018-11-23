<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 26px;
            }
        </style>
        <script type="text/javascript">
        window.onload=function(){
             setTimeout("location.reload()",100000);
        };
       
        </script>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title"><?php echo date("Y-m-d H:i:s.ms") ?></div>
                <p>已登录{{ $user['name'] }}</p>
                <a href="/auth/logout">登出</a>
            </div>
        </div>
    </body>
</html>
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
