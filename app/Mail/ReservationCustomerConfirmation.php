<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationCustomerConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;
    public array $files;

    public function __construct(array $data, array $files = [])
    {
        $this->data = $data;
        $this->files = $files;
    }

    public function build()
    {
        return $this->subject('We received your reservation request')
            ->view('emails.reservation-confirmation')
            ->with([
                'data' => $this->data,
                'files' => $this->files,
            ]);
    }
}