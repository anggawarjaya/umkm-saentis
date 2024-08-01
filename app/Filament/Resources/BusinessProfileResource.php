<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BusinessProfileResource\Pages;
use App\Filament\Resources\BusinessProfileResource\RelationManagers;
use App\Models\BusinessProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BusinessProfileResource extends Resource
{
    protected static ?string $model = BusinessProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('cover')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('image')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('range')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('facebook')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('instagram')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('tiktok')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('category_business_id')
                    ->relationship('category_business', 'name')
                    ->required(),
                Forms\Components\Select::make('hamlet_id')
                    ->relationship('hamlet', 'name')
                    ->required(),
                Forms\Components\Textarea::make('location')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('approved')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cover')
                    ->searchable(),
                Tables\Columns\TextColumn::make('range')
                    ->searchable(),
                Tables\Columns\TextColumn::make('facebook')
                    ->searchable(),
                Tables\Columns\TextColumn::make('instagram')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tiktok')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category_business.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hamlet.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('approved')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBusinessProfiles::route('/'),
            'create' => Pages\CreateBusinessProfile::route('/create'),
            'edit' => Pages\EditBusinessProfile::route('/{record}/edit'),
        ];
    }
}
