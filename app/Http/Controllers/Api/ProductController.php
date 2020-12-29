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

    public function store(StoreProductRequest $request){
        $request->validated();
        $product = new Product();
        $product->name=$request->name;
        $product->type=$request->type;
        $product->description=$request->description;
        $product->price=$request->price;
        if($request->has('photo_url')){
            /*
            $upload_path = public_path('foto');
            $file_name = $request->photo_url->getClientOriginalName();
            $generated_new_name = time() . '.' . $request->photo_url->getClientOriginalExtension();
            $request->file->move($upload_path, $generated_new_name);
            $user->photo_url=$file_name;*/
        }
        $product->save();
        return response()->json(new ProductResource($product), 201);
    }

    public function update(UpdateProductRequest $request, Product $product){
        $product->update($request->validated());
        return new ProductResource($product);
    }

    public function destroy(Product $product){
        $removedProduct=$product;
        $product->delete();
        return new ProductResource($removedProduct);
    }
}
