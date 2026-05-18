<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lending Information')
                    ->description('Assign an asset to a user and set the due date for returning the asset.')
                    ->schema([
                        Select::make('user_id')
                            ->required()
                            ->label('Requester User')
                            ->relationship('user', 'name'),
                        Select::make('asset_id')
                            ->required()
                            ->label('Asset Name')
                            ->relationship('asset', 'name'),
                        DatePicker::make('due_date')
                            ->label('Due Date'),
                        Textarea::make('note')
                            ->label('Additional Note')
                            ->columnSpanFull(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
