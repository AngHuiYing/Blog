<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    protected $table = 'post';

    protected $fillable = ['title', 'content'];

    public function images(){
        return $this->hasMany(Image::class);
    }
}
