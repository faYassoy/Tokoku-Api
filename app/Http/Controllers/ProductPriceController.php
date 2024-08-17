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
            ->select('*')->paginate($paginate);

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
            'product_id' => 'required|exists:products,id',
            'price_type' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
        ]);

        if ($validation) return $validation;

        DB::beginTransaction();
        try {
            $productPrice = new ProductPrice();
            $productPrice = $this->dump_field($request->all(), $productPrice);

            $productPrice->save();

            DB::commit();

            return response([
                "message" => "success",
                "data" => $productPrice
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                "message" => "Error: server side having problem!",
                "th" => $th,
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validation = $this->validation($request->all(), [
            'price_type' => 'nullable|string|max:255',
            'price' => 'nullable|integer|min:0',
        ]);

        if ($validation) return $validation;

        DB::beginTransaction();
        try {
            $productPrice = ProductPrice::findOrFail($id);
            $productPrice->previous_price = $productPrice->price;
            $productPrice = $this->dump_field($request->all(), $productPrice);

            $productPrice->save();

            DB::commit();

            return response([
                "message" => "success",
                "data" => $productPrice
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                "message" => "Error: server side having problem!",
                "th" => $th,
            ], 500);
        }
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

