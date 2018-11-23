<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Userattention;
use App\Models\Forum;
use Auth;
use App\User;

class UserForumController extends Controller
{
    /**
     * 获取指定用户的关注分区信息
     *
     * @return \Illuminate\Http\Response
     */
    public function getForumByUser(Request $request)
    {
        if($request->has('user_id')){
            $user_id = $request->input('user_id');
            $userforum=Userattention::where('user_id',$user_id)->where('active','true')->get();
        }
        elseif($request->has('user_name')){
            $user_name = $request->input('user_name');
            $user_id=User::where('name',$user_name)->first()->id;
            $userforum=Userattention::where('user_id',$user_id)->where('active','true')->get();
        }
        else{
            $user_id=Auth::user()->id;
            $userforum=Userattention::where('user_id',$user_id)->where('active','true')->get();
        };
        $userforum_num = $userforum->count();
        return response()->json(['message'=>'required successfully','userforum_num'=>$userforum_num,'userforum'=>$userforum,'user'=>User::find($user_id)]);
        
    }

    /**6
     * 获取指定分区的用户信息和对应分区信息
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserByForum(Request $request)
    {
        $forum_id = $request->input('forum_id');
        $forum_name = $request->input('forum_name');
        if($forum_id!=null){
            $userforums=Userattention::where('forum_id',$forum_id)->where('active','true')->get();
        }
        elseif($forum_name!=null){
            $forum_id = Forum::where('forum_name',$forum_name)->first()->id;
            $userforums=Userattention::where('forum_id',$forum_id)->where('active','true')->get();
        }
        else{
            return response('no param',403);
        };
        $userforums=Userattention::where('forum_id',$forum_id)->where('active','true')->get();
        $userforums_num = $userforums->count();
        $users = [];
        foreach ($userforums as $userforum){
            $users[] = $userforum->user;
        }
        $users_num = count($users);
        return response()->json(['message'=>'required successfully',"userforums_num"=>$userforums_num,'userforums'=>$userforums,'users_num'=>$users_num,'users'=>$users])->setStatusCode(200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createUserForum(Request $request)
    {
        $forum_id = request()->input('forum_id');
        $user_id=Auth::user()->id;
        //return response($forum_id.$user_id);
        if(Forum::find($forum_id)){
            if(Userattention::where('user_id',$user_id)->where('forum_id',$forum_id)->first()){
                $userforum=Userattention::where('user_id',$user_id)->where('forum_id',$forum_id)->first();
                if(($userforum->active)=="true"){
                    return response()->json(["message"=>"You have been attention this forum!"])->setStatusCode(403);
                }
                else{
                    $userforum->active="true";
                    $userforum->save();
                    return response()->json(["message"=>"Attention successfully!",'userforum'=>$userforum])->setStatusCode(200);
                };
                
            }
            else{
                $input=['user_id'=>$user_id,
                        'forum_id'=>$forum_id,
                        'admin'=>'false',
                        'experience'=>10,
                        'active'=>'true',
                ];
                Userattention::create($input);
                $userforum=Userattention::where('user_id',$user_id)->where('forum_id',$forum_id)->first();
                return response()->json(["message"=>"Attention successfully!",'userforum'=>$userforum])->setStatusCode(200);
            }
        }
        else{
            return response()->json(["message"=>"Forum not found!"])->setStatusCode(404);
        };
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * 更新用户关注
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $forum_id
     * @return \Illuminate\Http\Response
     */
    public function updateUserForum(Request $request, $forum_id)
    {
        $user_id = Auth::user()->id;
        $userforum = Userattention::where('forum_id',$forum_id)->where('user_id',$user_id)->first();
        if($userforum->active=='true'){
            $userforum->active='false';
            $userforum->save();
            return response()->json(['message'=>'disattention successfully','userforum'=>$userforum])->setStatusCode(200);
        }
        else{
            $userforum->active='true';
            $userforum->save();
            return response()->json(['message'=>'attention successfully','userforum'=>$userforum])->setStatusCode(200);
        }
        
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
