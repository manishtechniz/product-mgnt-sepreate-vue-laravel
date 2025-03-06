<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * List all Categories
     */
    public function index(): JsonResponse
    {
        $query = Product::query();
        
        /**
         * Set sorting and filter queries using helper function.
         */
        setQueryBuilder($query);

        return response()->json([
            'message' => 'Product retrieved successfully',
            'data'    => applyPaginate($query)
        ], 200);
    }

    /**
     * Create Product
     */
    public function store(): JsonResponse
    {
        $validate = Validator::make(request()->all(), [
            'name'        => 'required|max:50',
            'description' => 'nullable|string|max:500',
            'price'       => 'required|numeric',
            'discount'    => 'nullable|numeric',
            'stock'       => 'required|integer',
            'status'      => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $validate->errors(),
            ], 422);
        }

        $product = new Product();
        $product->name = request('name');
        $product->description = request('description');
        $product->price = request('price');
        $product->discount = request('discount');
        $product->stock = request('stock');
        $product->status = request('status');
        $product->category_id = request('category_id');
        $product->save();

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    /**
     * Update Product
     */
    public function update($id): JsonResponse
    {
        $validate = Validator::make(request()->all(), [
            'name'        => 'sometimes|required|max:50',
            'description' => 'nullable|string|max:500',
            'price'       => 'sometimes|required|numeric',
            'discount'    => 'nullable|numeric',
            'stock'       => 'sometimes|required|integer',
            'status'      => 'sometimes|required|boolean',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $validate->errors(),
            ], 422);
        }

        $product = Product::findOrFail($id);

        if (request()->has('name')) {
            $product->name = request('name');
        }

        if (request()->has('description')) {
            $product->description = request('description');
        }

        if (request()->has('price')) {
            $product->price = request('price');
        }

        if (request()->has('discount')) {
            $product->discount = request('discount');
        }

        if (request()->has('stock')) {
            $product->stock = request('stock');
        }

        if (request()->has('status')) {
            $product->status = request('status');
        }

        if (request()->has('category_id')) {
            $product->category_id = request('category_id');
        }

        $product->save();

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ], 200);
    }

    /**
     * Delete Product
     */
    public function destroy($id): JsonResponse
    {
        $product = Product::findOrFail($id);

        /**
         * Delete product images before product.
         */
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::delete($image);
            }
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ], 200);
    }
}