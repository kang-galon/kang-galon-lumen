<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    protected $table = 'chats';

    protected $fillable = [
        'sender',
        'to',
        'transaction_id',
        'message'
    ];
}
