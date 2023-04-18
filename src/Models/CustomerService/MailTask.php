<?php

namespace Aphly\LaravelCompany\Models\CustomerService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Aphly\Laravel\Models\Model;

class MailTask extends Model
{
    use HasFactory;
    protected $table = 'company_customer_service_mail_task';
    protected $primaryKey = 'id';
    //public $timestamps = false;

    protected $fillable = [
        'mail_template_id','mail_id','level_id','uuid','status'
    ];

    public function mailTemplate(){
        return $this->hasOne(MailTemplate::class,'id','mail_template_id');
    }

    public function mail(){
        return $this->hasOne(Mail::class,'id','mail_id');
    }

}
