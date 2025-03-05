<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * List all Categories
     */
    public function index()
    {
        $query = Category::query();
        
        /**
         * Set sorting and filter queries using helper function.
         */
        setQueryBuilder($query);

        return response()->json([
            'message' => 'Categories retrieved successfully',
            'data'    => applyPaginate($query)
        ], 200);
    }

    /**
     * Create Category
     */
    public function create(): JsonResponse
    {
        $validate = Validator::make(request()->all(), [
            'name'        => 'required|max:50',
            'status'      => 'required|boolean',
            'image'       => 'nullable|file|mimes:jpeg,png,jpg',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $validate->errors(),
            ], 422);
        }

        $category = new Category();
        $category->name = request('name');
        $category->status = request('status');
        $category->description = request('description');

        if (request()->hasFile('image')) {
            $category->image_path = request()->file('image')->store('category/' . $category->id);
        }

        $category->save();

        return response()->json([
            'message'  => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    /**
     * Update Category
     */
    public function update($id): JsonResponse
    {
        $validate = Validator::make(request()->all(), [
            'name'        => 'nullable|max:50',
            'status'      => 'required|boolean',
            'image'       => 'nullable|file|mimes:jpeg,png,jpg',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $validate->errors(),
            ], 422);
        }

        $category = Category::findOrFail($id);

        if (request()->has('name')) {
            $category->name = request('name');
        }
        
        if (request()->has('status')) {
            $category->status = request('status');
        }

        if (request()->has('description')) {
            $category->description = request('description');
        }

        if (request()->hasFile('image')) {
            Storage::delete($category->image_path);

            $category->image_path = request()->file('image')->store('category/' . $category->id);
        }

        $category->save();

        return response()->json([
            'message'  => 'Category updated successfully',
            'category' => $category
        ], 200);
    }

    /**
     * Delete Category
     */
    public function destroy($id): JsonResponse
    {
        $category = Category::findOrFail($id);

        if ($category->image_path) {
            Storage::delete($category->image_path);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ], 200);
    }
}
