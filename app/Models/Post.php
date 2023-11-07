<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $guarded = false;
    protected $table = 'posts';

    public function images() {
        return $this->hasMany(Image::class, 'post_id', 'id');
    }

}
