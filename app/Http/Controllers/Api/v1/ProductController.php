<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'message' => 'Product List',
            'data' => ProductResource::collection($products),
            'status' => true,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $data = $request->all();

        if ($request->has('thumbnail')) {
            $fname = time();
            $fexe = $request->file('thumbnail')->extension();
            $fpath = "$fname.$fexe";
            $request->file('thumbnail')->move(public_path() . '/public/products/thumbnail/', $fpath);

            $data['thumbnail'] = 'products/thumbnail' . $fpath;
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'price' => $request->price,
            'moq' => $request->moq,
            'thumbnail' => $data['thumbnail'],
        ]);

        if ($request->hasFile('product_photopath')) {
            foreach ($request->file('product_photopath') as $image) {
                $fname = time();
                $fexe = $image->extension();
                $fpath = "$fname.$fexe";
                $image->move(public_path() . '/public/products/images/', $fpath);
                ProductImage::create([
                    'product_photopath' => 'products/images' . $fpath,
                    'product_id' => $product->id,
                ]);
            }
        }


        return response()->json([
            'message' => 'Product Created Successfully',
            'data' => new ProductResource($product),
            'status' => true,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {

        return response()->json([
            'message' => 'Product Details',
            'data' => $product,
            'status' => true,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {

        File::delete(public_path() . '/public/' . $product->thumbnail);
        $product_images = ProductImage::where('product_id', $product->id)->get();

        foreach ($product_images as $image) {
            File::delete(public_path() . '/public/' . $image->product_photopath);
        }
        $product->delete();

        return response()->json([
            'message' => 'Product Deleted Successfully',
            'status' => true,
        ], 200);
    }
}
