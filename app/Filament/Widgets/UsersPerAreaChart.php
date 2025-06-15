<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Area;

class UsersPerAreaChart extends ChartWidget
{
    protected static ?string $heading = 'User Faculty Per Area';
    protected static ?int $sort = 2;
    protected string | array | int $columnSpan = "1/2"; // 
    public static function canView(): bool
    {
        return auth()->user()?->role === 'admin';
    }
    protected function getData(): array
    {
        $areas = Area::withCount('users')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Faculty',
                    'data' => $areas->pluck('users_count')->toArray(),
                ],
            ],
            'labels' => $areas->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}