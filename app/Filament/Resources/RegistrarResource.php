<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrarResource\Pages;
use App\Filament\Resources\RegistrarResource\RelationManagers;
use App\Models\Registrar;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class RegistrarResource extends Resource
{
    protected static ?string $model = Registrar::class;

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
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('Php'), // Adjust the currency if needed
                TextColumn::make('payment_date')
                    ->label('Payment Date'),
                TextColumn::make('released_date')
                    ->label('Release/d Date'),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'danger' => 'paid', // Red for 'paid'
                        'warning' => 'pending', // Yellow for 'pending'
                        'success' => 'released', // Green for 'released'
                    ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('view')
                    ->label('View Details')
                    ->modalHeading('Document Request Details')
                    ->modalWidth('lg')
                    ->form([
                        TextInput::make('queue_number')
                            ->label('Queue Number')
                            ->default(fn($record) => $record->queue_number)
                            ->disabled(),
                        TextInput::make('created_at')
                            ->label('Date of Request')
                            ->default(fn($record) => Carbon::parse($record->created_at)->format('Y-m-d')) // Format as 'YYYY-MM-DD'
                            ->disabled(),
                        TextInput::make('amount')
                            ->label('Amount')
                            ->default(fn($record) => $record->amount)
                            ->numeric()
                            ->disabled(),
                        // Use a Select or TextInput to display document names
                        Textarea::make('documents')
                            ->label('Documents')
                            ->default(fn($record) => $record->documents->pluck('document_name')->join(', '))
                            ->disabled(),
                        // New Field for Upcoming Friday
                        TextInput::make('released_date')
                            ->label('Released Date')
                            ->default(fn() => Carbon::now()->next(Carbon::FRIDAY)->toDateString()) // Get the next Friday
                            ->readOnly(), // Disable if you don't want the user to modify it
                        Select::make('status')
                            ->label('Status')
                            ->default(function ($record) {
                                if ($record->status === 'paid') {
                                    return 'pending'; // Default to 'pending' if the status is 'paid'
                                } elseif ($record->status === 'pending') {
                                    return 'released'; // Default to 'released' if the status is 'pending'
                                }

                                return 'pending'; // Default to 'pending' if none of the conditions are met
                            })
                            ->reactive()
                            ->options([
                                'pending' => 'For Releasing',
                                'released' => 'Released',
                            ]),
                    ])
                    ->action(function ($record, $data) {
                        Log::info('Record and Data', ['record' => $record, 'data' => $data]);

                        $record->update([
                            'status' => $data['status'],
                            'released_date' => $data['released_date'],
                        ]);
                    }),
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
            'index' => Pages\ListRegistrars::route('/'),
            'create' => Pages\CreateRegistrar::route('/create'),
            'edit' => Pages\EditRegistrar::route('/{record}/edit'),
        ];
    }
}