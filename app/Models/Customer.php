<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'staff_id',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

}

