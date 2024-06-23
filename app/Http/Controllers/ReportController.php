<?php

// app/Http/Controllers/ReportController.php
namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the reports.
     */
    public function index()
    {
        $reports = Report::with('user')->get();
        return response()->json($reports);
    }

    /**
     * Store a newly created report in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'generated_on' => 'required|date',
            'user_id' => 'required|exists:users,user_id',
        ]);

        $report = Report::create($validated);

        return response()->json($report, 201);
    }

    /**
     * Display the specified report.
     */
    public function show($id)
    {
        $report = Report::with('user')->findOrFail($id);
        return response()->json($report);
    }

    /**
     * Update the specified report in storage.
     */
    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $validated = $request->validate([
            'report_type' => 'sometimes|required|string|max:255',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date',
            'generated_on' => 'sometimes|required|date',
            'user_id' => 'sometimes|required|exists:users,user_id',
        ]);

        $report->update($validated);

        return response()->json($report);
    }

    /**
     * Remove the specified report from storage.
     */
    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return response()->json(null, 204);
    }
}

