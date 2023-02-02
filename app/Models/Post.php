<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'description',
        'tag',
        'illness',
        'subject',
        'filename',
        'filepath',
        'mime_type'
    ];

    protected $casts = [
        'tag' => 'array'
    ];
}
