<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'phone_number';
    protected $keyType = 'string';
    protected $table = 'depots';

    protected $fillable = [
        'phone_number',
        'location',
        'address',
        'is_open'
    ];
}
