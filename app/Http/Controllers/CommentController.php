<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\Comment;
use App\Models\Post;
use Validator;
class CommentController extends Controller
{

    //public function __construct(){
       // $this->middleware('auth');
   // }

    /**
     *获取指定文章ID的评论
     *
     * @return \Illuminate\Http\Response
     */
    public function getCommentByPostid($post_id)
    {
        $post = Post::find($post_id);
        if(!$post){
            return response()->json(['message'=>"post not found",'status'=>404])->setStatusCode(404);
        }
        else{
            $comments = $post->comments()->orderBy('floor')->paginate($perPage=10,$columns=['*'],$pageName='page');
            //return response($comments);
            return response()->json(['message'=>"comment required successfully",'status'=>200,'comments'=>$comments->toArray()])->setStatusCode(200);
        }
    }

    /**
     *获取指定用户ID的所有评论
     *
     * @return \Illuminate\Http\Response
     */
    public function getCommentByUserid($user_id)
    {
        if($user_id==0){
            $user_id = Auth::user()->id;
        }
        elseif(User::find($user_id)->active=='false'){
            return response()->json(['message'=>'comment not found','status'=>'404'])->setStatusCode(404);
        }
        $comments = Comment::where('user_id',$user_id)->paginate(3);
        if(!$comments){
            return response()->json(['message'=>'comment not found','status'=>'404'])->setStatusCode(404);
        }
        else{
            return response()->json(['message'=>'comment required successfully','status'=>'200','comments'=>$comments->toArray()])->setStatusCode(200);
        };

    }

    /**
     *获取指定文章ID和floor的所有评论
     *
     * @return \Illuminate\Http\Response
     */
    public function getComment($post_id,$floor)
    {
        $comments = Comment::where('post_id',$post_id)->where('floor',$floor)->paginate($perPage=3,$columns=['*'],$pageName='no',$page=1);
        if(!$comments){
            return response()->json(['message'=>'post not found','status'=>'404'])->setStatusCode(404);
        }
        else{
            return response()->json(['message'=>'comment required successfully','status'=>'200','comments'=>$comments->toArray()])->setStatusCode(200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存一个新的comment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeComment(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(),['post_id'=>'required',
                                  'content'=>'required']);
        if($validator->fails()){
            return response(['message'=>$validator->errors()->all(),'ststus'=>422]);
        };
        $post_id=$request->input('post_id');
        $lastcommet_id=$request->input('lastcomment_id');
        $post=Post::where('id',$post_id)->where('is_delete','false')->first();
        $comment=Comment::where('id',$lastcommet_id)->first();
        if($post!=null&&($comment!=null||$lastcommet_id==0)){
            if($post->comments()->count()==0){
                $floor = 1;
            }
            else{
                $floor=$post->comments()->orderBy('floor','desc')->first()->floor+1;
            };
            $input=$request->all();
            unset($input['_method']);
            $input['user_id']=$user->id;
            $input['user_name']=$user->name;
            $input['lastcomment_id']=0;
            $input['floor']=$floor;
            Comment::create($input);
            return response()->json(['message'=>'提交评论成功!','status'=>200])->setStatusCode(200);
        }
        else{
            return response()->json(['message'=>'item not found','status'=>404])->setStatusCode(404);            
        };
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
