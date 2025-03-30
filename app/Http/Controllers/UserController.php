<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }



    public function total(Request $request): JsonResponse
    {
        return response()->json(Expense::where('user_id', $request->user()->id)->sum('amount'));
    }
}
