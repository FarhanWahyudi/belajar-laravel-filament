<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Kustomer';

    protected static ?string $navigationGroup = 'Kelola';

    protected static ?string $slug = 'customer';

    protected static ?string $label = 'kelola kustomer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_customer')
                    ->required()
                    ->label('Nama')
                    ->placeholder('masukkan nama anda'),
                TextInput::make('kode_customer')
                    ->required()
                    ->numeric()
                    ->label('Kode'),
                TextInput::make('alamat_customer')
                    ->required()
                    ->label('Alamat'),
                TextInput::make('telepon_customer')
                    ->required()
                    ->label('Telepon'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_customer')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('kode_customer')
                    ->label('Kode')
                    ->copyable()
                    ->copyMessage('di copy'),
                TextColumn::make('alamat_customer')
                    ->label('Alamat'),
                TextColumn::make('telepon_customer')
                    ->label('Telepon'),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
