<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\ScheduledTransaction;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransactionMail extends Mailable
{
    use Queueable, SerializesModels;
    public $transaction;
    public $subject;
    public $greeting;
    public $content;
    /**
     * Create a new message instance.
     */
    public function __construct(ScheduledTransaction $transaction)
    {
        $this->transaction = $transaction;

        // Customize these based on your needs
        $this->subject = 'Transaction Update: ' . ucfirst($transaction->status);
        $this->greeting = 'Hello ' . $transaction->user->name . ',';

        $this->content = [
            'Transaction Type' => ucfirst($transaction->type),
            'Amount' => config('app.currency_symbol') . number_format($transaction->amount, 2),
            'Status' => ucfirst($transaction->status),
            'Last Run' => $transaction->last_run_at ? $transaction->last_run_at->format('M j, Y g:i A') : 'Not yet run',
            'Next Scheduled' => $transaction->next_run_at ? $transaction->next_run_at->format('M j, Y g:i A') : 'Not scheduled'
        ];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.transaction-notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
