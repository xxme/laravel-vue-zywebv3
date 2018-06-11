<?php

use Illuminate\Http\Request;
use App\Type;
use App\ProductList;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => 'api'], function() {
    Route::get('get_types',  function() {
        $rs['types'] = Type::where('status', 1)->orderBy('listorder', 'asc')->orderBy('id', 'asc')->get();
        return response()->json($rs);
    });
    Route::get('get_productlist/{event_id}',  function($event_id) {
        $rs['productlists'] = ProductList::where('event_id', null)->orWhere('event_id', $event_id)->orderBy('updated_at', 'desc')->get();
        return response()->json($rs);
    });
    Route::get('get_product/{sku}', function($sku) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt ($ch, CURLOPT_URL, env('SHOP_URL', '').'/sku/'.$sku.'/');
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);

        $infojson = '/<script type="application\/ld\+json">(.*?)<\/script>/i';
        preg_match_all($infojson, $file_contents, $info);
        $temp = array();
        if(isset($info[1][0])) {
            $temp = json_decode($info[1][0], true);
            $image = isset($temp['@graph'][1]['offers'][0]['image']) ? $temp['@graph'][1]['offers'][0]['image'] : $temp['@graph'][1]['image'];
            $imgArray = explode('.', $image);
            $spinfo['title'] = $temp['@graph'][1]['name'];
            $spinfo['price'] = $temp['@graph'][1]['offers'][0]['price'];
            $spinfo['image'] = $imgArray[0].'.'.$imgArray[1].'.'.$imgArray[2].'-348x445.'.$imgArray[3];

            return response()->json($spinfo);
        }
        return null;
    });
});