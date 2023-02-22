<?php

namespace App\Models;

use App\Models\product;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Category extends Model 
{
    use Translatable;

protected $guarded=[];

    public $translatedAttributes = ['name'];
    protected $fillable = ['name'];

    public function products(){
        return $this->hasMany(product::class);
    } 
}

class Childcategory extends Category 
{
    protected $table = 'categories';
    protected $translationForeignKey = 'category_id';
}
