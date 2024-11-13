<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    protected $table = 'transactions';
    // protected $primaryKey = 'transaction_id';
    protected $fillable = [
        'user_id',
        'transaction_number',
        'customer_id',
        'transaction_date',
        'total_price',
        'total_bp',
        'total_payment',
        'payment_type'
    ];
    public $selectable = [
        'transactions.user_id',
        'transactions.customer_id',
        'transactions.transaction_date',
        'transactions.total_price',
        'transactions.total_bp',
        'transactions.total_payment',
        'transactions.payment_type',
        'transactions.status'
    ];

    protected $searchable = [
        'transaction_number'  // This will allow searching by transaction_number
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }

    public function creditTransaction()
    {
        return $this->hasOne(CreditTransaction::class, 'transaction_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'related_transaction_id');
    }

    public function transaction_payments()
    {
        return $this->hasMany(TransactionPayment::class, 'transaction_id', 'id');
    }

    public static function getStatusEnum($key)
    {

        $lists = [
            'PENDING' => 'pending',
            'DONE' => 'done',
            'WAITING_PAYMENT' => 'waiting_payment',
            'CANCEL' => 'cancel'
        ];

        if (isset($lists[$key])) {

            return $lists[$key];
        }

        return null;
    }

    public function transactionReturns()
    {
        return $this->hasMany(TransactionReturn::class, 'transaction_id', 'id');
    }

    public function generateSaleNumber()
    {
        $zeroPadding = "00000";
        $prefixCode = "TS-";

        $code = $prefixCode . date('dmY');

        $increment = 1;
        $similiarCode = DB::table('transactions')->select('transaction_number')
            // ->whereRaw('DATE(created_at) = DATE(NOW())')
            ->where('transaction_number', 'like', $code . "_____")
            ->orderBy('transaction_number', 'desc')
            ->first();

        if (!$similiarCode) {
            $increment = 1;
        } else {
            $increment = (int) substr($similiarCode->transaction_number, strlen($code));
            $increment = $increment + 1;
        }

        $code = $code . substr($zeroPadding, strlen("$increment")) . $increment;

        return $code;
    }
}
