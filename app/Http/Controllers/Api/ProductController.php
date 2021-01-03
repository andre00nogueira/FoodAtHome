<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\ProductTypeResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('page')) {
            return ProductResource::collection(Product::paginate(10));
        } else {
            return ProductResource::collection(Product::all());
        }
    }

    public function types(){
        return ProductTypeResource::collection(Product::select('type')->distinct('type')->get());
    }

    public function productByType(string $type_name)
    {
        return ProductResource::collection(Product::where('type', $type_name)->get());
    }

    public function getTotalProductsByType(){
        $drink=Product::where('type','drink')->count();
        $dessert=Product::where('type','dessert')->count();
        $hotDish=Product::where('type','hot dish')->count();
        $coldDish=Product::where('type','cold dish')->count();
        return response()->json(['drink'=>$drink,'dessert'=>$dessert,'hot dish'=>$hotDish,'cold dish'=>$coldDish],200);
    }
}
