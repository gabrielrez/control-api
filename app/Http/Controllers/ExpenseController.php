<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected $expenseModel;



    public function __construct(Expense $expenseModel)
    {
        $this->expenseModel = $expenseModel;
    }



    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->expenses()->with('tag')->latest();

        if ($request->query('filter') === 'this_month') {
            $this->expenseModel->filterByMonth($query);
        }

        $expenses = $query->get();
        $result = $expenses->map->toApiFormat();

        return response()->json($result);
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
