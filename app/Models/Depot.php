<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

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
        'address',
        'is_open'
    ];

    public function getImageAttribute()
    {
        $filePath = 'app/depot/' . $this->phone_number . '.*';
        $glob = File::glob(storage_path($filePath));

        if (count($glob) > 0) {
            return url('img/depot/' . $this->phone_number);
        }

        return null;
    }
}
