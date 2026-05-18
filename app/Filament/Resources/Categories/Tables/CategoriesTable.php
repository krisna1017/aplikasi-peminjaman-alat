<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
// use Filament\Schemas\Components\Grid;
// use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Tiptap\Marks\Bold;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->contentGrid([
                'xl' => 4,
                'lg' => 3,
                'md' => 2,
            ])
            ->columns([
                Grid::make([
                    'default' => 1
                ])->schema([
                    Stack::make([
                        'default' => 1
                    ])->schema([
                        ImageColumn::make('image')
                            ->imageSize(200, 200),
                        TextColumn::make('name')
                            ->weight('bold')
                            ->searchable()
                            ->sortable(),
                    ]),
                ]),
                TextColumn::make('is_active')
                    ->formatStateUsing(fn(string $state) => $state ? 'Active' : 'Inactive')
                    ->badge()
                    ->color(fn(string $state) => $state ? 'success' : 'danger'),
                // ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
