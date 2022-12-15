<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'order_local_number' => $this->order_local_number,
            'product_id' => $this->product_id,
            'price' => $this->price,
            'status' => $this->status,
            'preparation_by' => $this->preparation_by,
            'notes' => $this->notes,
        ];

        return $data;   
    }
}
