<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $limit = $this->getLimit($request);

        $name = $request->query('name');

        $categories = Category::query();
        $categories->when($name, function ($query, $name) {
            $query->where('name', 'LIKE', '%'.$name.'%');
        });

        $categories = $categories->orderBy('name')->paginate($limit);

        return CategoryResource::collection($categories);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function store(CreateCategoryRequest $request)
    {
        try {
            Category::create($request->validated());

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create category',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
        ], Response::HTTP_CREATED);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $category->name = $request->name;
            $category->save();

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update category',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        }

        return response()->json([
            'status' => 'success',
            'message' => 'income category updated successfully',
        ], Response::HTTP_OK);
    }

    public function destroy($ids)
    {
        $ids = explode(',', $ids);

        try {
            Category::whereIn('id', $ids)->delete();
        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'failed to delete category',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        }

        return response()->json([
            'status' => 'success',
            'message' => 'income category deleted successfully',
        ], Response::HTTP_NO_CONTENT);
    }
}
