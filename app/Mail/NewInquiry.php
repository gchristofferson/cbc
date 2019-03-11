<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewInquiry extends Mailable
{
    use Queueable, SerializesModels;

    public $inquiry;
    public $user;
    public $attachment_links;

    /**
     * Create a new message instance.
     * @param $inquiry
     * @param $user
     * @param $attachment_links
     * @return void
     */
    public function __construct($inquiry, $user, $attachment_links)
    {
        //
        $this->inquiry = $inquiry;
        $this->user = $user;
        $this->attachment_links = $attachment_links;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.new-inquiry');
    }
}
