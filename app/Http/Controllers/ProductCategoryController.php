<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        // Initial parameters
        $sortDirection = $request->get("sortDirection", "DESC");
        $sortBy = $request->get("sortBy", "created_at");
        $paginate = $request->get("paginate", 10);
        $filter = $request->get("filter", null);

        // Preparation
        $columnAliases = [];

        // Begin query
        $model = new ProductCategory();
        $query = ProductCategory::query();

        // Search functionality
        if ($request->get("search") != "") {
            $query = $this->search($request->get("search"), $model, $query);
        }

        // Filter functionality
        if ($filter) {
            $filters = json_decode($filter, true);
            foreach ($filters as $column => $value) {
                $query = $this->filter($this->remark_column($column, $columnAliases), $value, $model, $query);
            }
        }

        // Sort and execute with pagination
        $query = $query->orderBy($this->remark_column($sortBy, $columnAliases), $sortDirection)
            ->paginate($paginate);

        // Check for empty data
        if ($query->isEmpty()) {
            return response([
                "message" => "empty data",
                "data" => [],
            ], 200);
        }

        // Return success response
        return response([
            "message" => "success",
            "data" => $query->items(),
            "total_row" => $query->total(),
        ]);
    }

    public function store(Request $request)
    {
        // Validate request
        $validation = $this->validation($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        if ($validation) return $validation;

        return DB::transaction(function () use ($request) {
            $category = new ProductCategory();
            $category = $this->dump_field($request->all(), $category);

            try {
                $category->save();
            } catch (\Throwable $th) {
                DB::rollBack();
                return response([
                    "message" => "Error: server side having problem!",
                ], 500);
            }

            DB::commit();

            return response([
                "message" => "success",
                "data" => $category
            ], 201);
        });
    }

    public function show($id)
    {
        try {
            $category = ProductCategory::findOrFail($id);
            return response()->json($category, 200);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Failed to retrieve category',
                'details' => $e->getMessage()
            ], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Category not found',
                'details' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        // Validate request
        $validation = $this->validation($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        if ($validation) return $validation;

        return DB::transaction(function () use ($request, $id) {
            $category = ProductCategory::findOrFail($id);
            $category = $this->dump_field($request->all(), $category);

            try {
                $category->save();
            } catch (\Throwable $th) {
                DB::rollBack();
                return response([
                    "message" => "Error: server side having problem!",
                ], 500);
            }

            DB::commit();

            return response([
                "message" => "success",
                "data" => $category
            ], 200);
        });
    }

    public function destroy($id)
    {
        try {
            $category = ProductCategory::findOrFail($id);
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Failed to delete category',
                'details' => $e->getMessage()
            ], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Category not found',
                'details' => $e->getMessage()
            ], 404);
        }
    }
}
