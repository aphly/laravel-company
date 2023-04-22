<?php

namespace Aphly\LaravelCompany\Jobs;

use Aphly\Laravel\Jobs\Email;
use Aphly\LaravelCompany\Models\CustomerService\MailTaskOrder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CustomerServiceEmail extends Email
{

    //public $tries = 1;

    public $timeout = 60;

    public $failOnTimeout = true;

    //php artisan queue:work --queue=email_vip,email

    public function __construct(
        public $arr,
        public $mail_obj,
        public $queue_priority='email',
    ){
        if($queue_priority=='email_vip'){
            $this->onQueue('email_vip');
        }else{
            $this->onQueue('email');
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->arr['order']['email'] && $this->mail_obj){
            Config::set('mail.mailers.smtp.host',$this->arr['mail_task']['mail']['host']);
            Config::set('mail.mailers.smtp.port',$this->arr['mail_task']['mail']['port']);
            Config::set('mail.mailers.smtp.encryption',$this->arr['mail_task']['mail']['encryption']);
            Config::set('mail.mailers.smtp.username',$this->arr['mail_task']['mail']['username']);
            Config::set('mail.mailers.smtp.password',$this->arr['mail_task']['mail']['password']);
            Mail::to($this->arr['order']['email'])->send($this->mail_obj);
            //Log::info(config('mail.mailers.smtp'));
            MailTaskOrder::where('id',$this->arr['mail_task']['id'])->update(['status'=>2]);
        }
    }
}
