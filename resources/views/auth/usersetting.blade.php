@extends('templates/head')
@section('title',$user->name.'信息设置')
@section('content')
<div id="usersetting">
	<div>
		<h3 class="page-header">
			<a href="/">首页</a>><a href="{{ route('get_usersetting') }}">用户设置</a>
		</h3>
	</div>
	<div class="row">
		<div class="panel-default col-lg-8">
			<div class="panel-heading">
				<h3 class="panel-title">个人信息</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="role">角色</label>
					<input v-model="user_inform.role_name" class="form-control" id="role" disabled="disabled" />
				</div>
				<div class="form-group">
					<label for="name">用户名</label>
					<input v-model="user_inform.name" class="form-control" id="name" disabled="disabled" />
				</div>
				<div class="form-group">
					<label for="email">邮箱</label>
					<input v-model="user_inform.email" class="form-control" id="eamil" disabled="disabled" />
				</div>
				<div class="form-group">
					<label for="sex">性别</label>
					<select v-model="user_inform.sex" class="form-control">
						<option value="未知">未知</option>
						<option value="男">男</option>
						<option value="女">女</option>
					</select>
				</div>
				<div class="form-group">
					<label for="birthday">生日</label>
					<input v-model="user_inform.birthday" id="birthday" class="form-control" type="date" />
				</div>
				<div class="form-group">
					<label for="area">地区</label>
					<div id="area">
						<select v-model="user_inform.area_p"  class="form-control" id="province" runat="server"  onchange="selectprovince(this);" style=" width:95px;display: inline;"></select>
						<select v-model="user_inform.area_c"  class="form-control" id="city" runat="server"  style=" width:95px;display: inline;"></select>
					</div>
				</div>
				<div class="form-group">
					<label for="telephone">电话</label>
					<input v-model="user_inform.telephone" id="telephone" class="form-control" />
				</div>
				<button class="btn btn-primary pull-right" style="width: 35%;" v-on:click="saveUserInform(user_inform)"><strong>保存信息</strong></button>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="panel-default">
				<div class="panel-heading">已发表文章</div>
				<div class="panel-body">
					@foreach ($user->posts as $post)
					<li><a title="点击进入文章" href="{{ route('get_post',['post_id'=>$post->id]) }}">{{ $post->title }}</a></li>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-8">
			<div class="panel-heading">用户头像</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<label for="user_image">上传图片</label>
						<input id="user_image" type="file" />
					</div>
					<div class="col-md-6">
						<img v-bind:src="user_inform.user_image"/>
					</div>
				</div>
				<button class="btn btn-primary pull-right" style="width: 35%;" v-on:click="updateUserImage()"><strong>保存头像</strong></button>
			</div>
		</div>
	</div>
</div>
<script src="/assets/js/controllers/usersetting.js"></script>
<script src="/assets/js/controllers/area.js"></script>


@endsection('content')
