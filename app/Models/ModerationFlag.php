<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModerationFlag extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id','flagged_by_user_id','reason','automatic','created_at',
    ];

    protected $casts = [
        'automatic' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function flaggedBy()
    {
        return $this->belongsTo(User::class, 'flagged_by_user_id');
    }
}
