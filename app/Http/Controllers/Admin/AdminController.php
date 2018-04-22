<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;
use App\AdminLog;

class AdminController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
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
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,bmp|max:10240',
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
        $image = $imgThumb = Image::make($request->file('file')->getRealPath())->orientate();
        $width = $height = 1080;
        $widthThumb = 70;
        $heightThumb = 80;
        $image->height() > $image->width() ? $width = $widthThumb = null : $height = $heightThumb = null;

        \File::exists(public_path() . '/uploads/'.$dt->year.$dt->month .'/') or \File::makeDirectory(public_path() . '/uploads/'.$dt->year.$dt->month .'/', 0777, true);
        $uniqid = uniqid() . '_' . time();
        $fileNameWithPath = $dt->year.$dt->month .'/' . $uniqid;

        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path() . '/uploads/'. $fileNameWithPath . '.' . $extension);
        $imgThumb->resize($widthThumb, $heightThumb, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path() . '/uploads/'. $fileNameWithPath . '_thumb.' . $extension);
        
        return response()->json($fileNameWithPath . '_thumb.' . $extension);
    }

    public function getLogByYmd($ymd) {
        $logs = AdminLog::where('created_at', 'LIKE', "$ymd%")
            ->orderBy('created_at', 'DESC')
            ->with(['user'])->get();
        return $logs;
    }
}
