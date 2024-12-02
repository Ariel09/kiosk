<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CashierResource\Pages;
use App\Models\Cashier;
use App\Models\Document;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CashierResource extends Resource
{
    protected static ?string $model = Cashier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('queue_number')
                    ->label('Queue Number'),
                TextColumn::make('user.student.full_name')
                    ->label('Full Name')
                    ->formatStateUsing(function ($record) {
                        $student = $record->user?->student;

                        return $student ? $student->full_name : 'N/A';
                    }),
                TextColumn::make('amount')
                    ->label('Total Amount')
                    ->money('Php'),
            ])
            ->actions([
                Action::make('view')
                    ->label('View Details')
                    ->modalHeading('Document Request Details')
                    ->modalWidth('lg')
                    ->form(function ($record) {
                        return [
                            Select::make('status')
                                ->label('Status')
                                ->default('paid')
                                ->reactive()
                                ->options([
                                    'paid' => 'Paid',
                                    'decline' => 'Decline',
                                ]),
                                TextInput::make('amount'),
                            Select::make('documents')
                                ->label('Documents')
                                ->multiple()
                                ->options(Document::pluck('document_name', 'id')->toArray()) // List all documents
                                ->default(fn($record) => $record->documents->pluck('id')->toArray()), // Preselect existing documents

                            TextInput::make('remarks')
                                ->label('Remarks')
                                ->default($record->remarks ?? null)
                                ->visible(fn($get) => $get('status') === 'decline')
                                ->required(fn($get) => $get('status') === 'decline'),
                        ];
                    })
                    ->action(function ($record, $data) {
                        // Calculate the total amount
                        $selectedDocuments = Document::whereIn('id', $data['documents'])->get();
                        $totalAmount = $selectedDocuments->sum('price');

                        // Update the main record
                        $record->update([
                            'status' => $data['status'],
                            'amount' => $totalAmount,
                            'remarks' => $data['remarks'] ?? null,
                            'payment_date' => $data['status'] === 'paid' ? now() : null,
                        ]);

                        // Sync selected documents with the pivot table
                        $updatedDocuments = [];
                        foreach ($data['documents'] as $documentId) {
                            $document = Document::find($documentId);
                            $updatedDocuments[$documentId] = [
                                'quantity' => 1, // Default quantity
                                'price' => $document->price ?? 0,
                            ];
                        }
                        $record->documents()->sync($updatedDocuments);
                    }),
            ])
            ->defaultSort('queue_number', 'asc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCashiers::route('/'),
            'view' => Pages\ViewCashier::route('/{record}'),
        ];
    }
}
