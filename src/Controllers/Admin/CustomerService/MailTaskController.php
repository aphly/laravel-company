<?php

namespace Aphly\LaravelCompany\Controllers\Admin\CustomerService;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Models\Breadcrumb;
use Aphly\Laravel\Models\Manager;
use Aphly\Laravel\Models\UploadFile;
use Aphly\LaravelCompany\Controllers\Admin\Controller;
use Aphly\LaravelCompany\Jobs\CustomerServiceEmail;
use Aphly\LaravelCompany\Mail\Order\All;
use Aphly\LaravelCompany\Models\CustomerService\Mail;
use Aphly\LaravelCompany\Models\CustomerService\MailTask;
use Aphly\LaravelCompany\Models\CustomerService\MailTaskOrder;
use Aphly\LaravelCompany\Models\CustomerService\MailTemplate;
use Aphly\LaravelCompany\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class MailTaskController extends Controller
{
    public $index_url='/company_admin/customer_service/mail_task/index';

    private $currArr = ['name'=>'邮件','key'=>'customer_service/mail_task'];

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
        $res['breadcrumb'] = Breadcrumb::render([
            ['name'=>$this->currArr['name'].'任务','href'=>$this->index_url]
        ]);
        return $this->makeView('laravel-company::admin.customer_service.mail_task.index',['res'=>$res]);
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
            $res['breadcrumb'] = Breadcrumb::render([
                ['name'=>$this->currArr['name'].'任务','href'=>$this->index_url],
                ['name'=>'新增','href'=>'/company_admin/'.$this->currArr['key'].'/add']
            ]);
            return $this->makeView('laravel-company::admin.customer_service.mail_task.form',['res'=>$res]);
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
            $res['breadcrumb'] = Breadcrumb::render([
                ['name'=>$this->currArr['name'].'任务','href'=>$this->index_url],
                ['name'=>'编辑','href'=>'/admin/'.$this->currArr['key'].'/edit?id='.$res['info']->id]
            ]);
            return $this->makeView('laravel-company::admin.customer_service.mail_task.form',['res'=>$res]);
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
        $res['breadcrumb'] = Breadcrumb::render([
            ['name'=>$this->currArr['name'].'任务','href'=>$this->index_url],
            ['name'=>'订单','href'=>'/admin/'.$this->currArr['key'].'/order?id='.$res['info']->id]
        ]);
        return $this->makeView('laravel-company::admin.customer_service.mail_task.order',['res'=>$res]);
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
            $path = storage_path() . '/app/' . $file_path;
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
            throw new ApiException(['code' => 0, 'msg' => 'success', 'data' => ['redirect' => '/company_admin/customer_service/mail_task/order?id='.$res['info']->id]]);
        }else{
            $res['breadcrumb'] = Breadcrumb::render([
                ['name'=>$this->currArr['name'].'任务','href'=>$this->index_url],
                ['name'=>'导入','href'=>'/admin/'.$this->currArr['key'].'/import?id='.$res['info']->id]
            ]);
            return $this->makeView('laravel-company::admin.customer_service.mail_task.import',['res'=>$res]);
        }
    }

    public function send(Request $request){
        $res['info'] = MailTask::where('id', $request->query('id', 0))->dataPerm(Manager::_uuid())->with('mailTemplate')->with('mail')->firstOrError();
        $res['list'] = MailTaskOrder::where('mail_task_id',$res['info']->id)->with('order')->get();
        if($res['list']->count() && $res['info']->status==1){
            $res['info']->update(['status'=>2]);
            $columns1 = Schema::getColumnListing('company_order');
            foreach ($res['list'] as $val){
                $val->mail_task = $res['info']->toArray();
                $arr = $val->toArray();
                foreach ($columns1 as $v){
                    $arr['mail_task']['mail_template']['template'] = str_replace('{'.$v.'}',$val->order->{$v},$arr['mail_task']['mail_template']['template']);
                }
                CustomerServiceEmail::dispatch($arr, new All($arr));
            }
            throw new ApiException(['code' => 0, 'msg' => 'success']);
        }else{
            throw new ApiException(['code' => 1, 'msg' => 'fail']);
        }
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
