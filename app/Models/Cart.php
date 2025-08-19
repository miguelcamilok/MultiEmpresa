<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model {
    use HasFactory;
    protected $fillable = ['user_id', 'status'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class)->withPivot('quantity')->using(CartProduct::class);
    }

    public function order() {
        return $this->hasOne(Order::class);
    }
}
