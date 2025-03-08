<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'recipient_id',
        'buyer_name',
        'buyer_email',
        'phone_number',
        'note',
        'buyer_id',
        'attachment',
        'ip_address'
    ];
    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
