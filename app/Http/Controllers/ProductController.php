<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
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
        $model = new Product();
        $query = Product::query()->with('prices')->with('productStock');

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
    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $validation = $this->validation($request->all(), [
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|unique:products,barcode',
            'image' => 'nullable',
            'buy_price' => 'required|integer|min:0',
            'sales_counter' => 'nullable|integer',
            'prices' => 'required|array',
            'prices.*.price_type' => 'required|string|max:255',
            'prices.*.price' => 'required|integer|min:0',
            'initial_stock_quantity' => 'required|integer|min:0'
        ]);

        if ($validation) return $validation;

        DB::beginTransaction();
        try {
            $product = new Product();
            $product = $this->dump_field($request->all(), $product);

            // Handle file upload if present
            if ($request->hasFile('image')) {
                $product->image = $this->upload_file($request->file('image'), 'products');
            }

            $product->save();

            // Create product prices
            foreach ($request->prices as $priceData) {
                $productPrice = new ProductPrice();
                $productPrice->product_id = $product->id;
                $productPrice->price_type = $priceData['price_type'];
                $productPrice->price = $priceData['price'];
                $productPrice->save();
            }

            // Create initial product stock
            $productStock = new ProductStock();
            $productStock->product_id = $product->id;
            $productStock->stock_quantity = $request->initial_stock_quantity;
            $productStock->previous_stock_quantity = 0;
            $productStock->save();

            DB::commit();

            return response([
                "message" => "success",
                "data" => $product
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                "message" => "Error: server side having problem!",
                // "th" => $th,
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        // Validate request
        $validation = $this->validation($request->all(), [
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|unique:products,barcode,' . $id . ',id',
            'image' => 'nullable',
            'buy_price' => 'required|integer|min:0',
            'sales_counter' => 'nullable|integer',
           
        ]);

        if ($validation) return $validation;

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $product = $this->dump_field($request->all(), $product);

            // Handle file upload if present
            if ($request->hasFile('image')) {
                $product->image = $this->upload_file($request->file('image'), 'products');
            }

            $product->save();

            // Update product prices
            // $product->prices()->delete(); // Assuming a one-to-many relationship and a cascade delete
            // foreach ($request->prices as $priceData) {
            //     $productPrice = new ProductPrice();
            //     $productPrice->product_id = $product->product_id;
            //     $productPrice->price_type = $priceData['price_type'];
            //     $productPrice->price = $priceData['price'];
            //     $productPrice->save();
            // }

            DB::commit();

            return response([
                "message" => "success",
                "data" => $product
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
        $model = Product::findOrFail($id);

        DB::beginTransaction();
        try {
            $model->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                "message" => "Error: server side having problem!",
                "th" => $th,
                
            ], 500);
        }

        DB::commit();

        return response([
            "message" => "success",
            "data" => $model
        ], 200);
    }
}
