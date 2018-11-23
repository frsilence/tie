@extends('templates/head')
@section('title','首页')
@section('content')
<style>
            
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
<div class="container">
	<div class="content">
		<div class="title">
			<?php echo date("Y-m-d H:i:s.ms") ?>
		</div>
		@if (Auth::check())
		<p>Welcome {{ Auth::user()->name }}</p>
		@else
		<a href="/auth/login">登录</a>
		@endif
	</div>
</div>
<?php
	echo URL::previous();
?>
@endsection