<?php

namespace App\Models;
use Astrotomic\Translatable\Translatable;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class product extends Model
{
    use Translatable;

    protected $guarded=[];
    
        public $translatedAttributes = ['name','description'];
        protected $appends = ['image_path','profit_percent'];
        public function getImagePathAttribute(){
            return asset('uploads/product_images/' . $this->image);
        }
    
        public function category(){
            return $this->belongsTo(Category::class);
        }

        public function orders(){
            return $this->belongsToMany(Order::class,'product_order');
        }

        public function getProfitPercentAttribute(){
            $profit = $this->sale_price - $this->purchase_price ;
            $profit_percent = $profit * 100 / $this->purchase_price  ;
            return number_format($profit_percent,2) ;
        }


}


// class Childcategory extends Category 
// {
//     protected $table = 'categories';
//     protected $translationForeignKey = 'category_id';
// }