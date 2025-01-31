<?php

namespace App\Filament\Resources;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Resources\BusinessProfileSubmissionResource\Pages;
use App\Filament\Resources\BusinessProfileSubmissionResource\RelationManagers;
use App\Models\BusinessProfile;
use App\Models\BusinessProfileSubmission;
use App\Policies\BusinessProfileSubmissionPolicy;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Traineratwot\FilamentOpenStreetMap\Forms\Components\MapInput;

class BusinessProfileSubmissionResource extends Resource
{
    protected static ?string $model = BusinessProfileSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'UMKM';

    protected static ?int $navigationSort = 2;

    protected static ?string $pluralModelLabel = 'UMKM';

    protected static ?string $modelLabel = 'UMKM';

    protected static ?string $slug = 'umkm';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('user_id', auth()->id())->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama UMKM')
                    ->required()
                    ->maxLength(255),
                Select::make('category_business_id')
                    ->label('Kategori UMKM')
                    ->relationship(name: 'category_business', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TinyEditor::make('description')
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsVisibility('public')
                    ->fileAttachmentsDirectory('uploads')
                    ->profile('full')
                    ->columnSpanFull()
                    ->label('Deskripsi'),
                Section::make('Detail Informasi UMKM')
                    ->schema([
                        Select::make('hamlet_id')
                            ->label('Nama Dusun')
                            ->relationship(name: 'hamlet', titleAttribute: 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('range')
                            ->maxLength(255)
                            ->label('Rentang Harga')
                            ->placeholder('Rp 10.000 - Rp 100.000')
                            ->required(),                       
                    ])
                    ->compact()
                    ->columns(2),
                Section::make('Media Sosial')
                    ->schema([
                        TextInput::make('facebook')
                            ->maxLength(255)
                            ->columns(3)
                            ->placeholder('https://facebook.com/NamaAkunUMKM/')
                            ->regex('/\b((http[s]?):\/\/)?([a-zA-Z0-9.-]+\.[a-zA-Z]{2,6})(:[0-9]{1,5})?(\/.*)?\b/i'),
                        TextInput::make('instagram')
                            ->maxLength(255)
                            ->columns(3)
                            ->placeholder('https://www.instagram.com/NamaAkunUMKM/')
                            ->regex('/\b((http[s]?):\/\/)?([a-zA-Z0-9.-]+\.[a-zA-Z]{2,6})(:[0-9]{1,5})?(\/.*)?\b/i'),
                        TextInput::make('tiktok')
                            ->maxLength(255)
                            ->columns(3)
                            ->placeholder('https://www.tiktok.com/@NamaAkunUMKM/')
                            ->regex('/\b((http[s]?):\/\/)?([a-zA-Z0-9.-]+\.[a-zA-Z]{2,6})(:[0-9]{1,5})?(\/.*)?\b/i'),
                    ])
                    ->compact()
                    ->columns(3)
                    ->collapsible()
                    ->collapsed(),
                MapInput::make('location')
                    ->label('Lokasi UMKM')
                    ->zoom(20)
                    ->saveAsArray()
                    ->placeholder('Geser ke lokasi UMKM')
                    ->coordinates(98.7438529, 3.6635647)
                    ->rows(20)
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        $queryModifier = function (Builder $query) {
            $query->where('user_id', auth()->id());
        };

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
                IconColumn::make('approved')
                    ->boolean() // Treats the column as boolean (0/1)
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning')
                    ->trueIcon('heroicon-s-check-circle')
                    ->falseIcon('heroicon-s-x-circle'),
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
            ->defaultSort('name', 'asc')
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
            'index' => Pages\ListBusinessProfileSubmissions::route('/'),
            'create' => Pages\CreateBusinessProfileSubmission::route('/create'),
            'edit' => Pages\EditBusinessProfileSubmission::route('/{record}/edit'),
        ];
    }
}
