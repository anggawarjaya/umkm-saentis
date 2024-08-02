<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChiefHamletResource\Pages;
use App\Filament\Resources\ChiefHamletResource\RelationManagers;
use App\Models\ChiefHamlet;
use App\Models\Hamlet;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChiefHamletResource extends Resource
{
    protected static ?string $model = ChiefHamlet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Kepala Dusun';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 2;

    protected static ?string $pluralModelLabel = 'Kepala Dusun';

    protected static ?string $modelLabel = 'Kepala Dusun';

    protected static ?string $slug = 'kepala-dusun';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Nama Kepala Dusun')
                    ->preload()
                    ->required()
                    ->options(function () {
                        $assignedUsers = ChiefHamlet::pluck('user_id')->toArray();
                        $users = User::role('kepala_dusun')
                            ->whereNotIn('id', $assignedUsers)
                            ->pluck('name', 'id')
                            ->toArray();
                        return $users ?: [];
                    }),
                Select::make('hamlet_id')
                    ->label('Nama Dusun')
                    ->options(function () {
                        $assignedHamlets = ChiefHamlet::pluck('hamlet_id')->toArray();
                        $hamlets = Hamlet::whereNotIn('id', $assignedHamlets)
                            ->pluck('name', 'id')
                            ->toArray();
                        return $hamlets ?: [];
                    })
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->rowIndex()
                    ->label('No')
                    ->width(40),
                TextColumn::make('user.name')
                    ->label('Nama Kepala Dusun')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('hamlet.name')
                    ->label('Nama Dusun')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Ditambah pada')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Diubah pada')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('hamlet.name', 'asc')
            ->persistSortInSession()
            ->striped()
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                DeleteAction::make(),
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
            'index' => Pages\ListChiefHamlets::route('/'),
            'create' => Pages\CreateChiefHamlet::route('/create'),
            'edit' => Pages\EditChiefHamlet::route('/{record}/edit'),
        ];
    }
}
