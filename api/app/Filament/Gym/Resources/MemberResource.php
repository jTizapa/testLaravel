<?php

namespace App\Filament\Gym\Resources;

use App\Filament\Gym\Resources\MemberResource\Pages;
use App\Models\Member;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Members';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('phone')->maxLength(50),
            Forms\Components\Select::make('status')->options([
                'active' => 'Active',
                'inactive' => 'Inactive',
            ])->default('active')->required(),
            Forms\Components\DateTimePicker::make('joined_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'success' => 'active',
                    'danger' => 'inactive',
                ]),
                Tables\Columns\TextColumn::make('joined_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
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
            'index' => Pages\ManageMembers::route('/'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Management';
    }
}

namespace App\Filament\Gym\Resources\MemberResource\Pages;

use App\Filament\Gym\Resources\MemberResource;
use Filament\Resources\Pages\ManageRecords;

class ManageMembers extends ManageRecords
{
    protected static string $resource = MemberResource::class;
}

