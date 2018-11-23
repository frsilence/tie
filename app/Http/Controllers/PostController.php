<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App;
use App\User;
use App\Models\Forum;
use App\Models\Post;
use App\Models\Userattention;
use Validator;
use Gate;

class PostController extends Controller
{

    public function showPost($post_id)
    {
        $post = Post::where('id',$post_id)->where('is_delete','false')->first();
        if(!$post){
            return response()->view('errors.404')->setStatusCode(404);
        }
        else{
            $post_url = route('get_post',['post_id'=>$post_id]);
            $forum = $post->forums;
            $forum_url = route('get_forum',['forum_id'=>$post->forum_id]);
            $sort_name = $forum->sorts->sort_name;
            $forum['sort_name'] = $sort_name;
            $post['update_post'] = Gate::allows('update_post',$post);
            $post['delete_post'] = Gate::allows('delete_post',$post);
            return response()->view('projects.post',['title'=>($post->title),'post'=>$post,'post_url'=>$post_url,'forum_url'=>$forum_url,
                'forum'=>$forum])->setStatusCode(200);
        };
    }

    /**
     * 按post_id查询post
     */
    public function getPostByPostId($post_id)
    {
        $posts = Post::where('id',$post_id)->where('is_delete','false')->first();
        return response()->json(['message'=>'required successfully','status'=>200,'post'=>$posts])->setStatusCode(200);
    }
    /**
     *按分区查询所有post
     *
     * @return \Illuminate\Http\Response
     */
    public function getPostByForumId($forum_id)
    {
        $posts = Post::where('forum_id',$forum_id)->where('is_delete','false')->orderBy('created_at','desc')->paginate($perPage=10,$columns=['*'],$pageName='page');
        $posts = $posts->toArray();
        for($i=0;$i<count($posts['data']);$i++){
            $user_name = User::find($posts['data'][$i]['user_id'])->name;
            $posts['data'][$i]['user_name']=$user_name;
            $posts['data'][$i]['delete_post'] = Gate::allows('delete_post',Post::find($posts['data'][$i]['id']));
        }
        return response()->json(['message'=>'request successfully','posts'=>$posts])->setStatusCode(200);
    }

    /**
     *按用户查询所有post
     *
     * @return \Illuminate\Http\Response
     */
    public function getPostByUser(Request $request)
    {
        if($request->has('user_id')){
            $user_id=$request->input('user_id');
        }
        elseif($request->has('user_name')){
            $user_name=$request->input('user_name');
            $user_id = User::where('name',$user_name)->first()->id;
        }
        else{
            return response('bad request',400);
        };
        $posts = Post::where('user_id',$user_id)->where('is_delete','false')->get();
        $posts_num = $posts->count();
        return response()->json(['message'=>'request successfully','posts'=>$posts,'posts_num'=>$posts_num])->setStatusCode(200);
    }
    /**
     * 创建新Post
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * 保存一个新post
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePost(Request $request)
    {
        //为满足create_post权限检查函数要求，传入一个空的Post对象
        if(Gate::denies('create_post',new Post()) and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>['Unauthorized'],'status'=>401])->setStatusCode(200);
        }
        $validator = Validator::make($request->all(),[
            'title'=>'required',
            'content'=>'required',
            'forum_id'=>'required',
            ]);
        if($validator->fails()){
            return response(['message'=>$validator->errors()->all(),'ststus'=>422]);
        };
        if(!Forum::where('id',$request->input('forum_id'))->where('active','true')->first()){
            return response()->json(['message'=>'Forum not found'])->setStatusCode(404);
        }
        $input = $request->all();
        unset($input['_method']);
        $user_id = Auth::user()->id;
        $input['user_id']=$user_id;
        $input['sort_id']=Forum::find($request->input('forum_id'))->sort_id;
        $input['can_comment']='true';
        $post=Post::create($input);
        $this->addExperience($user_id,$request->input('forum_id'),10);
        return response()->json(['message'=>'创建文章成功!','post_id'=>$post->id,'status'=>200])->setStatusCode(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 更新指定的post内容
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePostByPostid(Request $request, $post_id)
    {
        $post = Post::where('id',$post_id)->where('is_delete','false')->first();
        if(Gate::denies('update_post',$post) and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        $user_id = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'title'=>'required|max:255',
            'content'=>'required|max:1000',]);
        if($validator->fails()){
            return response(['message'=>$validator->errors()->all(),'status'=>422])->setStatusCode(200);
        };
        if(!$post){
            return response()->json(['message'=>'post not found','status'=>404]);
        }
        else{
            $post->title = $request->input('title');
            $post->content=$request->input('content');
            $post->save();
            return response()->json(['message'=>'post update successfully','status'=>200]);
        };
    }

    /**
     * 删除指定id的post,删除后其所拥有的comment被永久删除
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePostByPostid($post_id)
    {
        $post = Post::where('id',$post_id)->where('is_delete','false')->first();
        if(Gate::denies('delete_post',$post) and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        if(!$post){
            return response()->json(['message'=>'Post not found','status'=>'404'])->setStatusCode(200);
        };
        if(Gate::denies('delete_post',$post)){
            return response()->json(['message'=>'not allows','status'=>403])->setStatusCode(200);
        };
        $post = Post::where('id',$post_id)->where('is_delete','false')->first();
        $comments = $post->comments()->delete();
        $post->is_delete='true';
        $post->save();
        return response()->json(['message'=>'Post delete successfully','status'=>'200'])->setStatusCode(200);



        /*
        $user_id = Auth::user()->id;
        if(!Post::where('id',$post_id)->where('user_id',$user_id)->where('is_delete','false')->first()){
            return response()->json(['message'=>'Post not found','status'=>'404'])->setStatusCode(404);
        }
        else{
            $post = Post::where('id',$post_id)->where('is_delete','false')->first();
            $comments = $post->comments()->delete();
            $post->is_delete='true';
            $post->save();
            return response()->json(['message'=>'Post delete successfully','status'=>'200'])->setStatusCode(200);
        };
        */
    }
    /**
     *公共函数：经验增加函数
     * 
     */
    public function addExperience($user_id,$forum_id,$experience)
    {
        $userforum = Userattention::where('user_id',$user_id)->where('forum_id',$forum_id)->first();
        if(!$userforum){
            $userforum = Userattention::create(['user_id'=>$user_id,
                                                'forum_id'=>$forum_id,
                                                'admin'=>'false',
                                                'experience'=>10,
                                                'active'=>'true']);
        };
        $userforum->active='true';
        $userforum->experience=$userforum->experience+$experience;
        $userforum->save();
    }
}


?>