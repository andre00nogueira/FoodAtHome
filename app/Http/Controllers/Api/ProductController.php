<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\Product as ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('page')) {
            return ProductResource::collection(Product::paginate(5));
        } else {
            return ProductResource::collection(Product::all());
        }
    }
}
