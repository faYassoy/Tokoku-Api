<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
        $model = new User();
        $query = User::query();

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
            'username' => 'required|string|unique:users,username|max:255',
            'password' => 'required|string|min:8',
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
        ]);

        if ($validation) return $validation;

        return DB::transaction(function () use ($request) {
            $user = new User();
            $user = $this->dump_field($request->all(), $user);
            $user->password = bcrypt($request->password);

            try {
                $user->save();
            } catch (\Throwable $th) {
                DB::rollBack();
                return response([
                    "message" => "Error: server side having problem!",
                ], 500);
            }

            DB::commit();

            return response([
                "message" => "success",
                "data" => $user
            ], 201);
        });
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user, 200);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Failed to retrieve user',
                'details' => $e->getMessage()
            ], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'User not found',
                'details' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        // Validate request
        $validation = $this->validation($request->all(), [
            'username' => 'required|string|max:255|unique:users,username,' . $id . ',id',
            'password' => 'sometimes|string|min:8',
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
        ]);

        if ($validation) return $validation;

        return DB::transaction(function () use ($request, $id) {
            $user = User::findOrFail($id);
            $user = $this->dump_field($request->all(), $user);

            if ($request->has('password')) {
                $user->password = bcrypt($request->password);
            }

            try {
                $user->save();
            } catch (\Throwable $th) {
                DB::rollBack();
                return response([
                    "message" => "Error: server side having problem!",
                ], 500);
            }

            DB::commit();

            return response([
                "message" => "success",
                "data" => $user
            ], 200);
        }, 5);
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Failed to delete user',
                'details' => $e->getMessage()
            ], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'User not found',
                'details' => $e->getMessage()
            ], 404);
        }
    }
}
