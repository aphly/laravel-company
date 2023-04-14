<?php

namespace Aphly\LaravelCompany\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Aphly\Laravel\Models\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'company_order';
    protected $primaryKey = 'order_id';
    protected $keyType = 'string';
    public $incrementing = false;
    //public $timestamps = false;

    protected $fillable = [
        'order_id','email','firstname','lastname','country','city','address','postcode',
        'telephone','price','currency','add_time'
    ];


}
