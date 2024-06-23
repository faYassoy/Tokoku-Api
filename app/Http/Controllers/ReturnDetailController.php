<?php

// app/Http/Controllers/ReturnDetailController.php
namespace App\Http\Controllers;

use App\Models\ReturnDetail;
use Illuminate\Http\Request;

class ReturnDetailController extends Controller
{
    /**
     * Display a listing of the return details.
     */
    public function index()
    {
        $returnDetails = ReturnDetail::with(['return', 'product'])->get();
        return response()->json($returnDetails);
    }

    /**
     * Store a newly created return detail in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'return_id' => 'required|exists:returns,return_id',
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
            'refund_amount' => 'required|integer|min:0',
        ]);

        $returnDetail = ReturnDetail::create($validated);

        return response()->json($returnDetail, 201);
    }

    /**
     * Display the specified return detail.
     */
    public function show($id)
    {
        $returnDetail = ReturnDetail::with(['return', 'product'])->findOrFail($id);
        return response()->json($returnDetail);
    }

    /**
     * Update the specified return detail in storage.
     */
    public function update(Request $request, $id)
    {
        $returnDetail = ReturnDetail::findOrFail($id);

        $validated = $request->validate([
            'return_id' => 'sometimes|required|exists:returns,return_id',
            'product_id' => 'sometimes|required|exists:products,product_id',
            'quantity' => 'sometimes|required|integer|min:1',
            'refund_amount' => 'sometimes|required|integer|min:0',
        ]);

        $returnDetail->update($validated);

        return response()->json($returnDetail);
    }

    /**
     * Remove the specified return detail from storage.
     */
    public function destroy($id)
    {
        $returnDetail = ReturnDetail::findOrFail($id);
        $returnDetail->delete();

        return response()->json(null, 204);
    }
}

