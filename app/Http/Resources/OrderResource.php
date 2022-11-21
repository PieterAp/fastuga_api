<?php

namespace App\Http\Resources;

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
        switch(OrderResource::$format){
            case 'detailed':
                return parent::toArray($request);
            default:
                return [
                    'id' => $this->id,
                    'status' => $this->status,
                    'total_price'=> $this->total_price,
                    'payment_type'=> $this->payment_type,
                    'date'=> $this->date,
                ];    
        }  
    }
}
