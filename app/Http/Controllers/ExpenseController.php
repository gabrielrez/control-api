<?php

namespace App\Http\Controllers;

use App\Services\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected $TagService;



    public function __construct(TagService $TagService)
    {
        $this->TagService = $TagService;
    }



    public function index(Request $request): JsonResponse
    {
        $expenses = $request->user()->expenses()->with('tag')->get();

        $result = $expenses->map(function ($expense) {
            return [
                'id' => $expense->id,
                'amount' => $expense->amount,
                'tag' => $expense->tag_name,
                'created_at' => $expense->created_at,
                'updated_at' => $expense->updated_at,
            ];
        });

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

        if (!$this->TagService->userOwnsTag($request->user(), $validated['tag_id'])) {
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
