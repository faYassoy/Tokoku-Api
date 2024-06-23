<?php

// app/Http/Controllers/StockMovementController.php
namespace App\Http\Controllers;

use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    /**
     * Display a listing of the stock movements.
     */
    public function index()
    {
        $stockMovements = StockMovement::with(['product', 'relatedTransaction'])->get();
        return response()->json($stockMovements);
    }

    /**
     * Store a newly created stock movement in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'type' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'movement_date' => 'required|date',
            'related_transaction_id' => 'nullable|exists:transactions,transaction_id',
        ]);

        $stockMovement = StockMovement::create($validated);

        return response()->json($stockMovement, 201);
    }

    /**
     * Display the specified stock movement.
     */
    public function show($id)
    {
        $stockMovement = StockMovement::with(['product', 'relatedTransaction'])->findOrFail($id);
        return response()->json($stockMovement);
    }

    /**
     * Update the specified stock movement in storage.
     */
    public function update(Request $request, $id)
    {
        $stockMovement = StockMovement::findOrFail($id);

        $validated = $request->validate([
            'product_id' => 'sometimes|required|exists:products,product_id',
            'type' => 'sometimes|required|string|max:255',
            'quantity' => 'sometimes|required|integer',
            'movement_date' => 'sometimes|required|date',
            'related_transaction_id' => 'nullable|exists:transactions,transaction_id',
        ]);

        $stockMovement->update($validated);

        return response()->json($stockMovement);
    }

    /**
     * Remove the specified stock movement from storage.
     */
    public function destroy($id)
    {
        $stockMovement = StockMovement::findOrFail($id);
        $stockMovement->delete();

        return response()->json(null, 204);
    }
}

