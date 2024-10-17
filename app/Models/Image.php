<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    protected $table = 'image';
    protected $fillable = ['image', 'post_id'];

    public function post(){
        return $this->belongsTo(Post::class);
    }
}
