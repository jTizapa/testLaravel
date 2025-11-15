<?php

namespace App\Filament\Gym\Resources;

use App\Filament\Gym\Resources\PlanResource\Pages;
use App\Models\Plan;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Plans';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('duration_days')->numeric()->minValue(1)->required(),
            Forms\Components\TextInput::make('price')->numeric()->minValue(0)->required(),
            Forms\Components\Toggle::make('active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('duration_days')->label('Days')->sortable(),
                Tables\Columns\TextColumn::make('price')->money('usd', true)->sortable(),
                Tables\Columns\IconColumn::make('active')->boolean(),
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
            'index' => Pages\ManagePlans::route('/'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Management';
    }
}

namespace App\Filament\Gym\Resources\PlanResource\Pages;

use App\Filament\Gym\Resources\PlanResource;
use Filament\Resources\Pages\ManageRecords;

class ManagePlans extends ManageRecords
{
    protected static string $resource = PlanResource::class;
}

