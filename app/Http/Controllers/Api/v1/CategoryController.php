<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'data' => $categories,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->all();

        if ($data['category_photo'] != '') {
            if ($request->has('category_photo')) {
                $fname = time();
                $fexe = $request->file('category_photo')->extension();
                $fpath = "$fname.$fexe";

                $request->file('category_photo')->move(public_path() . '/public/categories/', $fpath);

                $data['category_photo'] = 'categories/' . $fpath;
            }
        }

        $category = Category::create($data);

        return response()->json([
            'data' => $category,
            'message' => 'Category created successfully',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return response()->json([
            'data' => $category,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->all();

        if ($data['category_photo'] != '') {
            if ($request->has('category_photo')) {
                $fname = time();
                $fexe = $request->file('category_photo')->extension();
                $fpath = "$fname.$fexe";

                $request->file('category_photo')->move(public_path() . '/public/categories/', $fpath);

                $data['category_photo'] = 'categories/' . $fpath;
            }
        }

        $category->update($data);

        return response()->json([
            'data' => $category,
            'message' => 'Category updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully',
        ], 200);
    }
}
