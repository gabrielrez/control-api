<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->expenses);
    }

    public function show(Request $request, int $id)
    {
        if (!$expense = $request->user()->expenses()->find($id)) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        return response()->json($expense);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tag_id' => 'required|exists:tags,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $expense = $request->user()->expenses()->create($validated);

        return response()->json([
            'message' => 'Expense created successfully',
            'expense' => $expense
        ], 201);
    }

    public function delete(Request $request, int $id)
    {
        if (!$expense = $request->user()->expenses()->find($id)) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        $expense->delete();

        return response()->json(['message' => 'Expense deleted successfully']);
    }
}
