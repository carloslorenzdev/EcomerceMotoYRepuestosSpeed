<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusTitles = [
            'pendiente' => 'Pedido Registrado',
            'procesando' => 'Pedido en Preparación',
            'enviado' => 'Pedido Enviado',
            'entregado' => 'Pedido Entregado',
        ];
        $title = $statusTitles[$this->order->shipping_status] ?? 'Actualización del Pedido';

        return new Envelope(
            subject: $title . ' #' . $this->order->id . ' - MotoSpeed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
