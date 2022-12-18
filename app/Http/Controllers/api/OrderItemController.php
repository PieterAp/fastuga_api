<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderItemResource;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
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
            ->leftJoin('users', 'order_items.preparation_by', '=', 'users.id')
            ->where('products.type', '=', 'hot dish')
            ->where('orders.status', '!=', 'D')
            ->where('orders.status', '!=', 'C')
            ->select('order_items.*', 'products.name', 'products.type','products.photo_url', 'orders.ticket_number', 'orders.created_at', 'users.name as userName')
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

        $product = Product::where('id', '=', $request->product_id)->first();
        $order = Order::where('id', '=', $request->order_id)->first();

        if ($product->type == 'hot dish') {
            $orderItem['status'] = 'W';
        } else {
            $orderItem['status'] = 'R';
        }

        $orderItem->save();
        $orderItem['photo_url'] = $product->photo_url;
        $orderItem['type'] = $product->type;
        $orderItem['name'] = $product->name;
        $orderItem['created_at'] = $order->created_at;
        $orderItem['ticket_number'] = $order->ticket_number;
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
    public function update(Request $request, OrderItem $ordersItem)
    {
        $ordersItem->fill($request->all());
        $ordersItem->save();
        $ordersItem['userName'] = User::where('id', '=', $ordersItem->preparation_by)->first()->name;
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
