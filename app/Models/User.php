<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $primaryKey = 'phone_number';
    protected $keyType = 'string';
    protected $table = 'users';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'phone_number',
        'name',
        'uid',
        'device_id',
        'status'
    ];

    public function depot()
    {
        return $this->hasOne(Depot::class, 'phone_number', 'phone_number');
    }
}
