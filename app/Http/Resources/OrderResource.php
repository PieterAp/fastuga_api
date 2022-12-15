<?php

namespace App\Http\Resources;

use App\Models\Customer;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public static $format = "default";
    public function toArray($request)
    {

        switch (OrderResource::$format) {
            case 'detailed':
                return parent::toArray($request);
            
            case 'driver':
                $data = [
                    'customer_id' => $this->customer_id,
                    'order_id' => $this->id,
                    'delivery_time' => $this->delivery_time,
                ];
                return $data;
                
            default:
                $data = [
                    'id' => $this->id,
                    'status' => $this->status,
                    'date' => $this->date,
                    'customer_name' => $this->customer_name,
                    'ticket_number' => $this->ticket_number,
                    'delivered_by' => $this->delivered_by,
                    'created_at' => $this->created_at,
                ];

                return $data;
        }
    }
}
