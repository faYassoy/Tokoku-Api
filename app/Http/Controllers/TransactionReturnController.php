<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionReturn;
use App\Models\ProductStock;
use App\Models\SummaryTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionReturnController extends Controller
{
    public function index(Request $request, $transaction_id)
    {
        $sortDirection = $request->get('sortDirection', 'DESC');
        $sortby = $request->get('sortBy', 'created_at');
        $paginate = $request->get('paginate', 10);
        $filter = $request->get('filter', null);

        $columnAliases = [
            'created_at' => 'transaction_returns.created_at',
        ];
    
        $model = new TransactionReturn();

        $query = TransactionReturn::with([
                'transaction', 'product'
            ]);
        
        if ($request->get('search') != '') {
            $query = $this->search($request->get('search'), $model);
        } else {
            $query = $query;
        }

        if ($filter) {
            $filters = json_decode($filter);
            foreach ($filters as $column => $value) {
                $query = $this->filter($this->remark_column($column, $columnAliases), $value, $query);
            }
        }

        $query = $query->orderBy($this->remark_column($sortby, $columnAliases), $sortDirection)
            ->select($model->selectable)
            ->join('transactions', 'transactions.id', 'transaction_returns.transaction_id')
            ->where('transactions.id', $transaction_id)
            ->distinct('transactions.id')
            ->paginate($paginate);

        if (empty($query->items())) {
            return response([
                'message' => 'Empty data',
                'data' => [],
            ], 200);
        }

        return response([
            'message' => 'Success',
            'data' => $query->all(),
            'total_row' => $query->total()
        ]);
    }
    public function store(Request $request, $transaction_id)
    {
        $validate = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|numeric|min:0',
            'return_amount' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => "Error: unprocessable entity, validation error!",
                'errors' => $validate->errors(),
            ], 422);
        }

        $transaction = Transaction::with('transactionDetails')
            ->where('id', $transaction_id)
            ->first();

        if (!$transaction) {
            return response()->json([
                'message' => "Error: unprocessable entity, validation error!",
                'errors' => [
                    'transaction_id' => ['Transaction not found']
                ]
            ], 422);
        }

        $todaySummary = SummaryTransactions::where('is_active', 1)->first();

        DB::beginTransaction();

        $return_amount = $request->return_amount ?? (Product::find($request->product_id)->sell_price * $request->quantity);

        // Create Transaction Return
        $transactionReturn = new TransactionReturn();
        $transactionReturn->transaction_id = $transaction->id;
        $transactionReturn->product_id = $request->product_id;
        $transactionReturn->quantity = $request->quantity;
        $transactionReturn->return_amount = $return_amount;
        $transactionReturn->note = $request->note;

        try {
            $transactionReturn->save();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => 'Error: failed to insert new transaction return'], 500);
        }

        // Update Product Stock
        $productStock = ProductStock::where('product_id', $transactionReturn->product_id)->first();
        $productStock->stock_quantity += $transactionReturn->quantity;

        try {
            $productStock->save();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => 'Error: failed to update product stock'], 500);
        }

        // Update Summary Transaction
        $todaySummary->total_sale_product -= $request->quantity;
        $todaySummary->total_income -= $return_amount;

        try {
            $todaySummary->save();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => 'Error: failed to update summary transaction'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Transaction return created successfully'], 201);
    }
}

