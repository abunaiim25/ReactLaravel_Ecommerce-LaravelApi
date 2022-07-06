<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = [
        'category_id',
        'slug',
        'name',
        'description',

        'meta_title',
        'meta_keywords',
        'meta_description',

        'selling_price',
        'orginal_price',
        'quantity',
        'image',
        'brand',
        'featured',
        'popular',
        'status',
    ];

    //==============Join foreign key=================
    protected $with = ['category'];
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id'); //joined with category id
    }

    /*//or
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }
    */
}
