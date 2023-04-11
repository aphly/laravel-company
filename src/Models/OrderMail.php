<?php

namespace Aphly\LaravelCompany\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Aphly\Laravel\Models\Model;

class OrderMail extends Model
{
    use HasFactory;
    protected $table = 'company_order_mail';
    protected $primaryKey = 'id';
    //public $timestamps = false;

    protected $fillable = [
        'name','sort','status','default'
    ];


}
