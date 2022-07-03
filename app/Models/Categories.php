<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = [
        'slug',
        'name',
        'description',
        'status',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];
}
