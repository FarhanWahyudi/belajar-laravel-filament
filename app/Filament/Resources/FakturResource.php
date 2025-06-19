<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FakturResource\Pages;
use App\Filament\Resources\FakturResource\RelationManagers;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\Faktur;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Laravel\Prompts\select;

class FakturResource extends Resource
{
    protected static ?string $model = Faktur::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_faktur')
                    ->columnSpan(1),
                DatePicker::make('tanggal_faktur')
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 2
                    ]),
                Select::make('customer_id')
                    ->reactive()
                    ->relationship('customer', 'nama_customer')
                    ->afterStateUpdated(function ($state, callable $set) {
                        $customer = Customer::find($state);

                        if ($customer) {
                            $set('kode_customer', $customer->kode_customer);
                        }
                    }),
                TextInput::make('kode_customer')
                    ->columnSpan(1)
                    ->disabled()
                    ->dehydrated(),
                Repeater::make('details')
                    ->relationship()
                    ->schema([
                        Select::make('barang_id')
                            ->relationship('barang', 'nama_barang')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $barang = Barang::find($state);
                                if($barang) {
                                    $set('nama_barang', $barang->nama_barang);
                                    $set('harga', $barang->harga_barang);
                                }
                            }),
                            TextInput::make('nama_barang'),
                            TextInput::make('harga')
                            ->numeric(),
                            TextInput::make('qty')
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(function($state, Set $set, Get $get) {
                                $harga = $get('harga');
                                $set('hasil_qty', $harga * $state);
                            }),
                            TextInput::make('hasil_qty')
                            ->numeric(),
                            TextInput::make('diskon')
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(function($state, Set $set, Get $get) {
                                $hasilQty = $get('hasil_qty');
                                $diskon = $hasilQty * ($state / 100);
                                $total = $hasilQty - $diskon;
                                $set('subtotal', intval($total));
                            }),
                            TextInput::make('subtotal')
                            ->numeric(),
                    ])->columnSpan([
                        'default' => 1,
                        'md' => 2,
                        'lg' => 3
                    ])->live(),
                TextInput::make('ket_faktur'),
                TextInput::make('total')
                    ->placeholder(function (Set $set, Get $get) {
                        $detail = collect($get('details'))->pluck('subtotal')->sum();
                        if($detail == null) {
                            $set('total', 0);
                        } else {
                            $set('total', $detail);
                        }
                    }),
                TextInput::make('nominal_charge')
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $total = $get('total');
                        $charge = $total * ($state / 100);
                        $hasil = $total + $charge;

                        $set('total_final', $hasil);
                        $set('charge', $charge);
                    }),
                TextInput::make('charge'),
                TextInput::make('total_final'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_faktur'),
                TextColumn::make('tanggal_faktur'),
                TextColumn::make('kode_customer'),
                TextColumn::make('customer.nama_customer'),
                TextColumn::make('ket_faktur'),
                TextColumn::make('total'),
                TextColumn::make('nominal_charge'),
                TextColumn::make('charge'),
                TextColumn::make('total_final'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListFakturs::route('/'),
            'create' => Pages\CreateFaktur::route('/create'),
            'edit' => Pages\EditFaktur::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
