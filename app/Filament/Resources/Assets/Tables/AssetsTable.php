<?php

namespace App\Filament\Resources\Assets\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ColumnGroup::make('Asset Details', [
                    ImageColumn::make('image')
                        ->disk('public')
                        ->imageSize(50),
                    TextColumn::make('name')
                        ->label('Asset Name')
                        ->searchable(),
                    TextColumn::make('code')
                        ->label('Code')
                        ->searchable(),
                    TextColumn::make('category.name')
                        ->label('Category')
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ]),
                ColumnGroup::make('Asset Condition / Stock', [
                    TextColumn::make('good_qty')
                        ->label('Good')
                        ->searchable()
                        ->numeric(),
                    TextColumn::make('damage_qty')
                        ->label('Damage')
                        ->searchable()
                        ->numeric(),
                    TextColumn::make('borrowed_qty')
                        ->label('Borrowed')
                        ->searchable()
                        ->numeric(),
                    TextColumn::make('lost_qty')
                        ->label('Lost')
                        ->searchable()
                        ->numeric(),
                    TextColumn::make('total_qty')
                        ->label('Total Quantity')
                        ->searchable()
                        ->numeric(),
                    TextColumn::make('available_qty')
                        ->label('Available')
                        ->searchable()
                        ->numeric()
                        ->getStateUsing(fn($record) => $record->good_qty - $record->borrowed_qty)
                        ->badge(),
                ]),



                IconColumn::make('is_available')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
