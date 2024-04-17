<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $table = 'images';
    protected $fillable = ['path'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function imageable()
    {
        return $this->morphTo();
    }
    // public function eventType()
    // {
    //     return $this->hasOne(Event_Type::class);
    // }
}
   


