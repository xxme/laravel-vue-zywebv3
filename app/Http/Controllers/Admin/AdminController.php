<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;

class AdminController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param  string  $showdate(format:YYYY-MM)
     * @return \Illuminate\Http\Response
     */
    public function dashboard($showdate = null)
    {
        // $user = Auth::user();
        // if($user->group_id > 2){
        //     return redirect('home');
        // }
        $data['pageTitle'] = __('messages.events');
        $data['subTitle'] = __('messages.eventsSubTitle');
        return view('admin.dashboard', $data);
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

    // post only
    public function updateImage(Request $request){
        $dt = Carbon::now();
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
        $width = 1080;
        $height = 1080;
        $image->height() > $image->width() ? $width = null : $height = null;

        \File::exists(public_path() . '/uploads/'.$dt->year.$dt->month .'/') or \File::makeDirectory(public_path() . '/uploads/'.$dt->year.$dt->month .'/', 0777, true);
        $uniqidFileName = uniqid() . '_' . time() . '.' . $extension;
        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $image->save(public_path() . '/uploads/'. $dt->year.$dt->month .'/' . $uniqidFileName);

        return response()->json($dt->year.$dt->month.'/'.$uniqidFileName);
    }
}
