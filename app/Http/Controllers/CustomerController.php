<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
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
        $model = new Customer();
        $query = Customer::query();

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
        // Validate request
        $validation = $this->validation($request->all(), [
            'name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'credit_balance' => 'integer|min:0',
        ]);

        if ($validation) return $validation;

        return DB::transaction(function () use ($request) {
            $customer = new Customer();
            $customer = $this->dump_field($request->all(), $customer);

            try {
                $customer->save();
            } catch (\Throwable $th) {
                DB::rollBack();
                return response([
                    "message" => "Error: server side having problem!",
                ], 500);
            }

            DB::commit();

            return response([
                "message" => "success",
                "data" => $customer
            ], 201);
        }, );
    }

    public function update(Request $request, $id)
    {
        // Validate request
        $validation = $this->validation($request->all(), [
            'name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'credit_balance' => 'integer|min:0',
        ]);

        if ($validation) return $validation;

        return DB::transaction(function () use ($request, $id) {
            $customer = Customer::findOrFail($id);
            $customer = $this->dump_field($request->all(), $customer);

            try {
                $customer->save();
            } catch (\Throwable $th) {
                DB::rollBack();
                return response([
                    "message" => "Error: server side having problem!",
                ], 500);
            }

            DB::commit();

            return response([
                "message" => "success",
                "data" => $customer
            ], 200);
        }, );
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();
            return response()->json(['message' => 'Customer deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Failed to delete customer',
                'details' => $e->getMessage()
            ], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Customer not found',
                'details' => $e->getMessage()
            ], 404);
        }
    }
}
