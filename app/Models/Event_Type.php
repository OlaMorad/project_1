<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_Type extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    // public function image()
    // {
    //     return $this->belongsTo(Image::class);
    // }
    public function images()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function halls()
    {
        return $this->belongsToMany(Hall::class, 'events', 'event_type_id', 'hall_id');
    }
}
