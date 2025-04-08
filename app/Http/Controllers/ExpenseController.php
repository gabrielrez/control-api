<?php

namespace App\Http\Controllers;

use App\Services\ExpenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected ExpenseService $expenseService;



    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }



    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'filter' => 'nullable|string',
        ]);

        $expenses = $this->expenseService->list(
            $request->user()->id,
            $validated['filter'] ?? null
        );

        return response()->json($expenses);
    }



    public function total(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);

        $total = $this->expenseService->total(
            $request->user()->id,
            $validated['from'] ?? null,
            $validated['to'] ?? null
        );

        return response()->json($total);
    }



    public function show(Request $request, int $id): JsonResponse
    {
        if (!$expense = $request->user()->expenses()->find($id)) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        return response()->json($expense);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tag_id' => 'required|exists:tags,id',
            'amount' => 'required|numeric|min:0',
        ]);

        if (!$request->user()->tags()->where('id', $validated['tag_id'])->exists()) {
            return response()->json(['message' => 'Invalid tag'], 403);
        }

        $expense = $request->user()->expenses()->create($validated);

        return response()->json([
            'message' => 'Expense created successfully',
            'expense' => $expense
        ], 201);
    }

    public function delete(Request $request, int $id): JsonResponse
    {
        if (!$expense = $request->user()->expenses()->find($id)) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        $expense->delete();

        return response()->json(['message' => 'Expense deleted successfully']);
    }
}
