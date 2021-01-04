<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductResource as ProductResource;
use App\Http\Resources\ProductTypeResource;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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

    public function show(Product $product){
        return new ProductResource($product);
    }

    public function store(StoreProductRequest $request){
        $request->validated();
        $product = new Product();
        $product->name=$request->name;
        $product->type=$request->type;
        $product->description=$request->description;
        $product->price=$request->price;
        $generated_new_name = time() . '.' . $request->file('photo_url')->getClientOriginalExtension();
        $request->file('photo_url')->storeAs('public/products', $generated_new_name);
        $product->photo_url=$generated_new_name;
        $product->save();
        return response()->json(new ProductResource($product), 201);
    }

    public function update(UpdateProductRequest $request, Product $product){
        $productOldPhoto = $product->photo_url;
        $product->fill($request->validated());
        
        if($request->hasFile('photo_url')){
            $generated_new_name = time() . '.' . $request->file('photo_url')->getClientOriginalExtension();
            if(!empty($product->photo_url)){
                Storage::delete("public/products/{$productOldPhoto}");
            }
            $request->file('photo_url')->storeAs('public/products', $generated_new_name);
            $product->photo_url=$generated_new_name;
        }
        $product->save();
        return new ProductResource($product);
    }

    public function destroy(Product $product){
        $removedProduct=$product;
        Storage::delete("public/fotos/{$product->photo_url}");
        $product->delete();
        return new ProductResource($removedProduct);
    }
    
    public function getTotalProductsByType(){
        $drink=Product::where('type','drink')->count();
        $dessert=Product::where('type','dessert')->count();
        $hotDish=Product::where('type','hot dish')->count();
        $coldDish=Product::where('type','cold dish')->count();
        return response()->json(['drink'=>$drink,'dessert'=>$dessert,'hot dish'=>$hotDish,'cold dish'=>$coldDish],200);
    }

    public function getTopSoldProducts(){
        $topTenProducts = DB::select('SELECT name, SUM(quantity) as quantity FROM order_items INNER JOIN products ON product_id = products.id GROUP BY product_id ORDER BY SUM(quantity) DESC LIMIT 10');
        return response()->json($topTenProducts);
    }

    public function getQuantitySoldByCategory(){
        $soldByCategory = DB::select('SELECT type, SUM(quantity) as quantity FROM order_items INNER JOIN products ON product_id = products.id GROUP BY type ORDER BY SUM(quantity) DESC');
        return response()->json($soldByCategory);
    }
}
