<?php
// app/Http/Controllers/ReturnController.php
namespace App\Http\Controllers;

use App\Models\ProductReturn;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    /**
     * Display a listing of the returns.
     */
    public function index()
    {
        $returns = ProductReturn::with(['transaction', 'user'])->get();
        return response()->json($returns);
    }

    /**
     * Store a newly created return in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,transaction_id',
            'return_date' => 'required|date',
            'user_id' => 'required|exists:users,user_id',
            'reason' => 'nullable|string',
        ]);

        $return = ProductReturn::create($validated);

        return response()->json($return, 201);
    }

    /**
     * Display the specified return.
     */
    public function show($id)
    {
        $return = ProductReturn::with(['transaction', 'user'])->findOrFail($id);
        return response()->json($return);
    }

    /**
     * Update the specified return in storage.
     */
    public function update(Request $request, $id)
    {
        $return = ProductReturn::findOrFail($id);

        $validated = $request->validate([
            'transaction_id' => 'sometimes|required|exists:transactions,transaction_id',
            'return_date' => 'sometimes|required|date',
            'user_id' => 'sometimes|required|exists:users,user_id',
            'reason' => 'nullable|string',
        ]);

        $return->update($validated);

        return response()->json($return);
    }

    /**
     * Remove the specified return from storage.
     */
    public function destroy($id)
    {
        $return = ProductReturn::findOrFail($id);
        $return->delete();

        return response()->json(null, 204);
    }
}
