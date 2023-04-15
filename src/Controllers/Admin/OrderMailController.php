<?php

namespace Aphly\LaravelCompany\Controllers\Admin;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Models\Manager;
use Aphly\Laravel\Models\Role;
use Aphly\Laravel\Models\UploadFile;
use Aphly\LaravelCompany\Models\Mail;
use Aphly\LaravelCompany\Models\MailTemplate;
use Aphly\LaravelCompany\Models\Order;
use Aphly\LaravelCompany\Models\OrderMail;
use Illuminate\Http\Request;

class OrderMailController extends Controller
{
    public $index_url='/company_admin/order_mail/index';

    public function index(Request $request)
    {
        $res['search']['from_address'] = $request->query('from_address',false);
        $res['search']['string'] = http_build_query($request->query());
        $levelIds = (new Role)->hasLevelIds(session('role_id'));
        $res['list'] = OrderMail::when($res['search']['from_address'],
                function($query,$from_address) {
                    return $query->where('from_address', 'like', '%'.$from_address.'%');
                })
            ->dataPerm(Manager::_uuid(),$levelIds)
            ->orderBy('id','desc')
            ->Paginate(config('admin.perPage'))->withQueryString();
        return $this->makeView('laravel-company::admin.order_mail.index',['res'=>$res]);
    }

    public function add(Request $request)
    {
        if($request->isMethod('post')){
            if($request->hasFile('xlsx')) {
                $input = $request->all();
                $input['uuid'] = Manager::user()->uuid;
                $input['level_id'] = Manager::user()->level_id;
                $file_path = (new UploadFile(2, ['xlsx']))->upload($request->file('xlsx'), 'private/company/order');
                $path = storage_path().'\\app\\'.$file_path;
                if(file_exists($path)){
                    $arr = pathinfo($path);
                    $config   = ['path' => $arr['dirname']];
                    $excel    = new \Vtiful\Kernel\Excel($config);
                    $excel->openFile($arr['basename'])->openSheet();
                    $i = 1;
                    while (($row = $excel->nextRow()) !== NULL) {
                        if ($i == 1) {
                            $i++;
                            continue;
                        } else {
                            if ($row[0]) {
                                $input['order_id'] = $row[0];
                                $info = Order::where('order_id',$input['order_id'])->firstToArray();
                                if(!$info){
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
                                OrderMail::create($input);
                            }
                        }
                    }
                    $excel->close();
                    @unlink($path);
                }
                throw new ApiException(['code'=>0,'msg'=>'success','data'=>['redirect'=>$this->index_url]]);
            }else{
                throw new ApiException(['code'=>1,'msg'=>'fail']);
            }
        }else{
            $res['info'] = OrderMail::where('id',$request->query('id',0))->firstOrNew();
            $res['mail'] = Mail::dataPerm(Manager::user()->uuid)->where('status',1)->get();
            $res['mail_template'] = MailTemplate::dataPerm(Manager::user()->uuid)->where('status',1)->get();
            return $this->makeView('laravel-company::admin.order_mail.form',['res'=>$res]);
        }
    }

    public function edit(Request $request)
    {
        $res['info'] = OrderMail::where('id',$request->query('id',0))->dataPerm(Manager::_uuid())->firstOrError();
        $res['mail'] = Mail::dataPerm(Manager::user()->uuid)->where('status',1)->get();
        $res['mail_template'] = MailTemplate::dataPerm(Manager::user()->uuid)->where('status',1)->get();
        if($request->isMethod('post')){
            $res['info']->update($request->all());
            throw new ApiException(['code' => 0, 'msg' => 'success', 'data' => ['redirect' => $this->index_url]]);
        }else{
            return $this->makeView('laravel-company::admin.order_mail.form',['res'=>$res]);
        }
    }

    public function del(Request $request)
    {
        $query = $request->query();
        $redirect = $query?$this->index_url.'?'.http_build_query($query):$this->index_url;
        $post = $request->input('delete');
        if(!empty($post)){
            OrderMail::whereIn('id',$post)->delete();
            throw new ApiException(['code'=>0,'msg'=>'操作成功','data'=>['redirect'=>$redirect]]);
        }
    }

}
