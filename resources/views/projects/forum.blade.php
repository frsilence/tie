@extends('templates/head')
@section('title',$title)
@section('content')
<style>
	p{
		color: #000000;
	}
	a.nowselected{
		color: #FFFFFF !important;
		background-color: #0000C2 !important;
	}
	.popup-form {
	  position: fixed;
	  top: 20%;
	  left: 10%;
	  right: 10%;
	  width: 80%;
	  height: 70%;
	  max-height: 500px;
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
<div id='forum' style="width: 100%;">
	<div class="row">
		<div class="col-md-12">
			<h4 class="page-header">
	            <a href="/">首页</a>><a href="{{ route('get_sort',['sort_id'=>$forum->sorts->id]) }}">{{ $forum->sorts->sort_name }}</a>><a href="{{ $forum_url }}">@{{ forum.forum_name }}</a>
	        </h4>
		</div>
	</div>
	<div class="row">
	
		<div class="col-md-12">
			<div class="panel panel-default">
				@if(Auth::user())
				<div class="panel-heading" style="padding: 15px 15px;">
					文章列表<button style="float: right;font-weight: 500;color: #0000C2;font-size: large;" class="btn btn-default" v-on:click="showPopup()">+添加新文章</button>
				</div>				
				@else
				<div class="panel-heading" style="padding: 15px 15px;">
					文章列表<button style="float: right;font-weight: 500;color: #0000C2;font-size: large;" class="btn btn-default"><a href="/auth/login">+登录发表文章</a></button>								
				</div>
				@endif
				</br>
	
				<div class="panel-body" >
					<div class="alert alert-info" v-for="post in getPostsList()">
						<a :href="post.url">@{{ post.title }}</a><p style="">author：@{{ post.user_name }} &nbsp;&nbsp;发表于：@{{ post.created_at }}</p>
						<button v-if="post.delete_post" v-on:click="deletePost(post.post_id)">删除</button>
					</div>
					<div id="app" v-cloak>
						<ul class="pagination">
							<li>
								<a v-if="currentPage == 1">首页</a>
								<a v-else href="javascript:;" @click="next(1)">首页</a>
							</li>
							<li v-if="currentPage<=1">
								<a>上一页</a>
							</li>
							<li v-else>
								<a href="javascript:;" @click="next(currentPage-1)">上一页</a>
							</li>
					
							<li v-for="item in pagingList">
								<a v-if="currentPage==item.key || sign ==item.key" v-bind:class="{ 'nowselected':(item.key==currentPage) }">@{{item.key}}</a>
								<a v-else href="javascript:;" @click="next(item.value)">@{{item.key}}</a>
							</li>
					
							<li v-if="currentPage>=totalPageCount">
								<a>下一页</a>
							</li>
							<li v-else>
								<a href="javascript:;" @click="next(currentPage+1)">下一页</a>
							</li>
							<li>
								<a v-if="totalPageCount == currentPage">尾页</a>
								<a v-else href="javascript:;" @click="next(totalPageCount)">尾页</a>
							</li>
						</ul>
						<p>共:@{{totalPageCount||0}}页,当前页为：第@{{currentPage||0}}页，每页最大数量：10</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="popup-form new-post">
		<header>
			<p class="pull-left">发表文章</p>
		</header>
		<section>
			<form>
				<label for="post_title">标题</label>
				<input v-model="new_post.title" id="post_title" class="form-control" maxlength="30" placeholder="输入文章标题"/>
				<br />
				<label for="post_content">内容</label>
				<textarea v-model="new_post.content" id="post_content" placeholder="文章内容" type="text" class="form-control first" rows="6" maxlength="300" style="resize: none;"></textarea>
			</form>
			<span v-if="message.success!=null" class="label label-success" style="font-size: large;">@{{ message.success }}</span>
			<span v-for="error in message.error"  class="label label-danger" style="font-size: large;margin-top: 6px;">@{{ error }}</span>
		</section>
		<footer>
			<a v-on:click="closePopup(new_post)" class="btn btn-danger pull-left">关闭</a>
			<a v-on:click="createComment(new_post)" class="btn btn-primary pull-right">保存</a>
			<div class="clearfix"></div>
		</footer>
	</div>
	<script src="/assets/js/controllers/forum.js"></script>
</div>
@endsection