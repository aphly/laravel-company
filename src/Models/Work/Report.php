<?php

namespace Aphly\LaravelCompany\Models\Work;

use Aphly\Laravel\Models\UploadFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Aphly\Laravel\Models\Model;

class Report extends Model
{
    use HasFactory;
    protected $table = 'company_work_report';
    protected $primaryKey = 'id';
    //public $timestamps = false;

    protected $fillable = [
        'uuid','level_id','content','plan','upload_file_id','status'
    ];

    function uploadFile(){
        return $this->hasOne(UploadFile::class,'id','upload_file_id');
    }
}
