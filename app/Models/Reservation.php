<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    // ここを追加
    protected $fillable = [
        'title',
        'start',
        'end',
    ];
    
    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
