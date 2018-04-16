<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Type;
use App\TypeGroup;

class TypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index($group_id)
    {
        $typeGroup = TypeGroup::findOrFail($group_id);
        $data['pageTitle'] = __('messages.typemanage');
        $data['subTitle'] = $typeGroup->name;
        $data['group_id'] = $typeGroup->id;
        $data['types'] = TypeGroup::whereNull('deleted_at')->find($typeGroup->id)->types;

        return view('admin.type.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $typeGroup = TypeGroup::findOrFail($id);
        $data['pageTitle'] = __('messages.typemanage');
        $data['subTitle'] = $typeGroup->name;
        $data['group_id'] = $typeGroup->id;
        return view('admin.type.create', $data);
    }

    public function create_do(Request $request) {
        //inputs
        $inputs = $request->all();
        $typeGroup = TypeGroup::findOrFail($inputs['group_id']);

        //rules
        $rules = [
            'name'=>'required|min:0|max:32',
        ];

        //validation
        $validation = \Validator::make($inputs, $rules);

        //if fails
        if($validation->fails())
        {
            return redirect()->back()->withErrors($validation->errors())->withInput();
        }
        $obj = new Type();
        $obj->name = $inputs['name'];
        $obj->group_id = $inputs['group_id'];
        \AdminLog::saveWithLog($obj, 2, 1);
        return redirect()->route('type.index', ['id' => $inputs['group_id']]);
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


    public function updatetypes(Request $request)
    {
        $inputs = $request->all();
        //rules
        $rules = [
            'listorder.*'=>'required|integer|min:0|max:127',
            'name.*'=>'required|min:1|max:32',
        ];
        //validation
        $validation = \Validator::make($inputs, $rules);
        //if fails
        if($validation->fails())
        {
            return \Response::json(array('success' => false, 'message' => json_encode($validation->errors())), 200);
        }
        foreach($inputs['listorder'] as $key => $v){
            $obj = Type::where('id', $key)->first();
            $obj->listorder = $v;
            $obj->name = $inputs['name'][$key];
            $obj->save();
        }
        
        return \Response::json(array('success' => true, 'message' => "ok"), 200);
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
