<?php

// app/Http/Controllers/ProductPriceController.php
namespace App\Http\Controllers;

use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductPriceController extends Controller
{
    public function index(Request $request)
    {
        $sortDirection = $request->get("sortDirection", "DESC");
        $sortby = $request->get("sortBy", "created_at");
        $paginate = $request->get("paginate", 10);
        $filter = $request->get("filter", null);

        $columnAliases = [];
        $model = new ProductPrice();
        $query = ProductPrice::query();

        if ($request->get("search") != "") {
            $query = $this->search($request->get("search"), $model, $query);
        } else {
            $query = $query;
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
            'price_type' => 'required|string|max:255',
            'price' => 'required|integer'
        ]);

        if ($validation) return $validation;

        DB::beginTransaction();
        $model = new ProductPrice();
        $model = $this->dump_field($request->all(), $model);

        try {
            $model->save();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                "message" => "Error: server side having problem!",
            ], 500);
        }

        DB::commit();

        return response([
            "message" => "success",
            "data" => $model
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validation = $this->validation($request->all(), [
            'product_id' => 'required|exists:products,product_id',
            'price_type' => 'required|string|max:255',
            'price' => 'required|integer'
        ]);

        if ($validation) return $validation;

        $model = ProductPrice::findOrFail($id);
        $model = $this->dump_field($request->all(), $model);

        DB::beginTransaction();
        try {
            $model->save();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                "message" => "Error: server side having problem!",
            ], 500);
        }

        DB::commit();

        return response([
            "message" => "success",
            "data" => $model
        ], 200);
    }

    public function destroy($id)
    {
        $model = ProductPrice::findOrFail($id);

        DB::beginTransaction();
        try {
            $model->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                "message" => "Error: server side having problem!",
            ], 500);
        }

        DB::commit();

        return response([
            "message" => "success",
            "data" => $model
        ], 200);
    }
}

