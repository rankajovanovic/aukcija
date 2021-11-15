<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'payment',
        'delivery',
        'image',
        'user_id',
        'end_time',
        'buyer_id',
        'buy_price',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buyer() 
    {
        return $this->hasOne(User::class, 'buyer_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
}
