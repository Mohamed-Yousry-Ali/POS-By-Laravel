<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class CategoryTranslation extends Model
{
 
    public $timestamps = false;
    protected $fillable = ['name'];
}

class ChildcategoryTranslation extends Model 
{
    protected $table = 'category_translations';
    public $timestamps = false;
    protected $fillable = ['name'];  
}
