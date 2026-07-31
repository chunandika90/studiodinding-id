<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), 'StudioDinding')
                    ->subject('Notifikasi dari Website (' . $this->data['contact_type'] . ')')
                    ->view('emails.template_email')
                    ->with('data', $this->data);
    }
}
