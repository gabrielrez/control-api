<?php

namespace App\Http\Controllers;

use App\Services\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected $tag_service;



    public function __construct(TagService $tag_service)
    {
        $this->tag_service = $tag_service;
    }



    public function index(Request $request): JsonResponse
    {
        return response()->json($request->user()->expenses);
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
            'date' => 'required|date',
        ]);

        if (!$this->tag_service->userOwnsTag($request->user(), $validated['tag_id'])) {
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
