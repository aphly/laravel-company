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
            $menu2 = Menu::create(['name' => '系统','route' =>'','pid'=>$menu->id,'type'=>1,'module_id'=>$module_id,'sort'=>20]);
            if($menu2){
                $data=[];
                $data[] =['name' => '订单管理','route' =>'company_admin/order/index','pid'=>$menu2->id,'type'=>2,'module_id'=>$module_id,'sort'=>0];
                DB::table('admin_menu')->insert($data);
            }
            $menu2 = Menu::create(['name' => '客服部门','route' =>'','pid'=>$menu->id,'type'=>1,'module_id'=>$module_id,'sort'=>20]);
            if($menu2){
                $data=[];
                $data[] =['name' => '邮件设置','route' =>'company_admin/mail/index','pid'=>$menu2->id,'type'=>2,'module_id'=>$module_id,'sort'=>0];
                $data[] =['name' => '邮件模板','route' =>'company_admin/mail_template/index','pid'=>$menu2->id,'type'=>2,'module_id'=>$module_id,'sort'=>0];
                $data[] =['name' => '订单邮件','route' =>'company_admin/order_mail/index','pid'=>$menu2->id,'type'=>2,'module_id'=>$module_id,'sort'=>0];
                DB::table('admin_menu')->insert($data);
            }
        }
        $menuData = Menu::where(['module_id'=>$module_id])->get();
        $data=[];
        foreach ($menuData as $val){
            $data[] =['role_id' => 1,'menu_id'=>$val->id];
        }
        DB::table('admin_role_menu')->insert($data);

        $dict = Dict::create(['name' => '邮件发送状态','key'=>'mail_send_status','module_id'=>$module_id]);
        if($dict->id){
            $data=[];
            $data[] =['dict_id' => $dict->id,'name'=>'未发送','value'=>'1'];
            $data[] =['dict_id' => $dict->id,'name'=>'已发送','value'=>'2'];
            DB::table('admin_dict_value')->insert($data);
        }
        return 'install_ok';
    }

    public function uninstall($module_id){
        parent::uninstall($module_id);
        return 'uninstall_ok';
    }


}
