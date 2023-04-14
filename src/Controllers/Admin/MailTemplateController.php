<?php

namespace Aphly\LaravelCompany\Controllers\Admin;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Models\Manager;
use Aphly\Laravel\Models\Role;
use Aphly\LaravelCompany\Models\MailTemplate;
use Illuminate\Http\Request;

class MailTemplateController extends Controller
{
    public $index_url='/company_admin/mail_template/index';

    public function index(Request $request)
    {
        $res['search']['name'] =  $request->query('name',false);
        $res['search']['string'] = http_build_query($request->query());
        $levelIds = (new Role)->hasLevelIds(session('role_id'));
        $res['list'] = MailTemplate::when($res['search']['name'],
                            function($query,$val) {
                                return $query->where('name', 'like', '%'.$val.'%');
                            })
                        ->dataPerm(Manager::_uuid(),$levelIds)
                        ->orderBy('id','desc')
                        ->Paginate(config('admin.perPage'))->withQueryString();
        return $this->makeView('laravel-company::admin.mail_template.index',['res'=>$res]);
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
            $res['info'] = MailTemplate::where('id',$request->query('id',0))->dataPerm(Manager::_uuid())->firstOrNew();
            return $this->makeView('laravel-company::admin.mail_template.form',['res'=>$res]);
        }
    }

    public function edit(Request $request)
    {
        $res['info'] = MailTemplate::where('id',$request->query('id',0))->dataPerm(Manager::_uuid())->firstOrError();
        if($request->isMethod('post')){
            $res['info']->update($request->all());
            throw new ApiException(['code' => 0, 'msg' => 'success', 'data' => ['redirect' => $this->index_url]]);
        }else{
            return $this->makeView('laravel-company::admin.mail_template.form',['res'=>$res]);
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
