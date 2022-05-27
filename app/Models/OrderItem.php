<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    public function product()
    {
        return $this->belongsTo(Product::class);

    }
}
