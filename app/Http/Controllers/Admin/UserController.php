<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use Input;
use App\User;
use App\UserGroup;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($group_id)
    {
        if($group_id == 1) {
            return redirect()->route('admin.userindex', 2);
        }
        $userGroup = UserGroup::findOrFail($group_id);
        $data['pageTitle'] = __('messages.usermanage');
        $data['subTitle'] = $userGroup->name;
        $data['group_id'] = $userGroup->id;
        $data['users'] = UserGroup::find($userGroup->id)->users;

        return view('admin.user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($group_id)
    {
        $loginuser = Auth::user();
        if($loginuser->group_id != 1){
            return redirect()->route('admin.userindex', $group_id);
        }
        $userGroup = UserGroup::findOrFail($group_id);
        $data['pageTitle'] = __('messages.usermanage');
        $data['subTitle'] = $userGroup->name;
        $data['group_id'] = $userGroup->id;
        return view('admin.user.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();

        $loginuser = Auth::user();
        if($loginuser->group_id != 1){
            $request->session()->flash('message', __('messages.error').':'.__('messages.manageronly'));
            return redirect()->route('admin.userindex', $inputs['group_id']);
        }
        //rules
        $rules = [
            'name' => 'required|min:2|max:30',
            'email' => 'required|email',
            'password' => 'required|min:6|max:32'
        ];
        //validation
        $validation = \Validator::make($inputs, $rules);
        //if fails
        if($validation->fails())
        {
            return redirect()->back()->withErrors($validation->errors())->withInput();
        }
        $password = \Hash::make($inputs['password']);

        $obj_user = new User();
        $obj_user->password = $password;
        $obj_user->name = $inputs['name'];
        $obj_user->email = $inputs['email'];
        $obj_user->group_id = $inputs['groupid'];
        $obj_user->profileimg = $inputs['profileimg'];
        $obj_user->save();

        return redirect()->route('admin.userindex', $inputs['group_id']);
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
    	$loginuser = Auth::user();
    	$data['pageTitle'] = __('messages.useredit');
        $data['subTitle'] = "";
        
    	if($loginuser->group_id != 1 && $id != $loginuser->id){
    		return redirect()->route('admin.useredit', $loginuser->id);
    	}
    	$data['user'] = User::findOrFail($id);
        return view('admin.user.edit', $data);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //inputs
        $inputs = $request->all();

        $loginuser = Auth::user();
        // manager can be change the other user infomations.
        if($loginuser->group_id != 1 && $request->userid != $loginuser->id){
            $request->session()->flash('message', __('messages.error').':'.__('messages.manageronly'));
            return redirect()->route('admin.userindex', $inputs['group_id']);
        }
        //rules
        $rules = [
            'name'=>'required|min:2|max:30',
            'email'=>'required|email',
        ];
        if($inputs['changepw'] == 1){
            $rules['password'] = 'required|min:6|max:32';
        }

        //validation
        $validation = \Validator::make($inputs, $rules);

        //if fails
        if($validation->fails())
        {
            return redirect()->back()->withErrors($validation->errors())->withInput();
        }
        
        if($inputs['password']){
            $password = \Hash::make($inputs['password']);
        }

        $obj_user = User::find($request->userid);
        if(isset($password)){
            $obj_user->password = $password;
        }
        $obj_user->name = $inputs['name'];
        $obj_user->email = $inputs['email'];
        if(isset($inputs['groupid'])) {
            $obj_user->group_id = $inputs['groupid'];
        }
        $obj_user->profileimg = $inputs['profileimg'];
        $obj_user->save();

        return redirect()->route('admin.main');
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
    
    // post only
    public function updateprofile(Request $request){
        $validator = \Validator::make($request->all(),
            [
                'file' => 'image',
            ],
            [
                'file.image' => 'The file must be an image (jpeg, png, bmp, or gif)'
            ]);
        if ($validator->fails())
            return array(
                'fail' => true,
                'errors' => $validator->errors()
            );
        $extension = $request->file('file')->getClientOriginalExtension();
        $filename = $request->file('file')->getClientOriginalName();
        $image = Image::make($request->file('file')->getRealPath());
        \File::exists(public_path() . '/uploads/profiles/') or \File::makeDirectory(public_path() . '/uploads/profiles/', 0777, true);
        $uniqidFileName = uniqid() . '_' . time() . '.' . $extension;
        $image->resize(120, 120)->save(public_path() . '/uploads/profiles/' . $uniqidFileName);
        return $uniqidFileName;
    }
}
