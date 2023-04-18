<?php

namespace Aphly\LaravelCompany\Controllers\Admin\CustomerService;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Models\Manager;

use Aphly\LaravelCompany\Controllers\Admin\Controller;
use Aphly\LaravelCompany\Models\CustomerService\Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public $index_url='/company_admin/customer_service/mail/index';

    public function index(Request $request)
    {
        $res['search']['from_address'] = $request->query('from_address',false);
        $res['search']['string'] = http_build_query($request->query());
        $res['list'] = Mail::when($res['search']['from_address'],
                function($query,$from_address) {
                    return $query->where('from_address', 'like', '%'.$from_address.'%');
                })
            ->dataPerm(Manager::_uuid(),$this->roleLevelIds)
            ->orderBy('id','desc')
            ->Paginate(config('admin.perPage'))->withQueryString();
        return $this->makeView('laravel-company::admin.customer_service.mail.index',['res'=>$res]);
    }

    public function add(Request $request)
    {
        if($request->isMethod('post')){
            $input = $request->all();
            $input['uuid'] = Manager::user()->uuid;
            $input['level_id'] = Manager::user()->level_id;
            Mail::create($input);
            throw new ApiException(['code'=>0,'msg'=>'success','data'=>['redirect'=>$this->index_url]]);
        }else{
            $res['info'] = Mail::where('id',$request->query('id',0))->firstOrNew();
            return $this->makeView('laravel-company::admin.customer_service.mail.form',['res'=>$res]);
        }
    }

    public function edit(Request $request)
    {
        $res['info'] = Mail::where('id',$request->query('id',0))->dataPerm(Manager::_uuid())->firstOrError();
        if($request->isMethod('post')){
            $res['info']->update($request->all());
            throw new ApiException(['code' => 0, 'msg' => 'success', 'data' => ['redirect' => $this->index_url]]);
        }else{
            return $this->makeView('laravel-company::admin.customer_service.mail.form',['res'=>$res]);
        }
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
