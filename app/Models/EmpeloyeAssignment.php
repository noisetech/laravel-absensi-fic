<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpeloyeAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'departemen_id',
        'position_id',
        'start_date',
        'end_date',
        'status'
    ];


    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public function departemen()
    {
        return $this->belongsTo(Department::class, 'departemen_id', 'id');
    }
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }
}
