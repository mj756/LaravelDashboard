<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Classes\EmailDetail;
class MyMail extends Mailable
{
    use Queueable, SerializesModels;
    protected EmailDetail $detail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmailDetail $detail)
    {
        $this->detail=$detail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->detail->subject)
        ->view($this->detail->viewName)->with($this->detail->viewPayload);
    }
}
