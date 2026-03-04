<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'maker_id','name','description','type','material',
        'production_time','complexity','durability','unique_features',
        'verified','flagged','deleted_at',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'flagged' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function maker()
    {
        return $this->belongsTo(User::class, 'maker_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function moderationFlags()
    {
        return $this->hasMany(ModerationFlag::class);
    }
}
