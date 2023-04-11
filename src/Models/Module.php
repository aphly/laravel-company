<?php

namespace Aphly\LaravelCompany\Models;

use Aphly\Laravel\Models\Dict;
use Aphly\Laravel\Models\Menu;
use Aphly\Laravel\Models\Module as Module_base;
use Illuminate\Support\Facades\DB;

class Module extends Module_base
{
    public $dir = __DIR__;

    public function install($module_id){
        parent::install($module_id);
        $menu = Menu::create(['name' => '公司','route' =>'','pid'=>0,'type'=>1,'module_id'=>$module_id,'sort'=>10]);
        if($menu->id){
            $data=[];
            $data[] =['name' => '支付方式','route' =>'payment_admin/method/index','pid'=>$menu->id,'type'=>2,'module_id'=>$module_id,'sort'=>0];
            $data[] =['name' => '流水号','route' =>'payment_admin/payment/index','pid'=>$menu->id,'type'=>2,'module_id'=>$module_id,'sort'=>0];
            DB::table('admin_menu')->insert($data);
        }
        $menuData = Menu::where(['module_id'=>$module_id])->get();
        $data=[];
        foreach ($menuData as $val){
            $data[] =['role_id' => 1,'menu_id'=>$val->id];
        }
        DB::table('admin_role_menu')->insert($data);

        $dict = Dict::create(['name' => '支付状态','key'=>'payment_status','module_id'=>$module_id]);
        if($dict->id){
            $data=[];
            $data[] =['dict_id' => $dict->id,'name'=>'未支付','value'=>'1'];
            $data[] =['dict_id' => $dict->id,'name'=>'已支付','value'=>'2'];
            DB::table('admin_dict_value')->insert($data);
        }

        $dict = Dict::create(['name' => '支付退款状态','key'=>'payment_refund_status','module_id'=>$module_id]);
        if($dict->id){
            $data=[];
            $data[] =['dict_id' => $dict->id,'name'=>'等待退款','value'=>'1'];
            $data[] =['dict_id' => $dict->id,'name'=>'退款成功','value'=>'2'];
            DB::table('admin_dict_value')->insert($data);
        }
        return 'install_ok';
    }

    public function uninstall($module_id){
        parent::uninstall($module_id);
        return 'uninstall_ok';
    }


}
