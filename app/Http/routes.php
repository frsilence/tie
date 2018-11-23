<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/', 'UserController@index');
Route::get('/test',function(){
	return response()->view('test');
});
Route::get('/excel/out','ExcelController@export');
Route::get('/excel/in','ExcelController@import');
Route::get('/test1105','UserController@test1105');
Route::get('/vue',function(){
	return view('vuetest');
});
Route::get('/test','ForumController@test');
// 认证路由...
Route::get('auth/login', 'Auth\AuthController@getLogin')->name('get_login');
Route::post('auth/login', 'Auth\AuthController@postLogin')->name('post_login');
Route::get('auth/logout', 'Auth\AuthController@getLogout')->name('get_logout');
// 注册路由...
Route::get('auth/register', 'Auth\AuthController@getRegister')->name('get_register');
Route::post('auth/register', 'Auth\AuthController@postRegister')->name('post_register');

Route::group(['middleware'=>'auth'],function(){
	//User
	Route::get('user/setting','UserController@userSetting')->name('get_usersetting');
});
	

//View

//Admin
Route::group(['prefix'=>'/admin/','middleware'=>'auth'],function(){
	Route::get('','UserController@adminIndex')->name('get_admin');
	Route::get('adminsort','UserController@adminSort')->name('get_adminsort');
	Route::get('adminuser','UserController@adminUser')->name('get_adminuser');
	Route::get('adminmanagetool','UserController@adminManageTool')->name('get_adminmanagetool');
});

//forum
Route::get('forum/{forum_id}','ForumController@showForum')->name('get_forum');
//post
Route::get('post/{post_id}','PostController@showPost')->name('get_post');
//sort
Route::get('sort/{sort_id}','SortController@showSort')->name('get_sort');

//各动作请求API路由
Route::group(['prefix'=>'/api/'],function(){

	//Fourm
	//查
	Route::get('forum/getallforums','ForumController@getAllForums');
	Route::get('forum/getallactiveforums','ForumController@getAllActiveForums');
	Route::get('forum/getbyid/{id}','ForumController@getById');
	Route::get('forum/getbyname/{name}','ForumController@getByName');
	Route::get('forum/getforumbysortid/{sort_id}','ForumController@getForumBySortId');
	Route::group(['middleware'=>'auth'],function(){
		//增
		Route::post('forum/addforum','ForumController@storeForum');
		//改
		Route::put('forum/updateforum/{id}','ForumController@updateForum');
		//删
		Route::delete('forum/deleteforum/{id}','ForumController@destroyForum');
	});
	

	//UserForum
	//查
	Route::get('userforum/getforumbyuser','UserForumController@getForumByUser');
	Route::get('userforum/getuserbyforum','UserForumController@getUserByForum');
	//增
	Route::post('userforum/adduserforum','UserForumController@createUserForum');
	//改
	Route::put('userforum/updateuserforum/{forum_id}','UserForumController@updateUserForum');
	//删
	
	//Post
	
	//查
	Route::get('post/getpostbyforumid/{forum_id}','PostController@getPostByForumId');
	Route::get('post/getpostbypostid/{post_id}','PostController@getPostByPostId');
	Route::get('post/getpostbyuser','PostController@getPostByUser');
	
	Route::group(['middleware'=>'auth'],function(){
		//增
		Route::post('post/addpost','PostController@storePost');
		//改
		Route::put('post/updatepostbypostid/{post_id}','PostController@updatePostByPostid');
		//删
		Route::delete('post/deletepostbypostid/{post_id}','PostController@deletePostByPostid');
	});
	

	//Comment
	//增
	Route::post('comment/addcomment','CommentController@storeComment');
	//删
	//改
	//查
	Route::get('comment/getcommentbypostid/{post_id}','CommentController@getCommentByPostid');
	Route::get('comment/getcommentbyuserid/{user_id}','CommentController@getCommentByUserid');
	Route::get('comment/getcomment/{post_id}/{floor}','CommentController@getComment');

	//Sort
	Route::group(['middleware'=>'auth'],function(){
		//增
		Route::post('sort/addsort','SortController@addSort');
		//删
		Route::delete('sort/deletesort/{sort_id}','SortController@deleteSortBySortId');
		//改
		Route::put('sort/updatesort/{sort_id}','SortController@updateSortBySortId');
	});
	
	//查
	Route::get('sort/getsort/{sort_id}','SortController@getSortBySortId');
	Route::get('sort/getallsort','SortController@getAllSort');

	Route::group(['middleware'=>'auth'],function(){
		//User
		//查用户
		Route::get('user/getuserself','UserController@getUserSelf');
		Route::get('user/getuser/{user_param}','UserController@getUser');
		//更新用户激活状态
		Route::put('user/changeuseractive/{change}/{user_id}','UserController@changeUserActive');
		//批量导入用户信息并创建
		Route::post('user/batchimportuser','UserController@batchImportUser');
		//创建用户
		Route::post('user/adduser','UserController@addUser');
		//更新当前登录账号的用户信息
		Route::put('user/updateuserselfinform','UserController@updateUserSelfInform');
		//更新当前登录账户的用户头像
		Route::post('user/updateuserselfimage','UserController@updateUserSelfImage');
		//Permission
		//权限操作
		//查角色
		Route::get('permission/getallrole','UserController@getAllRole');
		//查权限
		Route::get('permission/getallpermission','UserController@getAllPermission');
		//增加角色
		Route::post('permission/addrole','UserController@addRole');
		Route::get('permission/test','UserController@test');
		//更新角色权限
		Route::put('permission/updaterole/{role_id}','UserController@updateRole');

		//给用户分配角色
		Route::post('permission/addroletouser/{user_id}/{role_id}','UserController@addRoleToUser');
		//删除角色
		Route::delete('permission/deleterole/{role_id}','UserController@deleteRole');
	});
});
?>