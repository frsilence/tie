<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\Sort;
use App\User;
use Validator;
use Auth;
use Storage;
use Gate;
class ForumController extends Controller
{
    public function showForum($forum_id)
    {
        $forum=Forum::where('id',$forum_id)->where('active','true')->first();
        $forum_url = route('get_forum',['id'=>$forum_id]);
        if($forum){
            $sort_name = $forum->sorts->sort_name;
            $forum['sort_name'] = $sort_name;
            return response()->view('projects.forum',['title'=>$forum->forum_name,'forum_url'=>$forum_url,'forum'=>$forum])->setStatusCode(200);
        }
        else{
            return response()->view('errors.404')->setStatusCode(404);
        };
    }
    
    /**
     * 获取全部分区
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllForums()
    {
        $forums = Forum::all();
        $forums_num = $forums->count();
        return response()->json(["message"=>"Forum required successfully!","forums_num"=>$forums_num,"forums"=>$forums])->setStatusCode(200);
        //return response($fourms->toArray(),200);
    }
    /**
     * 获取全部激活分区
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllActiveForums()
    {
        $forums = Forum::where('active','true')->get();
        foreach ($forums as $key => $forum) {
            $sort_name = $forum->sorts->sort_name;
            $forum['sort_name']=$sort_name;
        };
        $forums_num = $forums->count();
        return response()->json(["message"=>"Forum required successfully!","forums_num"=>$forums_num,"forums"=>$forums->toArray()])->setStatusCode(200);
        //return response($fourms->toArray(),200);
    }
    /**
     * 根据sort_id获取分区信息
     *
     * @return \Illuminate\Http\Response
     */
    public function getForumBySortId($sort_id)
    {
        $sort = Sort::where('id',$sort_id)->where('active','true')->first();
        if(!$sort){
            return response()->json(['message'=>'item not found','status'=>404])->setStatusCode(200);
        }
        else{
            $forums = Forum::where('sort_id',$sort_id)->where('active','true')->paginate();
            return response()->json(['message'=>'successfully','status'=>200,'forums'=>$forums->toArray()])->setStatusCode(200);
        };
              
    }

    /**
     * 按ID获取分区
     *
     * @return \Illuminate\Http\Response
     */
    public function getById($id)
    {
        $forum = Forum::find($id);
        $posts_num = $forum->posts()->count();
        $forum['posts_num'] = $posts_num;
        $sort_name = $forum->sorts->sort_name;
        $forum['sort_name'] = $sort_name;
        return response()->json(["forum"=>$forum])->setStatusCode(200);
    }
    /**
     * 按名称获取分区
     *
     * @return \Illuminate\Http\Response
     */
    public function getByName($name)
    {
        $forum = Forum::where('forum_name',$name)->get();
        return response()->json(["forum"=>$forum])->setStatusCode(200);
    }
    

    /**
     * 添加分区
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeForum(Request $request)
    {
        if(Gate::denies('set_adminsort') and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        $validator = Validator::make($request->all(),['forum_name'=>'required|unique:forums',
                                    'forum_description'=>'required',
                                    'forum_sort'=>'required']);
        if($validator->fails()){
            return response(['message'=>$validator->errors()->all(),'status'=>422])->setStatusCode(200);
        };
        if(!$request->hasFile('image')){
            return response(['message'=>['请选择图片'],'status'=>422])->setStatusCode(200);
        };
        $forum_name = $request->input('forum_name');
        $forum_description = $request->input('forum_description');
        $forum_sort = $request->input('forum_sort');
        $forum_image = $request->file('image');
        $sort_id = Sort::where('sort_name',$forum_sort)->first()->id;
        $user_id = Auth::user()->id;
        $entension = $forum_image->getClientOriginalExtension();
        $rule = ['jpg', 'png', 'gif','PNG'];
        if (!in_array($entension, $rule)) {
            return response()->json(['message'=>['图片格式为jpg,png,gif'],'status'=>422])->setStatusCode(200);
        };
        if($forum_image->isValid()){
            $image_path = "assets/images/forum/";
            $image_name = time().rand(1000,9999).".".$entension;
            $forum_image->move($image_path,$image_name);
            $image_url = "/".$image_path."/".$image_name;
            //$savePath = $image_path.$image_name;
            //Storage::put($savePath,file_get_contents($forum_image->getRealPath()));
            //if(!Storage::exists($savePath)){
                //exit('保存文件失败！');
            //};
            $forum = Forum::create([
                'forum_name'=>$forum_name,
                'forum_description'=>$forum_description,
                'forum_image'=>$image_url,
                'active'=>'true',
                'create_user'=>$user_id,
                'sort_id'=>$sort_id]);
        if($forum){
            return response()->json(['message'=>'创建分区成功','status'=>200])->setStatusCode(200);
        };
        return response(['message'=>['创建失败'],'status'=>422])->setStatusCode(422);

        }
        $input = $request->all();
        unset($input['_method']);
        $input['create_user']=$user_id;
        $input['active']="true";
        $forum=Forum::create($input);

        return response()->json(['message'=>'Create forum successfully!','forum_id'=>$forum->id])->setStatusCode(200);
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
     * 更新指定id的分区
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateForum(Request $request, $id)
    {
        if(Gate::denies('set_adminsort') and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        $validator=Validator::make($request->all(),[
            'forum_sort'=>'required',
            'forum_description'=>'required',]);
        if($validator->fails()){
            return response(['message'=>$validator->errors()->all(),'status'=>422])->setStatusCode(200);
        };
        if(!Forum::find($id)){
            return response(['message'=>'Forum not found!','status'=>404])->setStatusCode(200);
        };
        $forum = Forum::find($id);
        $forum_description = $request->input('forum_description');
        $forum_sort = $request->input('forum_sort');
        $forum->forum_description=$forum_description;
        $forum->sort_id = Sort::where('sort_name',$forum_sort)->first()->id;
        if($request->hasFile('image')){
            $forum_image = $request->file('image');
            $entension = $forum_image->getClientOriginalExtension();
            $rule = ['jpg', 'png', 'gif','PNG'];
            if (!in_array($entension, $rule)) {
                return response()->json(['message'=>['图片格式为jpg,png,gif'],'status'=>422])->setStatusCode(200);
            };
            if($forum_image->isValid()){
                $image_path = "assets/images/forum";
                $image_name = time().rand(1000,9999).".".$entension;
                $forum_image->move($image_path,$image_name);
                $oldforum_image = $forum->forum_image;
                $image_url = "/".$image_path."/".$image_name;
                $forum->forum_image = $image_url;
                //$oldforum_image = substr_replace($oldforum_image,"",0,1);
                //删除原图片
                //Storage::delete($oldforum_image);

            };
        };
        $forum->save();
        if($forum){
            return response()->json(['message'=>'更新分区成功','status'=>200])->setStatusCode(200);
        };
        return response(['message'=>['更新失败'],'status'=>422])->setStatusCode(422);
    }

    public  function test(){
    Storage::delete('\assets\images\forum\15409655211212.png');
}

    /**
     * 删除分区
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyForum($id)
    {
        if(Gate::denies('set_adminsort') and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        $forum = Forum::find($id);
        if($forum){
            $posts = $forum->posts;
            foreach ($posts as $key => $post){
                $post->comments()->delete();
                $post->is_delete = 'true';
                $post->save();
            };
            $forum->active = 'false';
            $forum->save();
            return response()->json(["message"=>"Delete forum successfully!",'status'=>200])->setStatusCode(200);
        }
        else{
            return response()->json(["message"=>"Forum not found!",'status'=>404])->setStatusCode(404);
        };
    }
}
