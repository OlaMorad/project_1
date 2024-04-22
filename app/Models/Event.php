<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = ['hall_id','event_type_id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

}
