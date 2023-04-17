<?php

namespace Aphly\LaravelCompany\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Aphly\Laravel\Models\Model;

class MailTaskOrder extends Model
{
    use HasFactory;
    protected $table = 'company_mail_task_order';
    protected $primaryKey = 'id';
    //public $timestamps = false;

    protected $fillable = [
        'order_id','mail_task_id','status'
    ];

    public function order(){
        return $this->hasOne(Order::class,'order_id','order_id');
    }
}
