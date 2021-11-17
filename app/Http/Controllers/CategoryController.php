<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::all();
        return $this->apiResponse(200, 'Category list.', ['data' => $categories]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'   => ['required', 'string', 'unique:categories,name'],
            'status' => ['nullable', 'boolean']
        ]);
        Category::create($validated);
        return $this->apiResponse(201, 'Category created.');
    }

    public function show(Category $category): JsonResponse
    {
        return $this->apiResponse(200, 'Category show.', ['data' => $category]);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name'   => ['required', 'string', 'unique:categories,name,' . $category->id],
            'status' => ['nullable', 'boolean']
        ]);
        $category->update($validated);
        return $this->apiResponse(201, 'Category updated.');
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return $this->apiResponse(200, 'Category deleted.');
    }
}
