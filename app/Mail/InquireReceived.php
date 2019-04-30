<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InquireReceived extends Mailable
{
    use Queueable, SerializesModels;
    private $inquiry;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($inquiry)
    {
        $this->inquiry = $inquiry;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.new-inquiry')->with([
            'host' => env("APP_URL"),
            'inquiry' => $this->inquiry
        ]);
    }
}
