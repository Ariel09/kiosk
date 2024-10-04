<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Document;
use App\Models\DocumentRequest;  // Ensure this line is present
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PaymentResource extends Resource
{
    protected static ?string $model = DocumentRequest::class;  // Ensure the model is properly set

    public static function canViewAny(): bool
    {
        return Auth::user()->can('view_payment');
    }

    public static function canCreate(): bool
    {
        return Auth::user()->can('create_payment');
    }

    public static function getLabel(): string
    {
        return 'Payment';  // Customize the label
    }

    public static function getPluralLabel(): string
    {
        return 'Payments';  // Customize the plural label
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Cashier';  // Assign a custom navigation group
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('status')
                    ->options([
                        'on_hold' => 'On Hold',
                        'paid' => 'Paid',
                    ])
                    ->required()
                    ->default('paid'),
                Select::make('document_id')
    ->label('Select Document')
    ->required()
    ->options(Document::all()->pluck('document_name', 'id')) // Fetch documents and map to key-value pairs
    ->placeholder('Choose a document'), // Optional: Placeholder text
                TextInput::make('amount')
                    ->numeric()
                    ->required(),
                DatePicker::make('payment_date')
                    ->required()
                    ->default(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            //->query(fn (Builder $query) => $query->where('status', 'on_hold'))
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label('Transaction ID'),
                TextColumn::make('queue_number')
                    ->label('Queue Number'),
                TextColumn::make('status')
                    ->label('Document Status')
                    ->color(fn ($state) => match ($state) {
                        'on_hold' => 'yellow',
                        'paid' => 'green',
                        'released' => 'blue',
                        default => 'gray',
                    }),
                TextColumn::make('name')
                    ->label('Student'),
                TextColumn::make('amount')
                    ->label('Payment Amount')
                    ->sortable()
                    ->money('php', true),
                TextColumn::make('payment_date')
                    ->label('Payment Date')
                    ->copyable()
                    ->searchable(),
            ])
            ->actions([
            Tables\Actions\EditAction::make()
                ->label('Process'),
            ])
            ->headerActions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) {
            return $query->where('status', 'on_hold');
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            //'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
