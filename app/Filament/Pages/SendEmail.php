<?php

namespace App\Filament\Pages;

use App\Models\Email;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Mail;

class SendEmail extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.send-email';
    protected static ?string $navigationLabel = 'Send Email';
    public $emailData = [];

    public function sendEmail()
    {
        Mail::raw($this->emailData['body'], function ($message) {
            $message->to($this->emailData['to'])
                ->subject($this->emailData['subject']);
        });

        // Save email data to the database
        Email::create($this->emailData);

        $this->notify('success', 'Email sent successfully!');
    }

}