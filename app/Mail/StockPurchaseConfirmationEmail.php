<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Stock;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StockPurchaseConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $stock;
    public $quantity;
    public $pricePerShare;
    public $totalAmount;
    public $orderTypeText;
    public $statusText;
    public $newBalance;
    public $limitPrice;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Stock $stock, $quantity, $pricePerShare, $totalAmount, $orderTypeText, $statusText, $newBalance, $limitPrice = null)
    {
        $this->user = $user;
        $this->stock = $stock;
        $this->quantity = $quantity;
        $this->pricePerShare = $pricePerShare;
        $this->totalAmount = $totalAmount;
        $this->orderTypeText = $orderTypeText;
        $this->statusText = $statusText;
        $this->newBalance = $newBalance;
        $this->limitPrice = $limitPrice;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Stock Purchase Confirmation - Primerica Global Capital',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.stock-purchase-confirmation',
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
