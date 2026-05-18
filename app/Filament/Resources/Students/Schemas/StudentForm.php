<?php

namespace App\Filament\Resources\Students\Schemas;

use App\Models\Student;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->required()
                    ->label('Student Name')
                    ->relationship('user', 'name', fn($query) => $query->whereHas('roles', fn($query) => $query->where('name', 'student')))
                    ->disableOptionWhen(fn($value) => Student::where('user_id', $value)->exists())
                    ->createOptionForm(
                        fn(Schema $schema) => $schema
                            ->components([
                                TextInput::make('name')
                                    ->required(),
                                TextInput::make('email')
                                    ->label('Email address')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                Select::make('roles')
                                    ->label('Role')
                                    ->relationship('roles', 'name')
                                    ->required(),
                                DateTimePicker::make('email_verified_at'),
                                TextInput::make('password')
                                    ->password()
                                    ->required(),
                            ])
                    ),
                Select::make('classroom_id')
                    ->required()
                    ->label('Class')
                    ->relationship('classroom', 'name'),
                TextInput::make('nisn')
                    ->required()
                    ->label('NISN')
                    ->unique(ignoreRecord: true)
                    ->validationMessages(['unique' => 'the NISN has been already registered']),
                TextInput::make('phone_number')
                    ->tel()
                    ->label('Phone Number')
                    ->required(),
                Select::make('gender')
                    ->label('Gender')
                    ->options(['male' => 'Male', 'female' => 'Female'])
                    ->required(),
                Textarea::make('address')
                    ->label('Address')
                    ->default(null)
                    ->columnSpanFull(),
                FileUpload::make('profile_picture')
                    ->label('Profile Picture')
                    ->directory('Students')
                    ->disk('public')
                    ->visibility('public')
                    ->default(null),
            ]);
    }
}
