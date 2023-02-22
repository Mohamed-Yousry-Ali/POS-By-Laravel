<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producttranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name','description'];
}
// class ChildcategoryTranslation extends Model 
// {
//     protected $table = 'category_translations';
//     public $timestamps = false;
//     protected $fillable = ['name'];  
// }