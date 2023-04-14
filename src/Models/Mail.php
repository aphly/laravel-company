<?php

namespace Aphly\LaravelCompany\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Aphly\Laravel\Models\Model;

class Mail extends Model
{
    use HasFactory;
    protected $table = 'company_mail';
    protected $primaryKey = 'id';
    //public $timestamps = false;

    protected $fillable = [
        'uuid','level_id','host','port','encryption','username','password','from_address','from_name','status'
    ];


}
