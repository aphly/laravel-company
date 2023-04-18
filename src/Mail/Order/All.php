<?php

namespace Aphly\LaravelCompany\Mail\Order;

use Aphly\LaravelCompany\Models\MailTaskOrder;
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
        Config::set('mail.mailers.smtp.host',$arr['mail_task']['mail']['host']);
        Config::set('mail.mailers.smtp.port',$arr['mail_task']['mail']['port']);
        Config::set('mail.mailers.smtp.encryption',$arr['mail_task']['mail']['encryption']);
        Config::set('mail.mailers.smtp.username',$arr['mail_task']['mail']['username']);
        Config::set('mail.mailers.smtp.password',$arr['mail_task']['mail']['password']);
        $this->template = $arr['mail_task']['mail_template']['template'];
        MailTaskOrder::where('id',$arr['mail_task']['id'])->update(['status'=>2]);
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
