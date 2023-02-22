<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::all();
        $products = product::when($request->search ,function($q) use($request){
            return $q->whereTranslationLike('name','%' . $request->search . '%');
        })->when($request->category_id , function($q) use($request){
            return $q->where('category_id' , $request->category_id);
        })->latest()->paginate(5);
        return view('dashboard.products.index',compact('products','categories'));
    }


    public function create()
    {
        $categories = Category::all();
        return view('dashboard.products.create',compact('categories'));
    }


    public function store(Request $request)
    {
       
        $rules = [
            'category_id' => 'required'
        ];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => 'required|unique:producttranslations,name'];
            $rules += [$locale . '.description' => 'required'];

        }//end of  for each

        $rules += [
            'purchase_price' => 'required',
            'sale_price' => 'required',
            'stock' => 'required',
        ];

        $request->validate($rules);

        $request_data = $request->all();

        if ($request->image) {

            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/product_images/' . $request->image->hashName()));

            $request_data['image'] = $request->image->hashName();

        }//end of if

        product::create($request_data);
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.products.index');
    }

    public function edit(product $product)
    {
        $categories = Category::all();
        return view('dashboard.products.edit',compact('product','categories'));
    }

    public function update(Request $request, product $product)
    {
        $rules = [
            'category_id' => 'required'
        ];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required', Rule::unique('producttranslations', 'name')->ignore($product->id, 'product_id')]];
            $rules += [$locale . '.description' => 'required'];

        }//end of  for each

        $rules += [
            'purchase_price' => 'required',
            'sale_price' => 'required',
            'stock' => 'required',
        ];

        $request->validate($rules);

        $request_data = $request->all();

        if ($request->image) {

            if ($product->image != 'default.png') {

                Storage::disk('public_uploads')->delete('/product_images/' . $product->image);
                    
            }//end of if

            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/product_images/' . $request->image->hashName()));

            $request_data['image'] = $request->image->hashName();

        }//end of if
        
        $product->update($request_data);
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.products.index');
    }

    public function destroy(product $product)
    {
        if ($product->image != 'default.png') {
            Storage::disk('public_uploads')->delete('/product_images/' . $product->image);
        }
        $product->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.products.index');
    }
}
