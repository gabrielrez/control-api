<?php

namespace App\Services;

use App\Models\Expense;
use Carbon\Carbon;

class ExpenseService
{
    public function totalAmount(int $user_id, ?string $from, ?string $to)
    {
        $query = Expense::where('user_id', $user_id);

        if ($from) {
            $query->where('created_at', '>=', Carbon::parse($from)->startOfDay());
        }

        if ($to) {
            $query->where('created_at', '<=', Carbon::parse($to)->endOfDay());
        }

        if (!$from && !$to) {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }

        return $query->sum('amount');
    }
}
