<?php

namespace App\Filament\Resources;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Resources\BusinessProfileApprovedResource\Pages;
use App\Filament\Resources\BusinessProfileApprovedResource\RelationManagers;
use App\Models\BusinessProfile;
use App\Models\BusinessProfileApproved;
use App\Models\ChiefHamlet;
use App\Policies\BusinessProfilePolicy;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Traineratwot\FilamentOpenStreetMap\Forms\Components\MapInput;

class BusinessProfileApprovedResource extends Resource
{
    protected static ?string $model = BusinessProfileApproved::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Permohonan UMKM';

    protected static ?int $navigationSort = 1;

    protected static ?string $pluralModelLabel = 'Permohonan UMKM';

    protected static ?string $modelLabel = 'Permohonan UMKM';

    protected static ?string $slug = 'permohonan-umkm';

    public static function getNavigationBadge(): ?string
    {
        $user = auth()->user();

        $queryModifier = function (Builder $query) {
            $query->where('approved', 0);
        };

        if ($user && $user->hasRole('kepala_dusun')) {
            $hamletIds = ChiefHamlet::where('user_id', $user->id)
                ->pluck('hamlet_id');

            $count = static::getModel()::where($queryModifier)
                ->whereIn('hamlet_id', $hamletIds)
                ->count();
        } else if ($user && ($user->hasRole('super_admin') || $user->hasRole('admin'))) {
            $count = static::getModel()::where($queryModifier)->count();
        } else {
            $count = 0;
        }

        return (string) $count;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama UMKM')
                    ->disabled(),
                Select::make('category_business_id')
                    ->label('Kategori UMKM')
                    ->relationship(name: 'category_business', titleAttribute: 'name')
                    ->disabled(),
                TinyEditor::make('description')
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsVisibility('public')
                    ->fileAttachmentsDirectory('uploads')
                    ->profile('full')
                    ->columnSpanFull()
                    ->label('Deskripsi')
                    ->disabled(),
                Section::make('Detail Informasi UMKM')
                    ->schema([
                        Select::make('user_id')
                            ->relationship(name: 'user', titleAttribute: 'name')
                            ->label('Pemilik UMKM')
                            ->disabled(),
                        Select::make('hamlet_id')
                            ->label('Nama Dusun')
                            ->relationship(name: 'hamlet', titleAttribute: 'name')
                            ->disabled(),
                        TextInput::make('range')
                            ->label('Rentang Harga')
                            ->disabled(),                       
                    ])
                    ->compact()
                    ->columns(3),
                Section::make('Media Sosial')
                    ->schema([
                        TextInput::make('facebook')
                            ->columns(3)
                            ->disabled(),
                        TextInput::make('instagram')
                            ->columns(3)
                            ->disabled(),
                        TextInput::make('tiktok')
                            ->columns(3)
                            ->disabled(),
                    ])
                    ->compact()
                    ->columns(3)
                    ->collapsible()
                    ->collapsed(),
                MapInput::make('location')
                    ->zoom('20')
                    ->label('Lokasi UMKM')
                    ->saveAsArray()
                    ->placeholder('Geser ke lokasi UMKM')
                    ->rows(20)
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = auth()->user();

        $queryModifier = function (Builder $query) { };

        if ($user && $user->hasRole('kepala_dusun')) {
            $hamletIds = ChiefHamlet::where('user_id', $user->id)
                ->pluck('hamlet_id');

            $queryModifier = function (Builder $query) use ($hamletIds) {
                $query->where('approved', 0)
                      ->whereIn('hamlet_id', $hamletIds);
            };
        } else if ($user && ($user->hasRole('super_admin') || $user->hasRole('admin'))) {
            $queryModifier = function (Builder $query) {
                $query->where('approved', 0);
            };
        } 

        return $table
            ->modifyQueryUsing($queryModifier)
            ->columns([
                TextColumn::make('index')
                    ->rowIndex()
                    ->label('No')
                    ->width(40),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama UMKM'),
                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->label('Pemilik'),
                TextColumn::make('category_business.name')
                    ->sortable()
                    ->searchable()
                    ->label('Kategori UMKM'),
                TextColumn::make('range')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Rentang Harga'),
                TextColumn::make('facebook')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('instagram')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tiktok')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ->defaultSort('updated_at', 'desc')
            ->persistSortInSession()
            ->striped()
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Detail'),
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
            'index' => Pages\ListBusinessProfileApproveds::route('/'),
            'setujui' => Pages\EditBusinessProfileApproved::route('/{record}/setujui'),
        ];
    }
}
