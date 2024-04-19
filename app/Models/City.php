<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable=['name','hall_id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function halls()
    {
        return $this->hasMany(Hall::class);
    }
    public function images()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
