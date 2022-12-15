<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    protected $fillable = [
        'ticket_number',
        'total_price',
        'total_paid_with_points',
        'points_gained',
        'points_used_to_pay',
        'payment_type',
        'payment_reference',
        'date',
        'total_paid',
        'customer_id',
        'status',
        //
        'delivery_address',
        'pickup_address',
        'delivery_distance',
        'delivery_time',
        'delivered_by',
        'created_at'
    ];

    public function assignedProducts(){
        return $this->belongsToMany(Product::class, 'order_items');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function userDelivery(){
        return $this->belongsTo(User::class, 'delivery_id');
    }


}
