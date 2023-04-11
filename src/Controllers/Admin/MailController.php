<?php

namespace Aphly\LaravelCompany\Controllers\Admin;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\LaravelCompany\Models\Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public $index_url='/company_admin/mail/index';

    public function index(Request $request)
    {
        $res['search']['name'] = $name = $request->query('name',false);
        $res['search']['string'] = http_build_query($request->query());
        $res['list'] = Mail::when($name,
                function($query,$name) {
                    return $query->where('name', 'like', '%'.$name.'%');
                })
            ->orderBy('id','desc')
            ->Paginate(config('admin.perPage'))->withQueryString();
        return $this->makeView('laravel-company::admin.mail.index',['res'=>$res]);
    }

    public function form(Request $request)
    {
        $res['info'] = Mail::where('id',$request->query('id',0))->firstOrNew();
        return $this->makeView('laravel-company::admin.mail.form',['res'=>$res]);
    }

    public function save(Request $request){
        $input = $request->all();
        if($input['default']==1){
            Mail::whereRaw('1')->update(['default'=>2]);
        }
        Mail::updateOrCreate(['id'=>$request->query('id',0)],$input);
        throw new ApiException(['code'=>0,'msg'=>'success','data'=>['redirect'=>$this->index_url]]);
    }

    public function del(Request $request)
    {
        $query = $request->query();
        $redirect = $query?$this->index_url.'?'.http_build_query($query):$this->index_url;
        $post = $request->input('delete');
        if(!empty($post)){
            Mail::whereIn('id',$post)->delete();
            throw new ApiException(['code'=>0,'msg'=>'操作成功','data'=>['redirect'=>$redirect]]);
        }
    }

}
