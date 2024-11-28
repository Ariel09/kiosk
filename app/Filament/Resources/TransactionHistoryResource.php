<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionHistoryResource\Pages;
use App\Filament\Resources\TransactionHistoryResource\RelationManagers;
use App\Models\TransactionHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionHistoryResource extends Resource
{
    protected static ?string $model = TransactionHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): ?string
    {
        return 'Registrar';  // Assign a custom navigation group
    }
    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('User'),
                TextColumn::make('transaction_type')->label('Transaction Type'),
                TextColumn::make('document_type')->label('Document Type'),
                TextColumn::make('queue_number')->label('Queue Number'),
                TextColumn::make('amount')->label('Amount')->money('php', true),
                TextColumn::make('transaction_date')->label('Transaction Date')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('transaction_type')
                    ->options([
                        'release' => 'Release',
                        'payment' => 'Payment',
                    ]),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactionHistories::route('/'),
        ];
    }
}