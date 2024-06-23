<?php

// app/Http/Controllers/TransactionController.php
namespace App\Http\Controllers;

use App\Models\ProductStock;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // ? Initial params
        $sortDirection = $request->get("sortDirection", "DESC");
        $sortby = $request->get("sortBy", "transaction_date");
        $paginate = $request->get("paginate", 10);
        $filter = $request->get("filter", null);

        // ? Preparation
        $columnAliases = [
            // Add any column aliases here if necessary
        ];

        // ? Begin
        $model = new Transaction();
        $query = Transaction::query();

        // ? When search
        if ($request->get("search") != "") {
            $query = $this->search($request->get("search"), $model, $query);
        }

        // ? When Filter
        if ($filter) {
            $filters = json_decode($filter, true);
            foreach ($filters as $column => $value) {
                $query = $this->filter($this->remark_column($column, $columnAliases), $value, $model, $query);
            }
        }

        // ? Sort & executing with pagination
        $query = $query->orderBy($this->remark_column($sortby, $columnAliases), $sortDirection)
            ->select($model->getFillable())->paginate($paginate);

        // ? When empty
        if (empty($query->items())) {
            return response([
                "message" => "empty data",
                "data" => [],
            ], 200);
        }

        // ? When success
        return response([
            "message" => "success",
            "data" => $query->items(),
            "total_row" => $query->total(),
        ]);
    }

    public function store(Request $request)
    {
        $validation = $this->validation($request->all(), [
            'user_id' => 'required|exists:users,user_id',
            'customer_id' => 'nullable|exists:customers,customer_id',
            'transaction_date' => 'required|date',
            'total_amount' => 'required|integer',
            'payment_type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,product_id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.price' => 'required|integer|min:0'
        ]);

        if ($validation) return $validation;

        DB::beginTransaction();
        $transaction = new Transaction();
        $transaction = $this->dump_field($request->all(), $transaction);

        try {
            $transaction->save();

            foreach ($request->details as $detail) {
                $transactionDetail = new TransactionDetail();
                $transactionDetail->transaction_id = $transaction->transaction_id;
                $transactionDetail->product_id = $detail['product_id'];
                $transactionDetail->quantity = $detail['quantity'];
                $transactionDetail->price = $detail['price'];
                $transactionDetail->total_price = $detail['quantity'] * $detail['price'];
                $transactionDetail->save();

                // Update product stock
                $productStock = ProductStock::where('product_id', $detail['product_id'])->first();
                if ($productStock) {
                    $productStock->previous_stock_quantity = $productStock->stock_quantity;
                    $productStock->stock_quantity -= $detail['quantity'];
                    $productStock->save();
                } else {
                    ProductStock::create([
                        'product_id' => $detail['product_id'],
                        'stock_quantity' => 0 - $detail['quantity'],
                        'previous_stock_quantity' => 0
                    ]);
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                "message" => "Error: server side having problem!",
            ], 500);
        }

        DB::commit();

        return response([
            "message" => "success",
            "data" => $transaction
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validation = $this->validation($request->all(), [
            'user_id' => 'required|exists:users,user_id',
            'customer_id' => 'nullable|exists:customers,customer_id',
            'transaction_date' => 'required|date',
            'total_amount' => 'required|integer',
            'payment_type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,product_id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.price' => 'required|integer|min:0'
        ]);

        if ($validation) return $validation;

        DB::beginTransaction();
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response([
                "message" => "Transaction not found",
            ], 404);
        }

        $transaction = $this->dump_field($request->all(), $transaction);

        try {
            // Restore previous stock quantities
            foreach ($transaction->details as $detail) {
                $productStock = ProductStock::where('product_id', $detail->product_id)->first();
                if ($productStock) {
                    $productStock->stock_quantity += $detail->quantity;
                    $productStock->save();
                }
                $detail->delete();
            }

            $transaction->save();

            foreach ($request->details as $detail) {
                $transactionDetail = new TransactionDetail();
                $transactionDetail->transaction_id = $transaction->transaction_id;
                $transactionDetail->product_id = $detail['product_id'];
                $transactionDetail->quantity = $detail['quantity'];
                $transactionDetail->price = $detail['price'];
                $transactionDetail->total_price = $detail['quantity'] * $detail['price'];
                $transactionDetail->save();

                // Update product stock
                $productStock = ProductStock::where('product_id', $detail['product_id'])->first();
                if ($productStock) {
                    $productStock->previous_stock_quantity = $productStock->stock_quantity;
                    $productStock->stock_quantity -= $detail['quantity'];
                    $productStock->save();
                } else {
                    ProductStock::create([
                        'product_id' => $detail['product_id'],
                        'stock_quantity' => 0 - $detail['quantity'],
                        'previous_stock_quantity' => 0
                    ]);
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                "message" => "Error: server side having problem!",
            ], 500);
        }

        DB::commit();

        return response([
            "message" => "success",
            "data" => $transaction
        ], 200);
    }

    

    public function destroy($id)
    {
        $model = Transaction::findOrFail($id);

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
