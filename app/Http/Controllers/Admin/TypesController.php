<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $typeGroup = \App\TypeGroup::findOrFail($id);
        $data['pageTitle'] = __('messages.typemanage');
        $data['subTitle'] = $typeGroup->name;
        $data['group_id'] = $typeGroup->id;
        return view('admin.type.create', $data);
    }

    public function create_do(Request $request) {
        //inputs
        $inputs = $request->all();
        $typeGroup = \App\TypeGroup::findOrFail($inputs['group_id']);

        //rules
        $rules = [
            'name'=>'required|min:4|max:30',
        ];

        //validation
        $validation = \Validator::make($inputs, $rules);

        //if fails
        if($validation->fails())
        {
            return redirect()->back()->withErrors($validation->errors())->withInput();
        }
        $obj = new \App\Type();
        $obj->name = $inputs['name'];
        $obj->group_id = $inputs['group_id'];
        $obj->save();
        return redirect()->route('typegroup', ['id' => $inputs['group_id']]);
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
}
