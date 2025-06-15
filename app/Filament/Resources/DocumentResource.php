<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('path')
                    ->required()
                    ->directory('documents')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    ])
                    ->maxSize(10240), // 10MB max
                Forms\Components\Select::make('area_id')
                    ->label('Area')
                    ->relationship('area', 'name')
                    ->options(function () {
                        $user = Auth::user();
                        return $user->isAdmin()
                            ? \App\Models\Area::pluck('name', 'id')
                            : $user->areas()->pluck('name', 'id');
                    })
                    ->required(),
                Forms\Components\Select::make('parameter')
                    ->options(['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E' => 'E'])
                    ->required(),
                Forms\Components\TextInput::make('section')
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'SAR' => 'SAR',
                        'Supporting Document' => 'Supporting Document',
                        'Other' => 'Other',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('year')
                    ->numeric()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $query = Document::query();
                if (!Auth::user()->isAdmin()) {
                    $query->whereIn('area_id', Auth::user()->areas()->pluck('id'));
                }
                return $query;
            })
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('area.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('parameter')
                    ->sortable(),
                Tables\Columns\TextColumn::make('year')
                    ->sortable(),
                Tables\Columns\TextColumn::make('section'),
                Tables\Columns\TextColumn::make('type'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('area')
                    ->relationship('area', 'name')
                    ->options(function () {
                        $user = Auth::user();
                        return $user->isAdmin()
                            ? \App\Models\Area::pluck('name', 'id')
                            : $user->areas()->pluck('name', 'id');
                    }),
                Tables\Filters\SelectFilter::make('parameter')
                    ->options(['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E' => 'E']),
                Tables\Filters\SelectFilter::make('year')
                    ->options(function () {
                        return Document::select('year')
                            ->distinct()
                            ->pluck('year', 'year')
                            ->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'view' => Pages\ViewDocument::route('/{record}'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }

    public static function getIndexView(): string
    {
        return 'filament.resources.document-resource.pages.list-documents';
    }
}