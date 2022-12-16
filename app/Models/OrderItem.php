<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_items';

    public $timestamps = false;


    protected $fillable = [
        'order_id',
        'order_local_number',
        'product_id',
        'price',
        'preparation_by',
        'status',
    ];


    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function chef(){
        return $this->belongsTo(Chef::class, 'preparation_by');
    }

}
