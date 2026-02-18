<?php

namespace App\Mail;

use App\Models\User;
use App\Models\InvestmentPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvestmentConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $plan;
    public $amount;
    public $newBalance;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, InvestmentPlan $plan, $amount, $newBalance)
    {
        $this->user = $user;
        $this->plan = $plan;
        $this->amount = $amount;
        $this->newBalance = $newBalance;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Investment Confirmation - Primerica Global Capital',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.investment-confirmation',
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
