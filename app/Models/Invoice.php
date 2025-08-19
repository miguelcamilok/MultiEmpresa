<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Invoice extends Model {
    
    use HasFactory;
    protected $fillable = ['order_id', 'user_id', 'invoice_number', 'invoice_date', 'payment_method', 'total'];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(InvoiceItem::class);
    }
}
