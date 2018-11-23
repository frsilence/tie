<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Role;
use App\Models\Post;
use App\User;
use Gate;
use DB;
use App\Models\Sort;
use App\Models\Forum;
use App\Models\Permission;
use Validator;
use App\Comment\Myclass\MyFunction;
use Excel;
use Storage;

class UserController extends Controller
{
    public function test1105()
    {
        $function=new MyFunction();
        return $function->test();
        //test();
    }
    /**
     * 用户信息设置界面
     */
    public function userSetting()
    {
        $user=Auth::user();
        $user->posts;
        return view('auth.usersetting',['user'=>$user]);
    }

    /**
     * 获取当前登录用户信息
     */
    public function getUserSelf()
    {
        if(!Auth::check()){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        };
        $user=Auth::user();
        $user->roles;
        return response()->json(['message'=>'success','status'=>200,'userinform'=>$user])->setStatusCode(200);
    }
    /**
     * 更新当前登录账户的用户信息
     */
    public function updateUserSelfInform(Request $request)
    {
        if(!Auth::check()){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        };
        $user=Auth::user();
        if($request->has('sex')){
            $user->sex = $request->input('sex');
        }
        if($request->has('birthday')){
            $user->birthday = $request->input('birthday');
        }
        if ($request->has('area')) {
            $user->area = $request->input('area');
        }
        if($request->has('telephone')){
            $user->telephone = $request->input('telephone');
        }
        $user->save();
        return response()->json(['message'=>'success','status'=>200])->setStatusCode(200);
    }
    /**
     * 更新当前登录账号的用户头像
     */
    public function updateUserSelfImage(Request $request)
    {
        if(!Auth::check()){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        };
        $user=Auth::user();
        if($request->hasFile('user_image')){
            $user_image = $request->file('user_image');
            $entension = $user_image->getClientOriginalExtension();
            $rule = ['jpg', 'png', 'gif','PNG'];
            if (!in_array($entension, $rule)) {
                return response()->json(['message'=>['图片格式为jpg,png,gif'],'status'=>422])->setStatusCode(200);
            };
            if($user_image->isValid()){
                $image_path = "assets/images/user";
                $image_name = time().rand(1000,9999).".".$entension;
                $user_image->move($image_path,$image_name);
                //$oldforum_image = $forum->forum_image;
                $image_url = "/".$image_path."/".$image_name;
                $user->user_image = $image_url;
                //$oldforum_image = substr_replace($oldforum_image,"",0,1);
                //删除原图片
                //Storage::delete($oldforum_image);
                $user->save();
                return response()->json(['message'=>'success','status'=>200])->setStatusCode(200);

            };
        };
    }

    public function adminIndex()
    {
        if(Gate::denies('set_adminsort') and Gate::denies('set_adminuser') and Gate::denies('set_adminsetting')){
            return view('errors.401',['title'=>'无权限']);
        }
        $users_count = User::all()->count();
        $sorts_count = Sort::all()->count();
        $forums_count = Forum::all()->count();
        $posts_count = Post::all()->count();
        return response()->view('projects.admin.index',['title'=>'系统设置',
            'users_count'=>$users_count,
            'sorts_count'=>$sorts_count,
            'forums_count'=>$forums_count,
            'posts_count'=>$posts_count]);
    }
    public function adminsort()
    {
        if(Gate::denies('set_adminsort') and Gate::denies('set_adminsetting')){
            return view('errors.401',['title'=>'无权限']);
        }
        return view('projects.admin.sortforum',['title'=>'分区设置']);
    }
    public function adminUser()
    {
        if(Gate::denies('set_adminuser') and Gate::denies('set_adminsetting')){
            return view('errors.401',['title'=>'无权限']);
        }
        return view('projects.admin.userpermission',['title'=>'用户&权限设置']);
    }
    public function adminManageTool()
    {
        if(Gate::denies('set_adminsetting')){
            return view('errors.401',['title'=>'无权限']);
        }
        return 'tool';
    }

    /**
     *  查找用户
     *  
     */
    public function getUser(Request $request,$user_param)
    {
        if(Gate::denies('set_adminuser') and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        $user = User::find($user_param);
        if($user){
        }
        elseif($user=User::where('name',$user_param)->first()){

        }
        elseif($user=User::where('email',$user_param)->first()){

        }
        else{
            return response()->json(['message'=>'item not found','status'=>404])->setStatusCode(404);
        };
        $user->roles;
        return response()->json(['message'=>'success','status'=>200,'user'=>$user])->setStatusCode(200);
    }

    /**
     * 改变用户激活状态
     */
    public function changeUserActive($change,$user_id)
    {
        if(Gate::denies('set_adminuser') and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        $user = User::find($user_id);
        if($user){
            if($user->admin=='true'){
                return response()->json(['message'=>'error','status'=>400])->setStatusCode(400);
            }
            if($change=='true'){
                $user->active = 'true';
            }
            elseif($change=='false'){
                $user->active = 'false';
            }
            else{
                return response()->json(['message'=>'error','status'=>400])->setStatusCode(400);
            };
            $user->save();
            return response()->json(['message'=>'success','status'=>200])->setStatusCode(200);
        }
        else{
            return response()->json(['message'=>'item not found','status'=>404])->setStatusCode(404);
        };
    }

    /**
     * 批量导入并创建用户
     */
    public function batchImportUser(Request $request)
    {
        if(Gate::denies('set_adminuser') and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        if($request->hasfile('user_file')){
            $file=$request->file('user_file');
            if(!$file->isValid()){
                return response(['message'=>['文件出错'],'status'=>422])->setStatusCode(200);
            }
            $rule = ['xls', 'xlsx'];
            $entension = $file->getClientOriginalExtension();
            if (!in_array($entension, $rule)) {
                return response()->json(['message'=>['文件格式应该为xls或xlsx'],'status'=>422])->setStatusCode(200);
            };
            $filename = $file->getClientOriginalName();
            $savePath = 'assets/files/'.$filename;
            $bytes = Storage::put($savePath,file_get_contents($file->getRealPath()));
            if(!Storage::exists($savePath)){
                return response()->json(['message'=>['处理失败'],'status'=>422])->setStatusCode(200);
            };
            $filePath = 'storage/app/assets/files/'.iconv('UTF-8', 'GBK', $filename);
            $data = Excel::load($filePath)->all()->toArray();
            $success_count = 0;
            $fail_count = 0;
            foreach ($data as $key => $value) {
                $check_user=User::where('name',$value['name'])->orwhere('email',$value['email'])->first();
                if($check_user){
                    $fail_count++;
                    continue;
                }
                $user =  User::create([
                    'name' => $value['name'],
                    'email' => $value['email'],
                    'password' => bcrypt($value['password']),
                    'sex'=>$value['sex'],
                    'birthday'=>$value['birthday'],
                    'telephone'=>$value['telephone'],
                    'area'=>$value['area'],
                ]);
                if($user){
                    $user->roles()->attach(Role::where('slug','user')->first()->id);
                    $success_count++;
                }
                else{
                    $fail_count++;
                    continue;
                }
                
            }
            return response()->json(['message'=>'success','status'=>200,'success_count'=>$success_count,'fail_count'=>$fail_count])->setStatusCode(200);

        }
        else{
            return response(['message'=>['请选择文件'],'status'=>422])->setStatusCode(200);
        }
    }

    /**
     * 创建用户
     */
    public function addUser(Request $request)
    {
        if(Gate::denies('set_adminuser') and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        $validator = Validator::make($request->all(),[
            'name'=>'required|unique:users,name|max:40',
            'email'=>'required|unique:users,email|email|max:60',
            'password'=>'required|confirmed|min:6',
            'userrole'=>'required']);
        if($validator->fails()){
            return response(['message'=>$validator->errors()->all(),'status'=>422])->setStatusCode(200);
        }
        $role = Role::find($request->input('userrole'));
        if($role){
            $user=User::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'password'=>bcrypt($request->input('password')),
            'sex'=>"未知",'birthday'=>'2000-01-01',
            'telephone'=>'未知','area'=>'北京',
            'active'=>'true','admin'=>'false',
            'user_image'=>'/assets/images/user/user.png']);
            if($user){
                $user->roles()->attach($role->id);
                return response(['message'=>'success','status'=>200])->setStatusCode(200);
            }
        };
        return response(['message'=>"fail",'status'=>422])->setStatusCode(200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        if(Auth::check()){
            return view('welcomeuser',["user"=>Auth::user()]);
        }
        else{
            return view('welcome');
        }
        */

        return view('projects.index');
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
    /**
     * 获取全部角色
     */
    public function getAllRole()
    {
        if(Gate::denies('set_adminuser') and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        $roles = Role::all();
        foreach ($roles as $key => $value) {
            $value->permissions;
        };
        return response()->json(['message'=>'success','status'=>200,'roles'=>$roles])->setStatusCode(200);
    }
    /**
     * 创建角色
     */
    public function addRole(Request $request)
    {
        if(Gate::denies('set_adminuser') and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        $validator = Validator::make($request->all(),['role_name'=>'required|unique:roles,name',
                                    'role_slug'=>'required|unique:roles,slug',
                                    ]);
        if($validator->fails()){
            return response(['message'=>$validator->errors()->all(),'status'=>422])->setStatusCode(200);
        };
        $role=Role::create(['name'=>$request->input('role_name'),'slug'=>$request->input('role_slug')]);
        if($role){
            if($request->has('role_permission')){
                foreach ($request->input('role_permission')  as $key => $value) {
                $permission = Permission::find($value);
                if($permission){
                    $role->permissions()->attach($permission->id);
                }
                else{
                    continue;
                };
           };
            }
           return response()->json(['message'=>'success','status'=>200])->setStatusCode(200); 
        }
        else{
            return response()->json(['message'=>'create role fail','status'=>404])->setStatusCode(200);
        };
    }
    /**
     * 更新角色权限
     */
    public function updateRole(Request $request,$role_id)
    {
        if(Gate::denies('set_adminuser') and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        $input=$request->all();
        $role = Role::find($role_id);
        if(!$role){
            return response()->json(['message'=>['role not found'],'status'=>404])->setStatusCode(200);
        }
        else{
            DB::table('roles_permissions')->where('role_id',$role_id)->delete();
            if($request->has('permission')){
                foreach ($request->input('permission')  as $key => $value) {
                $permission = Permission::find($value);
                if($permission){
                    $role->permissions()->attach($permission->id);
                }
                else{
                    continue;
                };
            };
            }
            $role->save();
            return response()->json(['message'=>'success','status'=>200])->setStatusCode(200);     
        };
    }
    /**
     * 获取全部权限
     */
    public function getAllPermission()
    {
        if(Gate::denies('set_adminuser') and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        $permissions = Permission::all();
        return response()->json(['message'=>"success","status"=>200,"permissions"=>$permissions])->setStatusCode(200);
    }



    public function test()
    {
        $post = Role::find(22);
        return $post->users;
        
    }
    /**
     * 为用户分配/更新角色（用户只能同时拥有一个角色）
     */
    public function addRoleToUser($user_id,$role_id)
    {
        if(Gate::denies('set_adminuser') and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        $user = User::where('id',$user_id)->where('active','true')->first();
        $role = Role::find($role_id);
        if($user and $role){
            if($user->admin=='true'){
                return response()->json(['message'=>'error','status'=>400])->setStatusCode(400);
            }
            if($user->roles()->count()>0){
                $nowrole_id = $user->roles[0]->id;
                DB::table('user_role')->where('user_id',$user->id)->where('role_id',$nowrole_id)->update(['role_id'=>$role_id,'updated_at'=>date("Y-m-d H:i:s")]);
            }
            else{
                $user->roles()->attach($role->id); 
            };         
            return response()->json(['message'=>'success','status'=>200])->setStatusCode(200);
        }
        else{
            return response()->json(['message'=>'user or role not found','status'=>404])->setStatusCode(200);
        };
    }
    /**
     * 删除角色
     */
    public function deleteRole($role_id)
    {
        if(Gate::denies('set_adminuser') and Gate::denies('set_adminsetting')){
            return response()->json(['message'=>'Unauthorized','status'=>401])->setStatusCode(401);
        }
        $role = Role::find($role_id);
        if(!$role){
            return response()->json(['message'=>'item not found','status'=>404])->setStatusCode(200);
        };
        $user = $role->users()->count();
        if($user>0){
            return response()->json(['message'=>'this role has beening used','status'=>400])->setStatusCode(200);
        };
        DB::table('roles_permissions')->where('role_id',$role_id)->delete();
        Role::find($role_id)->delete();
        return response()->json(['message'=>'delete success','status'=>200])->setStatusCode(200);
    }
}
