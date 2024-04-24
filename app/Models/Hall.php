<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hall extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name', 'capacity', 'description', 'location', 'city_id', 'price'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function images()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function event_types()
    {
        return $this->belongsToMany(Event_type::class, 'events', 'hall_id', 'event_type_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
