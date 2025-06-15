<?php
namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use App\Models\ActivityLog;

class UserActivityTable extends BaseWidget
{
    public static function canView(): bool
{
    return auth()->user()?->role === 'admin';
}
    protected static ?string $heading = 'Recent User Activity';
    protected string | array | int $columnSpan = 'full';
    protected static ?int $sort = 3;

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation|null
    {
        return ActivityLog::query()->latest()->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('user.name')->label('User'),
            Tables\Columns\TextColumn::make('action')
                ->label('Action')
                ->formatStateUsing(fn($state) => match($state) {
                    'created_user' => 'Created User',
                    'edited_user' => 'Edited User',
                    'deleted_user' => 'Deleted User',
                    'added_document' => 'Added Document',
                    'edited_document' => 'Edited Document',
                    'deleted_document' => 'Deleted Document',
                    default => ucfirst(str_replace('_', ' ', $state)),
                }),
            Tables\Columns\TextColumn::make('description')
                ->label('Details')
                ->limit(60),
            Tables\Columns\TextColumn::make('created_at')->label('When')->since(),
        ];
    }
    
}