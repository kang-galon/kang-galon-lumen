<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'client_phone_number',
        'depot_phone_number',
        'client_location',
        'status',
        'total_price',
        'gallon',
        'rating'
    ];

    public function depot()
    {
        return $this->hasOne(Depot::class, 'phone_number', 'depot_phone_number');
    }
}
