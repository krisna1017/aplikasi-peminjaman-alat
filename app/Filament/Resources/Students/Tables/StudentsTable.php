<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->contentGrid([
                'xl' => 4,
                'lg' => 3,
                'md' => 3,
            ])
            ->columns([
                Grid::make([
                    'default' => 1
                ])->schema([
                    ImageColumn::make('profile_picture')
                        ->label('Profile Picture')
                        ->disk('public')
                        ->imageSize(200),

                    Stack::make([
                        TextColumn::make('user.name')
                            ->label('Student Name')
                            ->weight(FontWeight::Bold)
                            ->searchable()
                            ->sortable(),
                        TextColumn::make('nisn')
                            ->icon(Heroicon::OutlinedBookOpen)
                            ->label('NISN')
                            ->searchable(),
                        TextColumn::make('classroom.name')
                            ->icon(Heroicon::OutlinedBuildingLibrary)
                            ->label('Class')
                            ->searchable()
                            ->sortable(),
                        TextColumn::make('phone_number')
                            ->icon(Heroicon::OutlinedPhone)
                            ->label('Phone Number')
                            ->searchable(),
                        TextColumn::make('gender')
                            ->label('Gender')
                            ->badge(),
                    ]),

                ]),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),

                ActionGroup::make([
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
