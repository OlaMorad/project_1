<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'capacity', 'descrebtion'];

    public function images()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function event_types()
    {
        return $this->belongsToMany(Event_Type::class, 'events');
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
