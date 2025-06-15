<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter user name'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('Enter user email'),
                TextInput::make('password')
                    ->password()
                    ->required(fn (string $context) => $context === 'create')
                    ->minLength(8)
                    ->visibleOn('create')
                    ->placeholder('Enter password'),
                Select::make('role')
                    ->options(['admin' => 'Admin', 'faculty' => 'Faculty'])
                    ->required()
                    ->live()
                    ->placeholder('Select role'),
                Select::make('areas')
                    ->relationship('areas', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Assigned Areas')
                    ->visible(fn ($get) => $get('role') === 'faculty')
                    ->placeholder('Select areas'),
            ])
            ->columns(1); // Single-column layout for the modal form
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->extraAttributes(['class' => 'text-lg']),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->color('primary'),
                TextColumn::make('role')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'success',
                        'faculty' => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'admin' => 'heroicon-o-shield-check',
                        'faculty' => 'heroicon-o-academic-cap',
                        default => '',
                    }),
                TextColumn::make('areas.name')
                    ->badge()
                    ->color('info')
                    ->separator(', ')
                    ->label('Areas'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options(['admin' => 'Admin', 'faculty' => 'Faculty'])
                    ->label('Filter by Role')
                    ->placeholder('All Roles'),
            ])
            ->actions([
                Action::make('_')
                    // ->label('Preview')
                    ->icon('heroicon-o-eye')
                    // ->modalHeading('User Preview')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(function (User $record) {
                        return view('filament.resources.user-resource.preview-modal', ['user' => $record]);
                    }),
                ActionGroup::make([
                    Tables\Actions\EditAction::make()->color('primary'),
                    Tables\Actions\DeleteAction::make()->color('danger'),
                ])
                    ->label('Actions')
                    ->icon('heroicon-o-pencil-square')
                    ->button(),
            ])
            ->recordAction('_')
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->color('danger'),
            ])
            ->striped()
            ->defaultSort('name', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'), // <-- This line enables your custom CreateUser logic!
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin();
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->isAdmin();
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->isAdmin();
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->isAdmin();
    }
}
