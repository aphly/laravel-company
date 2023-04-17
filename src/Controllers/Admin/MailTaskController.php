<?php

namespace Aphly\LaravelCompany\Controllers\Admin;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Models\Manager;
use Aphly\Laravel\Models\UploadFile;
use Aphly\LaravelCompany\Models\Mail;
use Aphly\LaravelCompany\Models\MailTask;
use Aphly\LaravelCompany\Models\MailTaskOrder;
use Aphly\LaravelCompany\Models\MailTemplate;
use Aphly\LaravelCompany\Models\Order;
use Illuminate\Http\Request;

class MailTaskController extends Controller
{
    public $index_url='/company_admin/mail_task/index';

    public function index(Request $request)
    {
        $res['search']['id'] = $request->query('id',false);
        $res['search']['string'] = http_build_query($request->query());
        $res['list'] = MailTask::when($res['search']['id'],
                        function($query,$val) {
                            return $query->where('id',$val);
                        })
                    ->dataPerm(Manager::_uuid(),$this->roleLevelIds)
                    ->with('mailTemplate')->with('mail')
                    ->orderBy('id','desc')
                    ->Paginate(config('admin.perPage'))->withQueryString();
        return $this->makeView('laravel-company::admin.mail_task.index',['res'=>$res]);
    }

    public function add(Request $request)
    {
        if($request->isMethod('post')){
            $input = $request->all();
            $input['uuid'] = Manager::user()->uuid;
            $input['level_id'] = Manager::user()->level_id;
            MailTask::create($input);
            throw new ApiException(['code'=>0,'msg'=>'success','data'=>['redirect'=>$this->index_url]]);
        }else{
            $res['info'] = MailTask::where('id',$request->query('id',0))->firstOrNew();
            $res['mail'] = Mail::dataPerm(Manager::user()->uuid)->where('status',1)->get();
            $res['mail_template'] = MailTemplate::dataPerm(Manager::user()->uuid)->where('status',1)->get();
            return $this->makeView('laravel-company::admin.mail_task.form',['res'=>$res]);
        }
    }

    public function edit(Request $request)
    {
        $res['info'] = MailTask::where('id',$request->query('id',0))->dataPerm(Manager::_uuid())->firstOrError();
        $res['mail'] = Mail::dataPerm(Manager::user()->uuid)->where('status',1)->get();
        $res['mail_template'] = MailTemplate::dataPerm(Manager::user()->uuid)->where('status',1)->get();
        if($request->isMethod('post')){
            $res['info']->update($request->all());
            throw new ApiException(['code' => 0, 'msg' => 'success', 'data' => ['redirect' => $this->index_url]]);
        }else{
            return $this->makeView('laravel-company::admin.mail_task.form',['res'=>$res]);
        }
    }

    public function order(Request $request)
    {
        $res['search']['order_id'] = $request->query('order_id',false);
        $res['search']['string'] = http_build_query($request->query());
        $res['info'] = MailTask::where('id',$request->query('id',0))->dataPerm(Manager::_uuid())->firstOrError();
        $res['list'] = MailTaskOrder::when($res['search']['order_id'],
                            function($query,$val) {
                                return $query->where('order_id',$val);
                            }
                        )->where('mail_task_id',$res['info']->id)
                        ->with('order')
                        ->orderBy('id','desc')
                        ->Paginate(config('admin.perPage'))->withQueryString();
        return $this->makeView('laravel-company::admin.mail_task.order',['res'=>$res]);
    }

    public function import(Request $request)
    {
        $res['info'] = MailTask::where('id', $request->query('id', 0))->dataPerm(Manager::_uuid())->firstOrError();
        if($request->isMethod('post')) {
            if(!$request->hasFile('xlsx')){
                throw new ApiException(['code' => 1, 'msg' => 'no file']);
            }
            MailTaskOrder::where('mail_task_id', $res['info']->id)->delete();
            $input['mail_task_id'] = $res['info']->id;
            $input['status'] = 1;
            $file_path = (new UploadFile(5, ['xlsx']))->upload($request->file('xlsx'), 'private/company/order');
            $path = storage_path() . '\\app\\' . $file_path;
            if (file_exists($path)) {
                $arr = pathinfo($path);
                $config = ['path' => $arr['dirname']];
                $excel = new \Vtiful\Kernel\Excel($config);
                $excel->openFile($arr['basename'])->openSheet();
                $i = 1;
                while (($row = $excel->nextRow()) !== NULL) {
                    if ($i == 1) {
                        $i++;
                        continue;
                    } else {
                        $input['order_id'] = $row[0];
                        if ($input['order_id']) {
                            $info = Order::where('order_id', $input['order_id'])->firstToArray();
                            if (!$info) {
                                $input['add_time'] = $row[1];
                                $input['email'] = $row[2];
                                $input['currency'] = $row[3];
                                $input['price'] = $row[4];
                                $input['firstname'] = $row[5];
                                $input['lastname'] = $row[6];
                                $input['country'] = $row[7];
                                $input['zone'] = $row[8];
                                $input['city'] = $row[9];
                                $input['address'] = $row[10];
                                $input['postcode'] = $row[11];
                                $input['telephone'] = $row[12];
                                Order::create($input);
                            }
                            MailTaskOrder::create($input);
                        }
                    }
                }
                $excel->close();
                @unlink($path);
            }
            throw new ApiException(['code' => 0, 'msg' => 'success', 'data' => ['redirect' => $this->index_url]]);
        }else{
            return $this->makeView('laravel-company::admin.mail_task.import',['res'=>$res]);
        }
    }

    public function send(Request $request){
        $res['info'] = MailTask::where('id', $request->query('id', 0))->dataPerm(Manager::_uuid())->firstOrError();
        $res['info']->update(['status'=>2]);
        throw new ApiException(['code' => 0, 'msg' => 'success', 'data' => ['redirect' => $this->index_url]]);
    }

    public function del(Request $request)
    {
        $query = $request->query();
        $redirect = $query?$this->index_url.'?'.http_build_query($query):$this->index_url;
        $post = $request->input('delete');
        if(!empty($post)){
            MailTask::whereIn('id',$post)->delete();
            MailTaskOrder::whereIn('mail_task_id',$post)->delete();
            throw new ApiException(['code'=>0,'msg'=>'操作成功','data'=>['redirect'=>$redirect]]);
        }
    }

}
