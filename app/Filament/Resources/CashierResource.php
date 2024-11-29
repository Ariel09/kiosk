<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CashierResource\Pages;
use App\Filament\Resources\CashierResource\RelationManagers;
use App\Models\Cashier;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Date;

class CashierResource extends Resource
{
    protected static ?string $model = Cashier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('queue_number')->label('Queue Number'),
                TextColumn::make('user.student.full_name')
                    ->label('Full Name')
                    ->formatStateUsing(function ($record) {
                        $student = $record->user?->student;

                        if ($student) {
                            return $student->full_name; // Access the accessor from the Student model
                        }

                        return 'N/A'; // Fallback if no student is associated
                    }),
                TextColumn::make('amount')->label('Amount')->money('Php'), // Adjust the currency if needed
            ])
            ->actions([
                Action::make('view')
                    ->label('View Details')
                    ->modalHeading('Document Request Details')
                    ->modalWidth('lg')
                    ->form([
                        Select::make('status')
                            ->label('Status')
                            ->default('paid')
                            ->reactive()
                            ->options([
                                'paid' => 'Paid',
                                'decline' => 'Decline',
                            ]),
                        TextInput::make('amount')
                            ->label('Amount')
                            ->default(fn($record) => $record->amount)
                            ->numeric(),
                        // Use a Select or TextInput to display document names
                        Repeater::make('documentRequestDocuments') // Field to display/edit documents
                            ->label('Documents')
                            ->relationship('documentRequestDocuments') // Assuming there is a relationship
                            ->schema([
                                Select::make('document_id')
                                    ->label('Document Name')
                                    ->relationship('document', 'document_name'),
                                TextInput::make('quantity')
                                    ->label('Quantity'),
                                TextInput::make('price')
                                    ->label('Total Price')
                                    ->disabled(), // Calculated automatically
                            ])
                            ->mutateRelationshipDataBeforeFillUsing(function ($data) {
                                // Format the data as needed, e.g., transforming pivot fields
                                return $data->map(function ($record) {
                                    return [
                                        'document_id' => $record->document_id,
                                        'quantity' => $record->quantity,
                                        'price' => $record->price,
                                    ];
                                });
                            })
                            ->columns(3), // Arranges fields in a table-like structure
                        TextInput::make('remarks')
                            ->label('Remarks')
                            ->visible(fn($get) => $get('status') === 'decline') // Show this field only if status is 'disapproved'
                            ->required(fn($get) => $get('status') === 'decline'), // Make remarks required if status is 'disapproved'
                    ])
                    ->action(function ($record, $data) {
                        if ($data['status'] === 'paid') {
                            $record->update([
                                'status' => $data['status'],
                                'amount' => $data['amount'],
                                'payment_date' => now()->format('Y-m-d'), // Sets the date in "YYYY-MM-DD" format

                            ]);
                        } else {
                            $record->update([
                                'status' => $data['status'],
                                'amount' => $data['amount'],
                                'remarks' => $data['remarks'],

                            ]);
                        }
                    }),
            ])
            ->defaultSort('queue_number', 'asc');
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
            'index' => Pages\ListCashiers::route('/'),
            'view' => Pages\ViewCashier::route('/{record}'),
        ];
    }
}
