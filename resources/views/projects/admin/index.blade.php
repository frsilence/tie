@extends('templates/head')
@section('title',$title)
@section('content')
<style>
	.admin-panel{
		height: 300px !important;
	}
</style>
<div id="admin" style="width: 100%;">
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-header">
	            <a href="/">首页</a>><a href="{{ route('get_admin') }}">系统设置</a>
	        </h3>
		</div>
	</div>
	<div class="panel panel-primary admin-panel">
		<div class="panel-heading">
			<h4>系统信息</h4>
		</div>
		<div class="panel-body row">
			<div class="panel panel-default col-lg-2 col-md-2 col-sm-3">
				<div class="panel-heading">
					用户数量
				</div>
				<div class="panel-body">
					{{ $users_count }}
				</div>
			</div>
			<div class="panel panel-default col-lg-2 col-md-2 col-sm-3">
				<div class="panel-heading">
					分类数量
				</div>
				<div class="panel-body">
					{{ $sorts_count }}
				</div>
			</div>
			<div class="panel panel-default col-lg-2 col-md-2 col-sm-3">
				<div class="panel-heading">
					分区数量
				</div>
				<div class="panel-body">
					{{ $forums_count }}
				</div>
			</div>
			<div class="panel panel-default col-lg-2 col-md-2 col-sm-3">
				<div class="panel-heading">
					文章数量
				</div>
				<div class="panel-body">
					{{ $posts_count }}
				</div>
			</div>
		</div>
	</div>
	</br>
	<div class="panel panel-primary admin-panel">
		<div class="panel-heading">
			<h4>系统信息</h4>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="panel panel-default col-sm-4 col-lg-4 col-md-4" onclick="window.location.href='{{ route('get_adminsort') }}'" style="cursor: pointer;">
					<div class="panel-heading">
						分区设置
					</div>
					<div class="panel-body">
						<ul>
							<li>增加分区</li>
							<li>删除分区</li>
							<li>更新分区</li>
						</ul>
					</div>
				</div>
				<div class="panel panel-default col-sm-4 col-lg-4 col-md-4" onclick="window.location.href='{{ route('get_adminuser') }}'" style="cursor: pointer;">
					<div class="panel-heading">
						用户&权限设置
					</div>
					<div class="panel-body">
						<ul>
							<li>角色管理</li>
							<li>用户管理</li>
							<li>用户导入</li>
							
						</ul>
					</div>
				</div>
				<div class="panel panel-default col-sm-4 col-lg-4 col-md-4" onclick="window.location.href='{{ route('get_adminmanagetool') }}'" style="cursor: pointer;">
					<div class="panel-heading">
						管理工具
					</div>
					<div class="panel-body">
						<ul>
							<li>...</li>
							<li>...</li>
							<li>...</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection