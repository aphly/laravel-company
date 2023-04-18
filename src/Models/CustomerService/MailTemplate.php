<?php

namespace Aphly\LaravelCompany\Models\CustomerService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Aphly\Laravel\Models\Model;

class MailTemplate extends Model
{
    use HasFactory;
    protected $table = 'company_customer_service_mail_template';
    protected $primaryKey = 'id';
    //public $timestamps = false;

    protected $fillable = [
        'name','level_id','status','uuid','template','status'
    ];


}
