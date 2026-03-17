<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public $timestamps = false; // created_at only

    protected $fillable = ['order_id','rating','comment','created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // ✅ context helpers voor moderatie (via order)
    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            Order::class,
            'id',         // Foreign key on orders...
            'id',         // Foreign key on products...
            'order_id',   // Local key on reviews...
            'product_id'  // Local key on orders...
        );
    }

    public function buyer()
    {
        return $this->hasOneThrough(
            User::class,
            Order::class,
            'id',        // Foreign key on orders...
            'id',        // Foreign key on users...
            'order_id',  // Local key on reviews...
            'buyer_id'   // Local key on orders...
        );
    }
}
