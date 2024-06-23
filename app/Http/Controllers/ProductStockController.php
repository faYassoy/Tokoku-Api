<?php

// app/Http/Controllers/ProductStockController.php
namespace App\Http\Controllers;

use App\Models\ProductStock;
use App\Models\ProductStockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductStockController extends Controller
{
    /**
     * Display a listing of the product stocks.
     */
    public function index(Request $request)
    {
        $sortDirection = $request->get("sortDirection", "DESC");
        $sortby = $request->get("sortBy", "created_at");
        $paginate = $request->get("paginate", 10);
        $filter = $request->get("filter", null);

        $columnAliases = [];

        $model = new ProductStock();
        $query = ProductStock::query();

        if ($request->get("search") != "") {
            $query = $this->search($request->get("search"), $model, $query);
        }

        if ($filter) {
            $filters = json_decode($filter);
            foreach ($filters as $column => $value) {
                $query = $this->filter($this->remark_column($column, $columnAliases), $value, $model, $query);
            }
        }

        $query = $query->orderBy($this->remark_column($sortby, $columnAliases), $sortDirection)
            ->select($model->selectable)->paginate($paginate);

        if (empty($query->items())) {
            return response([
                "message" => "empty data",
                "data" => [],
            ], 200);
        }

        return response([
            "message" => "success",
            "data" => $query->all(),
            "total_row" => $query->total(),
        ]);
    }

    public function store(Request $request)
    {
        $validation = $this->validation($request->all(), [
            'product_id' => 'required|exists:products,product_id',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        if ($validation) return $validation;

        DB::beginTransaction();
        try {
            $productStock = new ProductStock();
            $productStock = $this->dump_field($request->all(), $productStock);
            $productStock->previous_stock_quantity = 0; // Initial stock has no previous quantity
            $productStock->save();

            DB::commit();

            return response([
                "message" => "success",
                "data" => $productStock
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                "message" => "Error: server side having problem!",
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validation = $this->validation($request->all(), [
            'stock_quantity' => 'required|integer|min:0',
        ]);

        if ($validation) return $validation;

        DB::beginTransaction();
        try {
            $productStock = ProductStock::findOrFail($id);
            $productStock->previous_stock_quantity = $productStock->stock_quantity; // Save previous stock quantity
            $productStock = $this->dump_field($request->all(), $productStock);
            $productStock->save();

            DB::commit();

            return response([
                "message" => "success",
                "data" => $productStock
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                "message" => "Error: server side having problem!",
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $productStock = ProductStock::findOrFail($id);
            $productStock->delete();

            DB::commit();

            return response([
                "message" => "success"
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                "message" => "Error: server side having problem!",
            ], 500);
        }
    }
}

