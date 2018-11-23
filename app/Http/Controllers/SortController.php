<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Sort;
use Validator;
use Gate;
class SortController extends Controller
{
    public function showSort($sort_id)
    {
        $sort = Sort::find($sort_id);
        if($sort){
            return response()->view('projects.sort',['title'=>$sort->sort_name,'sort'=>$sort])->setStatusCode(200);
        }
        else{
            return view('errors.404');
        };
        
    }

    /**
     * 获取所有分区
     */
    public function getAllSort()
    {
        $sorts = Sort::where('active','true')->get();
        return response()->json(['message'=>'success','status'=>200,'sorts'=>$sorts])->setStatusCode(200);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addSort(Request $request)
    {
        if(Gate::denies('set_adminsort') and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        $sort_name=$request->input('sort_name');
        $sort_description = $request->input('sort_description');
        $sort_image = $request->file('image');
        $rule = ['jpg', 'png', 'gif','PNG','jpeg'];
        $validator = Validator::make($request->all(),[
            'sort_name'=>'required|unique:sorts,sort_name',
            'sort_description'=>'required',
            ]);
        if($validator->fails()){
            return response(['message'=>$validator->errors()->all(),'status'=>422])->setStatusCode(200);
        };
        if(!$request->hasFile('image')){
            return response(['message'=>['请选择图片'],'status'=>422])->setStatusCode(200);
        }
        $entension = $sort_image->getClientOriginalExtension();
        if (!in_array($entension, $rule)) {
            return response()->json(['message'=>['图片格式为jpg,png,gif,jpeg'],'status'=>422])->setStatusCode(200);
        };
        if($sort_image->isValid()){
            $tmpName = $sort_image ->getFileName();
            $image_path = "assets/images/sort";
            $image_name = time().rand(1000,9999).".".$entension;
            $sort_image->move($image_path,$image_name);
            $image_url = "/".$image_path."/".$image_name;
            $sort = Sort::create([
                'sort_name'=>$sort_name,
                'sort_description'=>$sort_description,
                'sort_image'=>$image_url,
                'active'=>'true']);
        if($sort){
            return response()->json(['message'=>'创建分类成功','status'=>200])->setStatusCode(200);
        };
        return response(['message'=>['创建失败'],'status'=>422])->setStatusCode(422);

        }
        
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSortBySortId(Request $request, $id)
    {
        if(Gate::denies('set_adminsort') and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        $validator = Validator::make($request->all(),[
            'sort_description'=>'required',
            ]);
        if($validator->fails()){
            return response(['message'=>$validator->errors()->all(),'status'=>422]);
        };
        $sort = Sort::where('id',$id)->where('active','true')->first();
        if(!$sort){
            return response()->json(['message'=>'Sort not found'])->setStatusCode(404);
        }
        else{
            $sort->sort_description=$request->input('sort_description');
            $sort->save();
            return response()->json(['message'=>'sort update successfully','status'=>200])->setStatusCode(200);
        };
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSortBySortId($sort_id)
    {
        if(Gate::denies('set_adminsort') and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        if(Gate::denies('delete_post',$sort_id)){
            return response()->json(['message'=>'not allows','status'=>403])->setStatusCode(200);
        };
        $sort = Sort::where('id',$sort_id)->where('active','true')->first();
        if(!$sort){
            return response()->json(['message'=>'Sort not found'])->setStatusCode(404);
        }
        else{
            $forums=$sort->forums;
            foreach ($forums as $key => $forum) {
                $posts = $forum->posts;
                foreach ($posts as $key => $post) {
                    $post->comments()->delete();
                    $post->is_delete = 'true';
                    $post->save();
                };
                $forum->active = 'false';
                $forum->save();
            };
            $sort->active = 'false';
            $sort->save();
            return response()->json(['message'=>'sort delete successfully','status'=>200])->setStatusCode(200);
        }
    }
}
