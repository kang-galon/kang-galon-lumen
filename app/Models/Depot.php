<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'phone_number';
    protected $keyType = 'string';
    protected $table = 'depots';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'phone_number',
        'location',
        'image',
        'address',
        'is_open'
    ];

    public function getRatingAttribute()
    {
        $rating = 0;
        $transactions = Transaction::where('depot_phone_number', $this->phone_number)->get();
        foreach ($transactions as $transaction) {
            if ($transaction->rating > 0) {
                $rating += $transaction->rating;
            }
        }

        // 10/10 rating
        $rating = $rating == 0 ? $rating : $rating / $transactions->count();

        // 5/5 rating
        return $rating / 2;
    }

    public function user()
    {
        return $this->hasOne(User::class, 'phone_number', 'phone_number');
    }
}
