<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ReservationSubmitted extends Mailable
{
    public array $data;
    public array $files;

    public function __construct(array $data, array $files = [])
    {
        $this->data = $data;
        $this->files = $files;
    }

    public function build()
    {
        return $this->subject('New Reservation Submitted')
            ->view('emails.reservation-submitted')
->with([
    'data' => $this->data,
    'files' => $this->files,
]);
    }
}