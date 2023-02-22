@extends('layouts.dashboard.app')


@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>@lang('site.products')</h1>

        <ol class="breadcrumb">
            <li ><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a> </li>
            <li><a href="{{ route('dashboard.products.index') }}"> @lang('site.products')</a></li>
            <li class="active">@lang('site.edit')</li>
        </ol>
    </section>
    <section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('site.edit')</h3>
        </div>

        <div class="box-body">
            @include('partials._errors')
          <form action="{{route('dashboard.products.update',$product->id)}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
            {{method_field('put')}}

            <div class="form-group">
            <label>@lang('site.categories')</label>
            <select name="category_id" class="form-control">
                <option value="">@lang('site.all_categories')</option>
                @foreach ($categories as $category)
                <option value="{{$category->id}}" {{$product->category_id == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                @endforeach
            </select>
            </div>

            @foreach (config('translatable.locales') as $locale)
                            <div class="form-group">
                                <label>@lang('site.' . $locale . '.name')</label>
                                <input type="text" name="{{ $locale }}[name]" class="form-control" value="{{ $product->translate($locale)->name }}">
                            </div>

                            <div class="form-group">
                                <label>@lang('site.' . $locale . '.description')</label>
                                <textarea type="text" name="{{ $locale }}[description]" class="form-control ckeditor">{{ $product->translate($locale)->description }}</textarea>
                            </div>
             @endforeach

             <div class="form-group">
                <label for="">@lang('site.image')</label>
                <input type="file" name="image" class="form-control image">
            </div>

            <div class="form-group">
                <img src="{{$product->image_path}}" style="width: 100px;" class="img-thumbnail image-preview" alt="">
            </div>

            <div class="form-group">
                <label for="">@lang('site.purchase_price')</label>
                <input type="number" name="purchase_price" step="0.01" class="form-control " value="{{$product->purchase_price}}" required >
            </div>

            <div class="form-group">
                <label for="">@lang('site.sale_price')</label>
                <input type="number" name="sale_price" step="0.01" class="form-control " value="{{$product->sale_price}}" required >
            </div>

            <div class="form-group">
                <label for="">@lang('site.stock')</label>
                <input type="number" name="stock" step="0.01" class="form-control " value="{{$product->stock}}" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.edit')</button>    
            </div>
          </form>
        </div>
    </div>
    </section>
</div>

@endsection