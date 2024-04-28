<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=['name'];
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
