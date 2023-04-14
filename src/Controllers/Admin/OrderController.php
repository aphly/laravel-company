<?php

namespace Aphly\LaravelCompany\Controllers\Admin;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Models\Manager;
use Aphly\LaravelCompany\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public $index_url='/company_admin/order/index';

    public function index(Request $request)
    {
        $res['search']['email'] = $request->query('email',false);
        $res['search']['order_id'] = $request->query('order_id',false);
        $res['search']['string'] = http_build_query($request->query());

        $res['list'] = Order::when($res['search']['email'],
                    function($query,$val) {
                        return $query->where('email', 'like', '%'.$val.'%');
                    })
                ->when($res['search']['order_id'],
                    function($query,$val) {
                        return $query->where('order_id', $val);
                    })
                ->orderBy('order_id','desc')
                ->Paginate(config('admin.perPage'))->withQueryString();
        return $this->makeView('laravel-company::admin.order.index',['res'=>$res]);
    }

    public function add(Request $request)
    {
        if($request->isMethod('post')){
            $input = $request->all();
            $input['uuid'] = Manager::user()->uuid;
            $input['level_id'] = Manager::user()->level_id;
            Order::create($input);
            throw new ApiException(['code'=>0,'msg'=>'success','data'=>['redirect'=>$this->index_url]]);
        }else{
            $res['info'] = Order::where('order_id',$request->query('order_id',0))->firstOrNew();
            return $this->makeView('laravel-company::admin.order.form',['res'=>$res]);
        }
    }

    public function info(Request $request)
    {
        $res['info'] = Order::where('order_id',$request->query('order_id',0))->firstOrError();
        $res['show'] = 1;
        return $this->makeView('laravel-company::admin.order.form',['res'=>$res]);
    }

    public function edit(Request $request)
    {
        $res['info'] = Order::where('order_id',$request->query('order_id',0))->firstOrError();
        if($request->isMethod('post')){
            $res['info']->update($request->all());
            throw new ApiException(['code' => 0, 'msg' => 'success', 'data' => ['redirect' => $this->index_url]]);
        }else{
            return $this->makeView('laravel-company::admin.order.form',['res'=>$res]);
        }
    }

    public function del(Request $request)
    {
        $query = $request->query();
        $redirect = $query?$this->index_url.'?'.http_build_query($query):$this->index_url;
        $post = $request->input('delete');
        if(!empty($post)){
            Order::whereIn('order_id',$post)->delete();
            throw new ApiException(['code'=>0,'msg'=>'操作成功','data'=>['redirect'=>$redirect]]);
        }
    }

}
