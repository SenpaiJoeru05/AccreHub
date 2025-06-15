<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Area;

class DocumentsPerAreaChart extends ChartWidget
{
    protected static ?string $heading = 'Documents Per Area';
    public function getColumnSpan(): string|int|array
    {
        $user = auth()->user();
        // For faculty, make it full width; for admin, keep it half width
        return $user->role === 'faculty' ? 'full' : '1/2';
    }
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $user = auth()->user();
        if ($user->role === 'faculty') {
            $areas = $user->areas()->withCount('documents')->get();
        } else {
            $areas = Area::withCount('documents')->get();
        }
    
        return [
            'datasets' => [
                [
                    'label' => 'Documents',
                    'data' => $areas->pluck('documents_count')->toArray(),
                    'backgroundColor' => [
                        'rgba(56,189,248,0.6)',   // sky-400
                        'rgba(129,140,248,0.6)',  // violet-400
                        'rgba(244,114,182,0.6)',  // pink-400
                        'rgba(52,211,153,0.6)',   // emerald-400
                        'rgba(251,191,36,0.6)',   // yellow-400
                        'rgba(163,230,53,0.6)',   // lime-400
                        'rgba(252,165,165,0.6)',  // red-300
                        'rgba(252,211,77,0.6)',   // amber-300
                        'rgba(192,132,252,0.6)',  // purple-300
                        'rgba(103,232,249,0.6)',  // cyan-300
                        'rgba(249,168,212,0.6)',  // pink-300
                        'rgba(190,242,100,0.6)',  // lime-300
                    ],
                    'borderColor' => 'rgba(23, 50, 202, 0.61)',
                    'borderWidth' => 1,
                    'borderRadius' => 2,
                    'borderSkipped' => false,
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