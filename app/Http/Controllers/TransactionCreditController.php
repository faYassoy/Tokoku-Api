<?php

namespace App\Http\Controllers;

use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionCreditController extends Controller
{
    public function index(Request $request)
    {
        $sortDirection = $request->get("sortDirection", "DESC");
        $sortby = $request->get("sortBy", "due_date");
        $paginate = $request->get("paginate", 10);
        $filter = $request->get("filter", null);

        $columnAliases = [];
        $model = new CreditTransaction();
        $query = CreditTransaction::query();

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
            'transaction_id' => 'required|exists:transactions,transaction_id',
            'due_date' => 'required|date',
            'status' => 'required|string|max:255',
            'amount' => 'required|integer'
        ]);

        if ($validation) return $validation;

        DB::beginTransaction();
        $model = new CreditTransaction();
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
            'transaction_id' => 'required|exists:transactions,transaction_id',
            'due_date' => 'required|date',
            'status' => 'required|string|max:255',
            'amount' => 'required|integer'
        ]);

        if ($validation) return $validation;

        $model = CreditTransaction::findOrFail($id);
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
        $model = CreditTransaction::findOrFail($id);

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
