<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentRequestResource\Pages;
use App\Filament\Resources\DocumentRequestResource\RelationManagers;
use App\Models\DocumentRequest;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class DocumentRequestResource extends Resource
{
    protected static ?string $model = DocumentRequest::class;

        public static function canViewAny(): bool
{

    return Auth::user()->can('view_document_request');
}

public static function canCreate(): bool
{
    return Auth::user()->can('create_document_request');
}

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('user_id')
                ->label('Student')
                ->relationship('user', 'name')
                ->required(),

            Forms\Components\Select::make('document_type')
                ->label('Document Type')
                ->options([
                    'Form 138' => 'Form 138',
                    'Form 137' => 'Form 137',
                    'Good Moral' => 'Good Moral',
                    'Diploma' => 'Diploma',
                    'TOR' => 'Transcript of Records (TOR)',
                    'CTC' => 'Certified True Copy (CTC)',
                    'COE' => 'Certificate of Enrollment (COE)',
                ])
                ->required(),

            Forms\Components\TextInput::make('queue_number')
                ->label('Queue Number')
                ->disabled(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'on_hold' => 'On Hold',
                    'processing' => 'Processing',
                    'completed' => 'Completed',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Student Name'),
                Tables\Columns\TextColumn::make('document_type')->label('Document Type'),
                Tables\Columns\TextColumn::make('queue_number')->label('Queue Number'),
                Tables\Columns\TextColumn::make('status')->label('Status'),
                Tables\Columns\TextColumn::make('created_at')->label('Requested On')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'on_hold' => 'On Hold',
                    'processing' => 'Processing',
                    'completed' => 'Completed',
                ]),
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
            'index' => Pages\ListDocumentRequests::route('/'),
            'create' => Pages\CreateDocumentRequest::route('/create'),
            'edit' => Pages\EditDocumentRequest::route('/{record}/edit'),
        ];
    }
    protected function getTableActions(): array
{
    return [
        Action::make('Confirm Payment')
            ->action(function (DocumentRequest $record) {
                $record->status = 'processing';
                $record->save();

                // Send email notification to the student
                 //Mail::to($record->user->email)->send(new DocumentStatusNotification($record));

                $this->notify('success', 'Payment confirmed, document is processing.');
            })
            ->requiresConfirmation(),
    ];
}
}
