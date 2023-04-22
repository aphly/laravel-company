<?php

namespace Aphly\LaravelCompany\Mail\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class All extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $arr;

    public $template;

    public function __construct($arr)
    {
        $this->arr = $arr;
        $this->template = $arr['mail_task']['mail_template']['template'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->arr['mail_task']['mail_template']['name'])
            ->from($this->arr['mail_task']['mail']['from_address'], $this->arr['mail_task']['mail']['from_name'])
            ->view('laravel-company::mail.order.all');
    }
}
