<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * class TaskController extends Controller
     */
    public function index()
    {
        return ProductResource::collection(Product::withTrashed()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->photo) {
            $upload_path = public_path('storage/products');
            $generated_new_name = time() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move($upload_path, $generated_new_name);
            $data = $request->all();
            $product = new Product();
            $product['photo_url'] = $generated_new_name;
        } else {
            $data = $request->all();
            $product = new Product();
        }

        $product->fill($data);
        $product->save();
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $productF = Product::withTrashed()->find($product);
        return new ProductResource($productF);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        if ($request->photo) {
            $upload_path = public_path('storage/products');
            $generated_new_name = time() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move($upload_path, $generated_new_name);
            $product->fill($request->all());
            $product['photo_url'] = $generated_new_name;
        } else {
            $product->fill($request->all());
        }
        $product->save();
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return new ProductResource($product);
    }
}
