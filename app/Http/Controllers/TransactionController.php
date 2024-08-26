<?php

// app/Http/Controllers/TransactionController.php
namespace App\Http\Controllers;

use App\Models\ProductStock;
use App\Models\SummaryTransactions;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
class TransactionController extends Controller
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
       $model = new Transaction();
       $query = Transaction::query()->with('transactionDetails')->with('customer')->with('user');

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
        $validation = $this->validation($request->all(), [
            // 'transaction_number' => 'required|string|max:15|unique:transactions,transaction_number',
            'user_id' => 'required|exists:users,id',
            'customer_id' => 'nullable|exists:customers,id',
            // 'transaction_date' => 'required|date',
            'total_price' => 'required|integer',
            'total_bp' => 'required|integer',
            'payment_type' => 'required|string|max:255',
            'total_payment' => 'required|string|max:255',
            // 'status' => 'required|string|max:255',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.price' => 'required|integer|min:0'
        ]);
    
        if ($validation) return $validation;
    
        DB::beginTransaction();
    
        try {
            $transaction = new Transaction();
            $transaction = $this->dump_field($request->all(), $transaction);
            $transaction->transaction_number = $transaction->generateSaleNumber();
            $transaction->transaction_date = Carbon::now()->format('Y-m-d H:i:s');;
            $transaction->save();
    
            foreach ($request->details as $detail) {
                $transactionDetail = new TransactionDetail();
                $transactionDetail->transaction_id = $transaction->id;
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
    
            // Update summary transactions
            $summaryTransaction = SummaryTransactions::where('is_active', true)->first();
            if ($summaryTransaction) {
                $summaryTransaction->total_sales += $transaction->total_price;
                $summaryTransaction->total_sale_product += array_sum(array_column($request->details, 'quantity'));
                $summaryTransaction->total_payment += $transaction->total_payment;
                $summaryTransaction->total_income += $transaction->total_price - $transaction->total_bp;
                $summaryTransaction->save();
            }
    
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
            "data" => $transaction
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validation = $this->validation($request->all(), [
            'transaction_number' => 'required|string|max:15|unique:transactions,transaction_number,' . $id,
            'user_id' => 'required|exists:users,id',
            'customer_id' => 'nullable|exists:customers,id',
            'transaction_date' => 'required|date',
            'total_price' => 'required|integer',
            'total_bp' => 'required|integer',
            'payment_type' => 'required|string|max:255',
            'total_payment' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,id',
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
    
            $transaction = $this->dump_field($request->all(), $transaction);
            $transaction->save();
    
            foreach ($request->details as $detail) {
                $transactionDetail = new TransactionDetail();
                $transactionDetail->transaction_id = $transaction->id;
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
    
            // Update summary transactions
            $summaryTransaction = SummaryTransactions::where('is_active', true)->first();
            if ($summaryTransaction) {
                $summaryTransaction->total_sales += $transaction->total_price;
                $summaryTransaction->total_sale_product += array_sum(array_column($request->details, 'quantity'));
                $summaryTransaction->total_payment += $transaction->total_payment;
                $summaryTransaction->total_income += $transaction->total_price - $transaction->total_bp;
                $summaryTransaction->save();
            }
    
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

    public function print_struck(Request $request, $id)
    {
        // Retrieve optional date and time from the request, if provided
        $date = $request->get('date', null);
        $time = $request->get('time', null);
    
        // Find the transaction with its details
        $transaction = Transaction::with([
            'transactionDetails.product', // Assuming relationship names are 'transactionDetails' and 'product'
            'customer', // Assuming there's a customer relationship
        ])->findOrFail($id);
    
        // Prepare custom paper size (adjust as needed)
        $customPaper = [0, 0, 222, 1000];
    
        // Load the receipt view and generate PDF
        $pdf = Pdf::setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('pdf.receipt', ['sale' => $transaction, 'date' => $date, 'time' => $time])
            ->setPaper($customPaper);
    
        // return $pdf->stream("Receipt-$transaction->transaction_number.pdf");
        return 'data:application/pdf;base64,'.base64_encode($pdf->stream("Receipt-$transaction->transaction_number.pdf"));
    }
}
