@extends('templates/head')
@section('title',$title)
@section('content')
<style>
	.panel-sort{
		height: 150px;
		min-height: 200px !important;
	}
	.panel-forum{
		height: 150px;
		min-height: 300px !important;
	}
	img{
		
		max-width: 100% !important;
	}
	.popup-form {
	  position: fixed;
	  top: 20%;
	  left: 20%;
	  right: 10%;
	  width: 60%;
	  height: 80%;
	  max-height: 450px;
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
<div id="sortforum">
	<div class="row">
		<div class="col-lg-12">
			<h3 class="page-header">
				<a href="/">首页</a>><a href="{{ route('get_admin') }}">系统设置</a>><a href="{{ route('get_adminsort') }}">分区设置</a>
			</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#sortsetting" data-toggle="tab">分类设置</a>
						</li>
						<li>
							<a href="#forumsetting" data-toggle="tab">分区设置</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="sortsetting">
							<div class="panel-body row">
								<div class="row form-group">
									<div class="col-lg-6">
										<input class="form-control" v-model="seach_sort"/>
									</div>
									<div class="col-lg-4">
										<button class="btn btn-default" v-on:click="get_mysort(seach_sort)">搜索</button>
									</div>
									<div class="col-lg-2">
										<button class="btn btn-primary" v-on:click="showNewSort()">添加分类</button>
									</div>									
								</div>
								<div class="panel panel-default  panel-sort col-lg-3 col-md-3 col-sm-4"  v-for="sort in sorts">
									<div class="panel-heading">
										@{{ sort.sort_name }}
									</div>
									<div class="panel-body row">
										<div class="row">
											<div class="col-sm-5">
												<img v-bind:src="sort.sort_image" />
											</div>
											<div class="col-sm-7">
												@{{ sort.sort_description }}
											</div>
										</div>
										<div class="row" style="margin-top: 5px;">								
											<button class="btn btn-danger btn-sm col-lg-6 col-md-6 col-sm-6" style="width: 30%;margin-left: 10px;" v-on:click="deleteSort(sort)">删除</button>
											<button class="btn btn-primary btn-sm col-lg-6 col-md-6 col-sm-6" style="width: 30%;margin-right: 10px;" v-on:click="showUpdateSort(sort)">更新</button>
										</div>
									</div>
								</div>
								
							</div>
						</div>
						<div class="tab-pane fade" id="forumsetting">
							<div class="row form-group">
								<div class="col-lg-6">
									<input class="form-control" v-model="seach_sort" />
								</div>
								<div class="col-lg-4">
									<button class="btn btn-default" v-on:click="get_mysort(seach_sort)">搜索</button>
								</div>
								<div class="col-lg-2">
									<button class="btn btn-primary" v-on:click="showNewForum()">添加分区</button>
								</div>
							</div>
							<div class="panel-body row">
								<div class="panel panel-default  panel-forum col-lg-3 col-md-3 col-sm-4" v-for="forum in forums">
									<div class="panel-heading">
										@{{ forum.forum_name }}
									</div>
									<div class="panel-body" style="overflow: auto;">
										<div>
											<div class="row">
												<div class="col-sm-4">
													<img v-bind:src="forum.forum_image" />
												</div>
												<div class="col-sm-8">
													@{{ forum.forum_description }}
												</div>
											</div>
											<div style="width: 100%;">
												<button class="btn btn-danger btn-sm col-lg-6 col-md-6 col-sm-6" style="width: 30%;margin-left: 10px;" v-on:click="deleteForum(forum)">删除</button>
												<button class="btn btn-primary btn-sm col-lg-6 col-md-6 col-sm-6" style="width: 30%;margin-right: 10px;" v-on:click="showUpdateForum(forum)">更新</button>
											</div>
										</div>
										
									</div>
								</div>
							
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="popup-form update-sort">
		<header>
			<p class="pull-left">更新分类（ID：@{{ select_sort.id }}）</p>
		</header>
		<section>
			<form>
				<label for="post_title">名称</label>
				<p>@{{ select_sort.sort_name }}</p>
				<br />
				<label for="post_content">描述</label>
				<textarea v-model="select_sort.sort_description" id="post_content" placeholder="分区描述" type="text" class="form-control first" rows="3" maxlength="300" style="resize: none;"></textarea>
			</form>
			<span v-if="message.success!=null" class="label label-success" style="font-size: large;">@{{ message.success }}</span>
			<span v-for="error in message.error"  class="label label-danger" style="font-size: large;margin-top: 6px;">@{{ error }}</span>
		</section>
		<footer>
			<a v-on:click="closePopup()" class="btn btn-danger pull-left">关闭</a>
			<a v-on:click="updateSort(select_sort.id)" class="btn btn-primary pull-right">保存更新</a>
			<div class="clearfix"></div>
		</footer>
	</div>
	<div class="popup-form new-sort">
		<header>
			<p class="pull-left">添加分类</p>
		</header>
		<section>
			<form id="new_sortform" class="new_sortform">
				<label for="sort_name">名称</label>
				<input v-model="new_sort.sort_name" id="sort_name" class="form-control" maxlength="30" placeholder="输入分类名称"/>
				<br />
				<label for="sort_description">描述</label>
				<textarea v-model="new_sort.sort_description" id="sort_description" placeholder="分类描述" type="text" class="form-control first" rows="3" maxlength="300" style="resize: none;"></textarea>
				<label for="sort_image">分类图片</label>
				<input id="sort_image" type="file" name="sort_image" v-model="new_sort.sort_image"/>
			</form>
			<span v-if="message.success!=null" class="label label-success" style="font-size: large;">@{{ message.success }}</span>
			<span v-for="error in message.error"  class="label label-danger" style="font-size: large;margin-top: 6px;">@{{ error }}</span>
		</section>
		<footer>
			<a v-on:click="closeNewSort()" class="btn btn-danger pull-left">关闭</a>
			<a v-on:click="createNewSort()" class="btn btn-primary pull-right">提交保存</a>
			<div class="clearfix"></div>
		</footer>
	</div>
	<div class="popup-form new-forum">
		<header>
			<p class="pull-left">添加分区</p>
		</header>
		<section>
			<form id="new_sortform1" class="new_sortform1">
				<label for="forum_name">名称</label>
				<input v-model="new_forum.forum_name" id="forum_name" class="form-control" maxlength="30" placeholder="输入分区名称"/>
				<label for="forum_sort">所属分类</label>
				<select v-model="new_forum.forum_sort" id="forum_sort" class="form-control">
					<option v-for="sort in sorts">@{{ sort.sort_name }}</option>
				</select>
				<label for="forum_description">描述</label>
				<textarea v-model="new_forum.forum_description" id="forum_description" placeholder="分类描述" type="text" class="form-control first" rows="3" maxlength="300" style="resize: none;"></textarea>
				<label for="sort_image">分区图片</label>
				<input id="forum_image" type="file" name="forum_image" v-model="new_forum.forum_image"/>
			</form>
			<span v-if="message.success!=null" class="label label-success" style="font-size: large;">@{{ message.success }}</span>
			<span v-for="error in message.error"  class="label label-danger" style="font-size: large;margin-top: 6px;">@{{ error }}</span>
		</section>
		<footer>
			<a v-on:click="closeNewForum()" class="btn btn-danger pull-left">关闭</a>
			<a v-on:click="createNewForum()" class="btn btn-primary pull-right">提交保存</a>
			<div class="clearfix"></div>
		</footer>
	</div>
	<div class="popup-form update-forum">
		<header>
			<p class="pull-left">更新分区（ID:@{{ selected_forum.sort_id }}）</p>
		</header>
		<section>
			<form>
				<label for="forum_name1">名称</label>
				<input v-model="selected_forum.forum_name" id="forum_name1" class="form-control" maxlength="30" disabled="disabled"/>
				<label for="forum_sort1">所属分区</label>
				<select v-model="selected_forum.sort_name" id="forum_sort1" class="form-control">
					<option selected="selected">@{{ selected_forum.sort_name }}</option>
					<option v-for="sort in sorts" >@{{ sort.sort_name }}</option>
				</select>
				<label for="forum_description1">描述</label>
				<textarea v-model="selected_forum.forum_description" id="forum_description1" placeholder="分类描述" type="text" class="form-control first" rows="3" maxlength="300" style="resize: none;"></textarea>
				<label for="forum_newimage">分区图片</label>
				<input id="forum_newimage" type="file" name="forum_image1"/>
			</form>
			<span v-if="message.success!=null" class="label label-success" style="font-size: large;">@{{ message.success }}</span>
			<span v-for="error in message.error"  class="label label-danger" style="font-size: large;margin-top: 6px;">@{{ error }}</span>
		</section>
		<footer>
			<a v-on:click="closeUpdateForum()" class="btn btn-danger">关闭</a>
			<a v-on:click="updateForum(selected_forum.id)" class="btn btn-primary pull-right">提交保存</a>
		</footer>
	</div>
	<script src="/assets/js/controllers/admin-sortforum.js"></script>
	
</div>
@endsection