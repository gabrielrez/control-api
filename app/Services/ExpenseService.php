<?php

namespace App\Services;

use App\Models\Expense;
use Carbon\Carbon;

class ExpenseService
{
    protected Expense $expenseModel;



    public function __construct(Expense $expenseModel)
    {
        $this->expenseModel = $expenseModel;
    }



    public function total(int $user_id, ?string $from, ?string $to)
    {
        $query = $this->expenseModel->where('user_id', $user_id);

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



    public function list(int $user_id, ?string $filter = null)
    {
        $query = Expense::where('user_id', $user_id)
            ->with('tag')
            ->latest();

        if ($filter === 'this_month') {
            $this->expenseModel->filterByMonth($query);
        }

        return $query->get()->map->toApiFormat();
    }
}
