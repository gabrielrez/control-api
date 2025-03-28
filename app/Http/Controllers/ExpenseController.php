<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        //
    }

    public function show(Request $request)
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        
    }

    public function delete(Request $request)
    {
        //
    }
}
