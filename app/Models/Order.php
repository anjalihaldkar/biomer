<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id', 'order_number', 'name', 'phone', 'email',
        'address', 'city', 'state', 'pincode', 'notes',
        'total_amount', 'status',
        'razorpay_order_id', 'razorpay_payment_id', 'payment_status',
        'payment_gateway', 'cashfree_order_id', 'cashfree_payment_id',
        'shiprocket_order_id',


    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class , 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
