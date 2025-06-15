<?php
namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\User;

class UserStats extends BaseWidget
{
    protected function getCards(): array
    {
        $total = User::count();
        $admin = User::where('role', 'admin')->count();
        $faculty = User::where('role', 'faculty')->count();

        return [
            Card::make('Total Users', $total),
            Card::make('Admins', $admin)
                ->description($total > 0 ? round(($admin / $total) * 100) . '% of users' : '0% of users')
                ->descriptionIcon('heroicon-o-users')
                ->color('success')
                ->chart([1,5,2,10])
                ->extraAttributes(['class' => 'bg-blue-100 text-blue-800']),
            Card::make('Faculty', $faculty)
                ->description($total > 0 ? round(($faculty / $total) * 100) . '% of users' : '0% of users')
                ->descriptionIcon('heroicon-o-users')
                ->color('warning')
                ->extraAttributes(['class' => 'bg-green-100 text-green-800'])
                ->chart([1,3,5,1,4]),
        ];
    }
}