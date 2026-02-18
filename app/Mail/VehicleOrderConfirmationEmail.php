<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Order;
use App\Models\TeslaCar;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VehicleOrderConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $order;
    public $car;
    public $quantity;
    public $total;
    public $newBalance;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Order $order, TeslaCar $car, $quantity, $total, $newBalance)
    {
        $this->user = $user;
        $this->order = $order;
        $this->car = $car;
        $this->quantity = $quantity;
        $this->total = $total;
        $this->newBalance = $newBalance;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vehicle Order Confirmation - TESLA',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.vehicle-order-confirmation',
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
