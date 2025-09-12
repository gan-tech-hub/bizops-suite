<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start',
        'end',
        'customer_id',
        'location',
        'description',
        'staff',
        'customer',
        'color',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];
    
    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
