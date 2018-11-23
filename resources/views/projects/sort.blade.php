@extends('templates/head')
@section('title',$title)
@section('content')
<style>
	.panel-sort{
		height: 130px;
		overflow: auto;
	}
</style>
<div id='sorts'>
	<div class="row">
		<div class="col-md-12">
			<h4 class="page-header">
	            <a href="/">首页</a>><a href="{{ route('get_sort',['sort_id'=>$sort->id]) }}">{{ $sort->sort_name }}</a>
	        </h4>
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<input class="form-control" />
		</div>
		<div class="col-md-2">
			<button class="btn btn-default">搜索</button>
		</div>
	</div>
	<div class="panel row">
		<div class="panel-success">
			<div class="panel-heading">
				<h2 class="panel-title">分类：{{ $sort->sort_name }}</h2>
			</div>
			<div class="panel-body row">
				<div class="col-lg-4">
					<img src="{{ $sort->sort_image }}" style="max-height: 90px;"/>
				</div>
				<div class="col-lg-8">
					<h4>{{ $sort->sort_description }}</h4>
				</div>
			</div>
		</div>
		<div class="sort panel-primary col-md-3 col-lg-3" v-for="(forum,index) in forums" v-on:click="goForum(forum.id)" title="点击进入分区" style="cursor: pointer;">
			<div class="panel-heading">
				<h3 class="panel-title">@{{ forum.forum_name }}</h3>
			</div>
			<div class="panel-body panel-sort">
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4">
						<img v-bind:src="forum.forum_image" />
					</div>
					<div class="col-lg-8 col-md-5 col-sm-5">
						Description:@{{ forum.forum_description }}
					</div>
				</div>
				
			</div>
		</div>
	</div>
	
	<script src="/assets/js/controllers/sort.js"></script>
</div>


		  
@endsection