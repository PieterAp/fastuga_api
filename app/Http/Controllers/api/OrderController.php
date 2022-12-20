<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
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
        OrderResource::$format = 'detailed';
        return OrderResource::collection(Order::all());
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
        $order = new Order();
        $order->fill($data);
        $latestOrderTicketNumber = Order::orderBy('id','DESC')->first()->ticket_number ;   
        if($latestOrderTicketNumber=='99'){
            $order['ticket_number'] = 1;
        }else{
            $order['ticket_number'] = $latestOrderTicketNumber+1;
        }
        $order->save();
        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    public function orderItems(Order $order)
    {

        $items = DB::table('order_items')              
        ->join('orders', 'order_items.order_id', '=', 'orders.id')  
        ->join('products', 'order_items.product_id', '=', 'products.id')  
        ->leftJoin('users', 'order_items.preparation_by', '=', 'users.id')
        ->where('order_items.order_id', '=', $order->id)       
        ->where('orders.status', '!=', 'R')      
        ->select('order_items.*', 'products.name', 'products.type','products.photo_url', 'orders.ticket_number', 'orders.created_at', 'users.name as userName')
        ->get();

        return OrderItemResource::collection($items);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $order->fill($request->all() ); 
        $order->save();
        return new OrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return new OrderResource($order);
    }
}
