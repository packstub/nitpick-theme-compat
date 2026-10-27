<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends StatsOverviewWidget
{
    protected static ?int $sort = -1;

    protected function getStats(): array
    {
        return [
            Stat::make('Orders', Order::count())->description('12% up')->descriptionIcon('heroicon-m-arrow-trending-up')->color('success')->chart([3, 5, 4, 7, 6, 9, 8]),
            Stat::make('Revenue', '$'.number_format(Order::sum('total')))->description('3% down')->descriptionIcon('heroicon-m-arrow-trending-down')->color('danger')->chart([9, 7, 8, 6, 7, 5, 6]),
            Stat::make('Unpaid', Order::where('paid', false)->count()),
        ];
    }
}
