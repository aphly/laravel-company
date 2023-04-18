<?php

namespace Aphly\LaravelCompany\Controllers\Admin\CustomerService;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Models\Manager;
use Aphly\LaravelCompany\Controllers\Admin\Controller;
use Aphly\LaravelCompany\Models\CustomerService\MailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class MailTemplateController extends Controller
{
    public $index_url='/company_admin/customer_service/mail_template/index';

    public function index(Request $request)
    {
        $res['search']['name'] =  $request->query('name',false);
        $res['search']['string'] = http_build_query($request->query());
        $res['list'] = MailTemplate::when($res['search']['name'],
                            function($query,$val) {
                                return $query->where('name', 'like', '%'.$val.'%');
                            })
                        ->dataPerm(Manager::_uuid(),$this->roleLevelIds)
                        ->orderBy('id','desc')
                        ->Paginate(config('admin.perPage'))->withQueryString();
        return $this->makeView('laravel-company::admin.customer_service.mail_template.index',['res'=>$res]);
    }

    public function add(Request $request)
    {
        if($request->isMethod('post')){
            $input = $request->all();
            $input['uuid'] = Manager::user()->uuid;
            $input['level_id'] = Manager::user()->level_id;
            MailTemplate::create($input);
            throw new ApiException(['code'=>0,'msg'=>'success','data'=>['redirect'=>$this->index_url]]);
        }else{
            $res['info'] = MailTemplate::where('id',$request->query('id',0))->firstOrNew();
            $res['columns'] = Schema::getColumnListing('company_order');
            $res['columns'] = implode(',',$res['columns']);
            return $this->makeView('laravel-company::admin.customer_service.mail_template.form',['res'=>$res]);
        }
    }

    public function edit(Request $request)
    {
        $res['info'] = MailTemplate::where('id',$request->query('id',0))->dataPerm(Manager::_uuid())->firstOrError();
        if($request->isMethod('post')){
            $res['info']->update($request->all());
            throw new ApiException(['code' => 0, 'msg' => 'success', 'data' => ['redirect' => $this->index_url]]);
        }else{
            $res['columns'] = Schema::getColumnListing('company_order');
            $res['columns'] = implode(',',$res['columns']);
            return $this->makeView('laravel-company::admin.customer_service.mail_template.form',['res'=>$res]);
        }
    }

    public function del(Request $request)
    {
        $query = $request->query();
        $redirect = $query?$this->index_url.'?'.http_build_query($query):$this->index_url;
        $post = $request->input('delete');
        if(!empty($post)){
            MailTemplate::whereIn('id',$post)->delete();
            throw new ApiException(['code'=>0,'msg'=>'操作成功','data'=>['redirect'=>$redirect]]);
        }
    }

}
