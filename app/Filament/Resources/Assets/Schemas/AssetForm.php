<?php

namespace App\Filament\Resources\Assets\Schemas;

use App\Models\Asset;
use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AssetForm
{
    protected static function recalculateStock(Get $get, Set $set): void
    {
        $good = (int) $get('good_qty');
        $damage = (int) $get('damage_qty');
        $borrowed = (int) $get('borrowed_qty');
        $lost = (int) $get('lost_qty');

        $available = $good - $borrowed;
        $set('available_qty', $available);

        $total = $good + $damage + $borrowed + $lost;
        $set('total_qty', $total);
    }
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Fieldset::make('Asset Details')
                            ->schema([
                                Select::make('category_id')
                                    ->required()
                                    ->relationship('category', 'name')
                                    ->reactive()
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $category = Category::find($get('category_id'));
                                        if (!$category) {
                                            return;
                                        }

                                        $prefix = strtoupper(Str::substr($category->name, 0, 3));

                                        $lastcode = Asset::where('code', 'like', $prefix . '-%')
                                            ->orderBy('code', 'desc')
                                            ->value('code');

                                        if ($lastcode) {
                                            $number = (int) substr($lastcode, 3);
                                            $nextNumber = $number + 1;
                                        } else {
                                            $nextNumber = 1;
                                        }

                                        $code = $prefix .  str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                                        $set('code', $code);
                                    }),
                                TextInput::make('code')
                                    ->readOnly()
                                    ->reactive()
                                    ->required(),
                                TextInput::make('name')
                                    ->required()
                                    ->columnSpanFull(),
                                RichEditor::make('description')
                                    ->label('Description')
                                    ->columnSpanFull()
                                    ->extraAttributes(['style' => 'min-height: 250px;']),
                                FileUpload::make('image')
                                    ->label('Asset Picture')
                                    ->disk('public')
                                    ->directory('Assets Pictures')
                                    ->default(null)
                                    ->columnSpanFull(),
                            ]),

                        Toggle::make('is_available')
                            ->label('Status')
                            ->required(),
                    ])->columnSpan(2),


                Fieldset::make('Asset Conditions')
                    ->schema([
                        TextInput::make('good_qty')
                            ->label('Good')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->default(0)
                            ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateStock($get, $set)),
                        TextInput::make('damage_qty')
                            ->label('Damage')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->default(0)
                            ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateStock($get, $set)),
                        TextInput::make('borrowed_qty')
                            ->label('Borrowed')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->default(0)
                            ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateStock($get, $set)),
                        TextInput::make('lost_qty')
                            ->label('Lost')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->default(0)
                            ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateStock($get, $set)),
                        TextInput::make('available_qty')
                            ->label('Available')
                            ->numeric()
                            ->belowContent('Calculated as Good - Borrowed')
                            ->readOnly()
                            ->required()
                            ->default(0),
                        TextInput::make('total_qty')
                            ->label('Total')
                            ->numeric()
                            ->readOnly()
                            ->required()
                            ->default(0),
                    ])->columnSpan(1),
            ])->columns(3);
    }
}
