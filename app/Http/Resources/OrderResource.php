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
            default:
                $data = [
                    'id' => $this->id,
                    'status' => $this->status,
                    'date' => $this->date,
                    'customer_id' => $this->customer_id,
                    'ticket_number' => $this->ticket_number,
                    'payment_type'=> $this->payment_type,
                    'payment_reference' => $this->payment_reference,
                    'delivered_by' => $this->delivered_by,
                    'created_at' => $this->created_at,
                ];

                return $data;
        }
    }
}
