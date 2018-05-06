<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductList;
use App\ProductItem;

class ProductListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Return the specified resource.
     *
     * @param  int  $type 1.All 2.Untreated 3.Treated
     * @return \Illuminate\Http\Response
     */
    public function getlists($type) {
        if($type == 1) {
            return ProductList::with(['user', 'items', 'event'])->orderBy('updated_at', 'DESC')->paginate(20);
        } else if($type == 2) {
            return ProductList::with(['user', 'items'])->where('event_id', null)->orderBy('updated_at', 'DESC')->paginate(20);
        } else {
            return ProductList::with(['user', 'items', 'event'])->where('event_id', '!=', null)->orderBy('updated_at', 'DESC')->paginate(20);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.dashboard');
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
        $objList = new ProductList();
        $objList->cost = $inputs['cost'];
        $objList->price = $inputs['totalprice'];
        $objList->note = $inputs['note'];
        $objList->user_id = auth()->user()->id;
        $product_list_id = \AdminLog::saveWithLog($objList, config('const.log_shopping_list'), config('const.log_action_add'));
        if($product_list_id) {
            foreach($inputs['items'] as $item) {
                $objItem = new ProductItem();
                $objItem->product_list_id = $product_list_id;
                if($item['sku']) {
                    $objItem->sku = $item['sku'];
                }
                $objItem->title = $item['title'];
                $objItem->quantity = $item['quantity'];
                $objItem->price = $item['price'];
                $objItem->image = $item['image'];
                $objItem->save();
            }
        }
        
        return $objList;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ProductList::with(['items'])->findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.dashboard');
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
        $inputs = $request->all();
        $objList = ProductList::findOrFail($id);
        $objList->cost = $inputs['cost'];
        $objList->price = $inputs['totalprice'];
        $objList->note = $inputs['note'];
        $product_list_id = \AdminLog::saveWithLog($objList, config('const.log_shopping_list'), config('const.log_action_update'));
        if($product_list_id) {
            ProductItem::where('product_list_id', $product_list_id)->delete();
            foreach($inputs['items'] as $item) {
                $objItem = new ProductItem();
                $objItem->product_list_id = $product_list_id;
                if($item['sku']) {
                    $objItem->sku = $item['sku'];
                }
                $objItem->title = $item['title'];
                $objItem->quantity = $item['quantity'];
                $objItem->price = $item['price'];
                $objItem->image = $item['image'];
                $objItem->save();
            }
        }
        
        return $objList;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = ProductList::findOrFail($id);
        \AdminLog::saveLog($id, config('const.log_shopping_list'), config('const.log_action_del'), __('messages.totalprice').' ￥'.number_format($obj->price));
        return ProductList::destroy($id);
    }
}
