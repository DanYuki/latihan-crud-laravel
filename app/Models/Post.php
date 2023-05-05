<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Test this thing, does it really work or just a bunch of comments
    /**
     * fillable
     * 
     * @var array 
     */
    // Does this work if I change the var name other than $fillable?
    protected $fillable = [
        'image',
        'title',
        'content'
    ];
}
