<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attedance extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'time_out',
        'latlon_in',
        'latlon_out'
    ];


    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
