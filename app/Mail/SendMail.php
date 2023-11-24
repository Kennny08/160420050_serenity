<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $type;
    public $details;
    public $fileName;
    /**
     * Create a new message instance.
     *
     * @return void
     * 
     * 
     */
    public function __construct($type, $details, $fileName = '')
    {
        $this->type = $type;
        $this->details = $details;
        $this->fileName = $fileName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->type == 'batalreservasiadmin') {
            return $this->subject('Pembatalan Reservasi dari Admin')->view('mail.batalreservasiadminmail');
        }
        
    }
}
