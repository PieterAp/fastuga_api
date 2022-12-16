<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderItemResource;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderItemController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * class TaskController extends Controller
     */
    public function index()
    {
        return OrderItemResource::collection(OrderItem::all());
    }

    public function chefIndex()
    {
        $items = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('users', 'order_items.preparation_by', '=', 'users.id')
            ->where('products.type','=','hot dish')
            ->where('orders.status','!=','D')
            ->where('orders.status','!=','C')
            ->select('order_items.*','products.name','products.photo_url','orders.ticket_number','orders.created_at','users.name as userName')
            ->get();

        return OrderItemResource::collection($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $orderItem = new OrderItem();
        $orderItem->fill($data);
        $orderItem->save();
        return new OrderItemResource($orderItem);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OrderItem $ordersItem)
    {
        return new OrderItemResource($ordersItem);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,OrderItem $ordersItem)
    {
        $ordersItem->fill($request->all());
        $ordersItem->save();
        return new OrderItemResource($ordersItem);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderItem $ordersItem)
    {
        $ordersItem->delete();
        return new OrderItemResource($ordersItem);
    }
}