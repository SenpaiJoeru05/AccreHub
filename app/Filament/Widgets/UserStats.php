<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\User;

class UserStats extends BaseWidget
{
    protected static ?int $sort = 1;
    public static function canView(): bool
    {
        return auth()->user()?->role === 'admin';
    }
    protected function getCards(): array
    {
        $total = User::count();
        $admin = User::where('role', 'admin')->count();
        $faculty = User::where('role', 'faculty')->count();

        return [
            Card::make('Total Users', $total)
                ->icon('heroicon-o-users')
                ->description('All registered users')
                ->color('primary'),
            Card::make('Admins', $admin)
                ->icon('heroicon-o-shield-check')
                ->description($total > 0 ? round(($admin / $total) * 100) . '% of users' : '0% of users')
                ->color('success'),
            Card::make('Faculty', $faculty)
                ->icon('heroicon-o-academic-cap')
                ->description($total > 0 ? round(($faculty / $total) * 100) . '% of users' : '0% of users')
                ->color('info'),
        ];
    }
}