<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasPermissionTo('category.list')) {
            try {
                if (request('type') == 'blog') {
                    $categories = Category::where('type', 1)->get();
                }else{
                    $categories = Category::where('type', 0)->get();
                }
    
                return response()->json([
                    'type' => true,
                    'categories' => $categories
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function createCategory(StoreCategoryRequest $request)
    {
        if (Auth::user()->hasPermissionTo('category.create')) {
            try {
                Category::create([
                    'name' => $request->name,
                    'slug' => $request->icon,
                    'type' => $request->type ?: 0
                ]);
    
                return response()->json([
                    'type' => true,
                    'message' => 'Category Created Successfully'
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function getCategory(Category $category)
    {
        if (Auth::user()->hasPermissionTo('category.get')) {
            try {    
                return response()->json([
                    'type' => true,
                    'category' => $category
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function updateCategory(UpdateCategoryRequest $request, Category $category)
    {
        if (Auth::user()->hasPermissionTo('category.update')) {
            try {
                $category->update([
                    'name' => $request->name,
                    'icon' => $request->icon,
                    'type' => $request->type ?: 0
                ]);
    
                return response()->json([
                    'type' => true,
                    'message' => 'Category Updated Successfully'
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if (Auth::user()->hasPermissionTo('category.delete')) {
            try {
                $category->delete();

                return response()->json([
                    'type' => true,
                    'message' => "Category Deleted Successfully"
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
