<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class OrdersChart extends ChartWidget
{
    protected ?string $heading = 'Orders per week';

    protected function getData(): array
    {
        return [
            'datasets' => [['label' => 'Orders', 'data' => [4, 7, 6, 9, 8, 11, 10, 13]]],
            'labels' => ['W1', 'W2', 'W3', 'W4', 'W5', 'W6', 'W7', 'W8'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
