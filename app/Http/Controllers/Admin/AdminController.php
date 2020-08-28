<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;
use App\AdminLog;
use Storage;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

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
    public function vue()
    {
        return view('admin.dashboard');
    }

    // post only
    public function updateImage(Request $request){
        $dt = Carbon::now();
        $input_data = $request->all();
        $validator = \Validator::make(
            $input_data, [
            'image_file.*' => 'required|mimes:jpg,jpeg,png,bmp|max:10240'
            ],[
                'image_file.*.required' => 'Please upload an image',
                'image_file.*.mimes' => 'Only jpeg,png and bmp images are allowed',
                'image_file.*.max' => 'Sorry! Maximum allowed size for an image is 20MB',
            ]
        );
        if ($validator->fails())
            return array(
                'fail' => true,
                'errors' => $validator->errors()
            );
        $files = $request->file('file');
        $thumbs = array();

        $disk = Storage::disk('s3');
        $s3client = $disk->getDriver()->getAdapter()->getClient();
        $bucket = \Config::get('filesystems.disks.s3.bucket');

        foreach($files as $file) {
            $extension = $file->getClientOriginalExtension();
            $filename = $file->getClientOriginalName();
            $image = Image::make($file->getRealPath())->orientate();
            $width = $height = 1080;
            // $widthThumb = 70;
            // $heightThumb = 80;
            $image->height() > $image->width() ? $width = $widthThumb = null : $height = $heightThumb = null;

            \File::exists(public_path() . '/uploads/'.$dt->year.$dt->month .'/') or \File::makeDirectory(public_path() . '/uploads/'.$dt->year.$dt->month .'/', 0777, true);
            $uniqid = uniqid() . '_' . time();
            $fileNameWithPath = $dt->year.$dt->month .'/' . $uniqid;

            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path() . '/uploads/'. $fileNameWithPath . '.' . $extension);

            // $contents = Storage::get(public_path() . '/uploads/'. $fileNameWithPath . '.' . $extension); //ファイルを読み取る
            // Storage::disk('s3')->put('/uploads/'. $fileNameWithPath . '.' . $extension, $image, 'public'); // Ｓ３にアップ
            $s3key = 'uploads/'. $fileNameWithPath . '.' . $extension;
            $result = $s3client->putObject([
                'Bucket' => $bucket,
                'Key'    => $s3key,
                'Body'   => $image
            ]);
            // $thumbname = $fileNameWithPath . '_thumb.' . $extension;
            array_push($thumbs, $fileNameWithPath . '.' . $extension);

            // if ($disk->exists($s3key)) {
            //     $command = $s3client->getCommand('GetObject', [
            //         'Bucket'   => $bucket,
            //         'Key'=> $s3key,
            //     ]);

            //     $request = $s3client->createPresignedRequest($command, '+30 minutes');
            //     $url = (string)$request->getUri();
            //     $presignedUrl = str_replace("s3-koyoshieki.s3-ap-northeast-1.amazonaws", "s3.koyoshieki", $url);
            //     array_push($s3bigimgs, $presignedUrl);
            // }
            // array_push($thumbs['s3url'], $result);

            // $imgThumb->resize($widthThumb, $heightThumb, function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save(public_path() . '/uploads/'. $thumbname);

        }
        
        return response()->json($thumbs);
    }

    public function getLogByYmd($ymd) {
        $logs = AdminLog::where('created_at', 'LIKE', "$ymd%")
            ->orderBy('created_at', 'DESC')
            ->with(['user'])->get();
        return $logs;
    }

    public function logsindex($ym = null, $action = 0) {
        $data['pageTitle'] = __('messages.logs');
        if(!isset($ym)) {
            // 获取当月日志
            $dt = Carbon::now();
            $month = $dt->month < 10 ? '0'.$dt->month:$dt->month;
            $ym = $dt->year.'-'.$month;
        } else {
            $dt = new Carbon($ym);
        }
        $data['nextmonth'] = $dt->addMonth()->format('Y-m');
        $data['prevmonth'] = $dt->subMonth(2)->format('Y-m');
        $data['subTitle'] = $ym;
        $whereData = [
            ['created_at', 'LIKE', "$ym%"]
        ];

        if (Auth::user()->group_id != 1) {
            array_push($whereData, ['type', '!=', 11]);
        }
        if($action) {
            array_push($whereData, ['log_type', $action]);
        }
        $data['logs'] = AdminLog::where($whereData)
        ->orderBy('created_at', 'DESC')
        ->with(['user'])->paginate(20);

        return view('admin.others.logs', $data);
    }

    // save system log for ajax
    public function saveLog(Request $request) {
        $errordata = json_decode($request['config']['data'], true);
        \AdminLog::saveLog($errordata['id'], config('const.log_system_error'), 
                config('const.log_action_add'), $request['data']['message']. ' #data:' .$request['config']['data']);
    }
}
