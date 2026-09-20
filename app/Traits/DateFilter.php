<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait DateFilter
{
    public function scopeApplyDateFilter(Builder $query, string $filter, string $searchDate = ''): Builder
    {
        if ($searchDate) {
            return $query->whereDate('created_at', $searchDate);
        }

        if ($filter === 'today') {
            return $query->whereDate('created_at', today());
        }

        if ($filter === 'week') {
            return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        }

        if ($filter === 'month') {
            return $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }

        return $query;
    }
}
