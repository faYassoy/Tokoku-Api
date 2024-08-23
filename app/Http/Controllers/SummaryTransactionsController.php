<?php

namespace App\Http\Controllers;

use App\Models\SummaryTransactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SummaryTransactionsController extends Controller
{
    //
    public function index(Request $request)
    {
        $sortDirection = $request->get('sortDirection', 'DESC');
        $sortby = $request->get('sortBy', 'created_at');
        $paginate = $request->get('paginate', 10);
        $filter = $request->get('filter', null);

        $columnAliases = [
            'open_at' => 'summary_sales.open_at',
        ];

    
        $model = new SummaryTransactions();

        $query = SummaryTransactions::query();
        
        if ($request->get('search') != '') {
            $query = $this->search($request->get('search'), $model, $query);
        } else {
            $query = $query;
        }

        if ($filter) {
            $filters = json_decode($filter);
            foreach ($filters as $column => $value) {
                $query = $this->filter($this->remark_column($column, $columnAliases), $value, $query, $model);
            }
        }

        $query = $query->orderBy($this->remark_column($sortby, $columnAliases), $sortDirection)
            ->select($model->selectable)
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        /**
         * * Summary sale Creation
         */
        $model = new SummaryTransactions();
        $model->open_by = Auth::user()->id;
        $model->open_at = Carbon::now();

        try {
            if(Auth::user()->role == 'admin'){
                $model->save();
            }else{
                DB::rollback();
                return response([
                    'message' => "unautorize to open store!",
                ], 403);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return response([
                'message' => "Error: failed to insert new summary sale",
                'th' => $th,
            ], 500);
        }

        DB::commit();

        $model = SummaryTransactions:: where('id', $model->id)->first();

        return response([
            'message' => 'Success',
            'data' => $model,
        ]);
    }

    public function check_open(Request $request)
    {
      
        $model = SummaryTransactions::where('is_active', 1)
            ->first();
    
        if (!$model) {
            return response([
                'message' => 'Data not found'
            ], 404);
        }
    
        // Check if the found model is active
        if ($model->is_active != 1) {
            return response([
                'message' => 'Data not active'
            ], 404);
        }
    
        return response([
            'message' => 'Success',
            'data' => $model,
        ]);
    }

    public function close(Request $request)
    {
        // Ensure that the open_at value is in the correct format
        $openAt = Carbon::parse($request->open_at)->format('Y-m-d H:i:s');

        $model = SummaryTransactions::where('is_active', 1)
            ->first();

        if (!$model) {
            return response([
                'message' => 'Data not found'
            ], 404);
        }

        $model->is_active = 0;
        // $model->close_by = Auth::user()->id;
        $model->close_at = Carbon::now();

        try {
            DB::beginTransaction(); // Begin transaction here
            $model->save();
            DB::commit(); // Commit transaction here
        } catch (\Throwable $th) {
            DB::rollback();
            return response([
                'message' => "Error: failed to close active summary sale",
                'th' => $th
            ], 500);
        }

        return response([
            'message' => 'Success',
            'data' => $model,
        ]);
    }
    public function destroy($id)
    {
        $model = SummaryTransactions::findOrFail($id);

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
