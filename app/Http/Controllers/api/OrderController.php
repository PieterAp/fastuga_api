<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Driver;
use Exception;
use Illuminate\Http\Request;

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
        //OrderResource::$format = 'detailed';
        return OrderResource::collection(Order::all());
    }

    public function indexDelivery()
    {
        //OrderResource::$format = 'detailed';
        $readyToDeliveryOrders = Order::whereNull('delivered_by')->where('status','!=','D')->get();
        return OrderResource::collection($readyToDeliveryOrders);
    }

    public function ordersByDriver(Request $request)
    {
        OrderResource::$format = 'driver';
        $user = $request->user();
        $driver = Driver::where('user_id', $user->id)->first();
        //$order = $request->order();
        $order = Order::where('delivered_by', $driver->user_id)->get();
        return OrderResource::collection($order);
    }


    public function confirmOrder(Request $request, Order $order){
        $user = $request->user();
        $order->fill($request->all());
        $order->save();
        $driver = Driver::where('user_id', $user->id)->first();
        $driver['balance']= $driver['balance']+$request->balance;
        $driver->save();
        return new OrderResource($order);
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
        //calculate delivery_distance
        $order = new Order();
        $order->fill($data);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //update distance if necessary
        if ($order['pickup_address'] != $request->pickup_address && $request->pickup_address != null || $order['delivery_address'] != $request->delivery_address && $request->delivery_address != null) {
            $client = new \GuzzleHttp\Client();
            try {
                $result = $client->get("http://api.positionstack.com/v1/forward?access_key=d8cc239cc1f09552d7d37f11000ce9d2&query={$request->pickup_address}&output=json&limit=1");

                $startLocation = json_decode($result->getBody(), true);

                $startLat = $startLocation['data'][0]['latitude'];
                $startLng = $startLocation['data'][0]['longitude'];

                $result = $client->get("http://api.positionstack.com/v1/forward?access_key=d8cc239cc1f09552d7d37f11000ce9d2&query={$request->delivery_address}&output=json&limit=1");

                $endLocation = json_decode($result->getBody(), true);

                $endLat = $endLocation['data'][0]['latitude'];
                $endLng = $endLocation['data'][0]['longitude'];

                $result = $client->get("https://graphhopper.com/api/1/matrix?point={$startLat},{$startLng}&point={$endLat},{$endLng}&out_array=distances&key=4790c76f-21e8-4781-8057-d26bc5cc655d");

                $routeDistance = json_decode($result->getBody(), true);

                $request['delivery_distance'] = $routeDistance['distances'][0][1] / 1000;
            } catch (Exception $e) {
                $request['delivery_distance'] = null;
                $order->fill($request->all());
                $order->save();
                return new OrderResource($order);
            }
        }

        if($order['delivered_by']==null){
            if ($request->delivered_by != "null") {
                $user = $request->user();
                $order['delivered_by'] = $user['id'];
            }else{
                $order['delivered_by']=null;
            }
        }else if($request->delivered_by!=null){
            $order->fill($request->all());
            $order['delivered_by']=null;
            return response()->json(['error' => 'Someone was faster than you and already took this order'], 409);
        }else{
            $order->fill($request->all());
        }     
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
