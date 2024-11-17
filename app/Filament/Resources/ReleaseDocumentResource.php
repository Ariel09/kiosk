<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReleaseDocumentResource\Pages;
use App\Filament\Resources\ReleaseDocumentResource\RelationManagers;
use App\Models\Document;
use App\Models\DocumentRequest;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ReleaseDocumentResource extends Resource
{
    protected static ?string $model = DocumentRequest::class;

    // public static function canViewAny(): bool
    // {
    //     return Auth::user()->can('view_release::document');
    // }

    // public static function canCreate(): bool
    // {
    //     return Auth::user()->can('create_release::document');
    // }

    public static function getLabel(): string
    {
        return 'Release Document';  // Customize the label
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Registrar';  // Assign a custom navigation group
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
                        'decline' => 'Decline',
                    ])
                    ->required()
                    ->default('on_hold'),
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
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label('Transaction ID'),
                TextColumn::make('queue_number')
                    ->label('Queue Number'),
                TextColumn::make('status')
                    ->label('Document Status')
                    ->color(fn($state) => match ($state) {
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('status', 'paid');
            });
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
            'index' => Pages\ListReleaseDocuments::route('/'),
            'create' => Pages\CreateReleaseDocument::route('/create'),
            'edit' => Pages\EditReleaseDocument::route('/{record}/edit'),
        ];
    }
}
