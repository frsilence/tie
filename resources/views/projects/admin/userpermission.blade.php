@extends('templates/head')
@section('title',$title)
@section('content')
<style>
	img{
		
		max-width: 100% !important;
	}
	.popup-form {
	  position: fixed;
	  top: 20%;
	  left: 30%;
	  right: 20%;
	  width: 50%;
	  height: 80%;
	  max-height: 450px;
	  max-width: 600px;
	  background-color: #fff;
	  box-shadow: 10px 2px 10px rgba(50, 50, 50, 0.4);
	  -webkit-box-shadow: 0 2px 10px rgba(50, 50, 50, 0.4);
	  -moz-box-shadow: 0 2px 10px rgba(50, 50, 50, 0.4);
	  display: none; }
	  .popup-form header p {
  margin: 0px 5px 10px 10px;
  font-weight: 600; }
 
.popup-form header{
	height: 50px;
	padding: 0 0 10px 0;
	background-color: #D3D3D3;
}
.popup-form section {
  padding: 10px;
  padding-bottom: 0px; }
 .popup-form form {
  padding: 10px;
  padding-bottom: 10px; }

.popup-form footer {
  width: 100%;
  padding: 10px;
  position: absolute;
  bottom: 0;
  background-color: #f2f4f3; }
</style>
<div id="userpermission">
	<div class="row">
		<div class="col-lg-12">
			<h3 class="page-header">
				<a href="/">首页</a>><a href="{{ route('get_admin') }}">系统设置</a>><a href="{{ route('get_adminuser') }}">用户&权限设置</a>
			</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#rolesetting" data-toggle="tab">角色管理</a>
						</li>
						<li>
							<a href="#usersetting" data-toggle="tab">用户管理</a>
						</li>
						<li>
							<a href="#importuser" data-toggle="tab">批量导入用户</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="rolesetting">
							<div class="panel-body row">
								<div class="row form-group">
									<div class="col-lg-4 col-md-4 col-sm-4">
										<input class="form-control"/>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-2">
										<button class="btn btn-default">搜索</button>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-2">
										<button class="btn btn-primary" v-on:click="showNewRole()">添加角色</button>
									</div>									
								</div>
								
								<div class="panel panel-default  panel-role col-lg-3 col-md-3 col-sm-4"  v-for="role in roles">
									<div class="panel-heading">
										@{{ role.name }}
									</div>
									<div class="panel-body row">
										<div>
											<p>标识：@{{ role.slug }}</p>
										</div>
										<div class="row" style="margin-top: 5px;">								
											<button v-bind:disabled="role.slug=='user'" class="btn btn-danger btn-sm col-md-6 col-sm-6" style="width: 30%;margin-left: 10px;" v-on:click="deleteRole(role)">删除</button>
											<button class="btn btn-primary btn-sm col-md-6 col-sm-6" style="width: 30%;margin-right: 10px;" v-on:click="showUpdateRole(role)">更新权限</button>
										</div>
									</div>
								</div>
									
							</div>
						</div>
						<div class="tab-pane fade" id="usersetting">
							<div class="row form-group">
								<div class="col-lg-4 col-md-4 col-sm-4">
									<input class="form-control" placeholder="用户名/用户ID/用户邮箱" v-model="search_userparam"/>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4">
									<button class="btn btn-default" v-on:click="searchUser(search_userparam)">搜索</button>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4">
									<button class="btn btn-primary" v-on:click="showNewUser()">添加用户</button>
								</div>
							</div>
							<div class="panel-body row">
								<div class="panel panel-default  panel-role col-lg-3 col-md-3 col-sm-4" v-if="user!=''">
									<div class="panel-heading">
										@{{ user.name }}
									</div>
									<div class="panel-body row">
										<p>用户ID: @{{ user.id }}</p>
										<p>用户邮箱：@{{ user.email }}</p>
										<p v-if="user.roles!=''">用户角色：@{{ user.roles[0].name }}-@{{ user.roles[0].slug}}</p>
										<p v-else>用户角色： 未分配-null</p>
										<div class="row" style="margin-top: 5px;">								
											<button v-if='user.active=="true"' class="btn btn-danger btn-sm col-md-6 col-sm-6" style="width: 30%;margin-left: 10px;" v-on:click="deleteUser(user)">用户失效</button>
											<button v-else class="btn btn-danger btn-sm col-md-6 col-sm-6" style="width: 30%;margin-left: 10px;" v-on:click="activeUser(user)">用户激活</button>
											<button v-if="user.active=='true'" class="btn btn-primary btn-sm col-md-6 col-sm-6" style="width: 30%;margin-right: 10px;" v-on:click="showUpdateUserrole(user)">更新角色</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="importuser">
							<form id="importuser-form">
								<div class="form-group">
									<label for="import_input">用户信息文件</label>
									<input id="import_input"  type="file" name="user_file"/><span v-for="error in message.error"  class="label label-danger" style="font-size: large;margin-top: 6px;">@{{ error }}</span>
									<p>Tips:支持文件格式为xls和xlsx，文件中必须包含字段：name;email;password;sex;telephone;birthday;area</p>
								</div>
							</form>
							<button class="btn btn-primary" v-on:click="importUser()">提交并执行用户创建</button>
						</div>					
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="popup-form new-role">
		<header>
			<p class="pull-left">新建角色</p>
		</header>
		<section>
			<form>
				<label for="role_name">名称(name)</label>
				<input id="role_name" class="form-control" v-model="newrole.role_name" />
				<label for="role_slug">标志(slug)</label>
				<input id="role_slug" class="form-control" v-model="newrole.role_slug" />
				<br />
				<label for="role_permission">权限</label>
				<div class="checkbox" v-for="permission in allpermission">
					<label><input v-model="newrole.role_permission" type="checkbox" v-bind:value="permission.id" />@{{ permission.name }}</label>
				</div>
				
			</form>
			<span v-if="message.success!=null" class="label label-success" style="font-size: large;">@{{ message.success }}</span>
			<span v-for="error in message.error"  class="label label-danger" style="font-size: large;margin-top: 6px;">@{{ error }}</span>
		</section>
		<footer>
			<a v-on:click="closeNewRole()" class="btn btn-danger pull-left">关闭</a>
			<a v-on:click="createNewRole(newrole)" class="btn btn-primary pull-right">提交保存</a>
			<div class="clearfix"></div>
		</footer>
	</div>
	<div class="popup-form update-role">
		<header>
			<p class="pull-left">更新角色(ID:@{{ selectrole.id }})</p>
		</header>
		<section>
			<form>
				<label for="role_name1">名称(name)</label>
				<input id="role_name1" class="form-control" v-model="selectrole.name" disabled="disabled"/>
				<label for="role_slug1">标志(slug)</label>
				<input id="role_slug1" class="form-control" v-model="selectrole.slug" disabled="disabled"/>
				<br />
				<label for="role_permission1">权限</label>
				<div class="checkbox" v-for="permission in allpermission">
					<label><input v-model="selectrole.role_permission" type="checkbox" v-bind:value="permission.id"/>@{{ permission.name }}</label>
				</div>
				
			</form>
			<span v-if="message.success!=null" class="label label-success" style="font-size: large;">@{{ message.success }}</span>
			<span v-for="error in message.error"  class="label label-danger" style="font-size: large;margin-top: 6px;">@{{ error }}</span>
		</section>
		<footer>
			<a v-on:click="closeUpdateRole()" class="btn btn-danger pull-left">关闭</a>
			<a v-on:click="updateRole(selectrole)" class="btn btn-primary pull-right">提交保存</a>
			<div class="clearfix"></div>
		</footer>
	</div>
	<div class="popup-form update-userrole">
		<header>
			<p class="pull-left">更新用户角色</p>
		</header>
		<section>
			<form>
				<label for="user_name">用户名称</label>
				<input id="user_name" class="form-control" v-model="selectuser.name" disabled="disabled"/>
				<label for="user_id">用户ID</label>
				<input id="user_id" class="form-control" v-model="selectuser.id" disabled="disabled"/>
				<br />
				<label for="user_role">分配角色</label>
				<select class="form-control" v-model="selectuser.userrole">
					<option v-for="role in roles"  v-bind:value="role.id">@{{ role.name }}-@{{ role.slug }}</option>
				</select>
				
			</form>
			<span v-if="message.success!=null" class="label label-success" style="font-size: large;">@{{ message.success }}</span>
			<span v-for="error in message.error"  class="label label-danger" style="font-size: large;margin-top: 6px;">@{{ error }}</span>
		</section>
		<footer>
			<a v-on:click="closeUpdateUserrole(user)" class="btn btn-danger pull-left">关闭</a>
			<a v-on:click="updateUserrole(selectuser)" class="btn btn-primary pull-right">提交保存</a>
			<div class="clearfix"></div>
		</footer>
	</div>
	<div class="popup-form new-user">
		<header>
			<p class="pull-left">新建用户</p>
		</header>
		<section>
			<form>
				<div class="input-group">
					<span class="input-group-addon"><strong>用户名称</strong></span>
					<input v-model="newuser.name" id="newuser_name" class="form-control" />
				</div>
				<br />
				<div class="input-group">
					<span class="input-group-addon"><strong>绑定邮箱</strong></span>
					<input v-model="newuser.email" id="newuser_email" class="form-control" />
				</div>
				<br />
				<div class="input-group">
					<span class="input-group-addon"><strong>密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码</strong></span>
					<input v-model="newuser.password" id="newuser_password" type="password" class="form-control" />
				</div>
				<br />
				<div class="input-group">
					<span class="input-group-addon"><strong>确认密码</strong></span>
					<input v-model="newuser.password_confirmation" type="password" class="form-control" />
				</div>
				<br />
				<div class="input-group">
					<span class="input-group-addon"><strong>分配角色</strong></span>
					<select class="form-control" v-model="newuser.userrole">
						<option v-for="role in roles" v-bind:value="role.id">@{{ role.name }}-@{{ role.slug }}</option>
					</select>
				</div>
				
			</form>
			<span v-if="message.success!=null" class="label label-success" style="font-size: medium;">@{{ message.success }}</span>
			<span v-for="error in message.error"  class="label label-danger" style="font-size: medium;margin-top: 6px;">@{{ error }}</span>
		</section>
		<footer>
			<a v-on:click="closeNewUser()" class="btn btn-danger pull-left">关闭</a>
			<a v-on:click="createNewUser(newuser)" class="btn btn-primary pull-right">提交保存</a>
			<div class="clearfix"></div>
		</footer>
	</div>
	<script src="/assets/js/controllers/admin-userpermission.js"></script>
</div>
@endsection