<?php

namespace Aphly\LaravelCompany\Models;

use Aphly\Laravel\Models\Dict;
use Aphly\Laravel\Models\LevelPath;
use Aphly\Laravel\Models\Manager;
use Aphly\Laravel\Models\Menu;
use Aphly\Laravel\Models\Module as Module_base;
use Aphly\Laravel\Models\Role;
use Illuminate\Support\Facades\DB;

class Module extends Module_base
{
    public $dir = __DIR__;

    public function install($module_id){
        parent::install($module_id);
        $manager = Manager::where('username','admin')->firstOrError();
        $data=[];
        $data[] =['id'=>3,'name' => '公司','pid'=>1,'uuid'=>$manager->uuid,'type'=>1,'status'=>1,'module_id'=>$module_id];
        $data[] =['id'=>4,'name' => '客服部门','pid'=>3,'uuid'=>$manager->uuid,'type'=>1,'status'=>1,'module_id'=>$module_id];
        DB::table('admin_level')->insert($data);
        (new LevelPath)->rebuild();

        $csRole = Role::create(['name' => '客服','level_id' => 4,'data_perm' => 1,'module_id'=>$module_id]);

        $menu = Menu::create(['name' => '公司','route' =>'','pid'=>0,'uuid'=>$manager->uuid,'type'=>1,'module_id'=>$module_id,'sort'=>10]);
        if($menu->id){
            $menu2 = Menu::create(['name' => '工作','route' =>'','pid'=>$menu->id,'uuid'=>$manager->uuid,'type'=>1,'module_id'=>$module_id,'sort'=>20]);
            if($menu2){
                $data=[];
                $data[] =['name' => '报告','route' =>'company_admin/work/report/index','pid'=>$menu2->id,'uuid'=>$manager->uuid,'type'=>2,'module_id'=>$module_id,'sort'=>0];
                DB::table('admin_menu')->insert($data);
            }
            $menu2 = Menu::create(['name' => '公共信息','route' =>'','pid'=>$menu->id,'uuid'=>$manager->uuid,'type'=>1,'module_id'=>$module_id,'sort'=>20]);
            if($menu2){
                $data=[];
                $data[] =['name' => '订单管理','route' =>'company_admin/order/index','pid'=>$menu2->id,'uuid'=>$manager->uuid,'type'=>2,'module_id'=>$module_id,'sort'=>0];
                DB::table('admin_menu')->insert($data);
            }
            $menu2 = Menu::create(['name' => '客服部门','route' =>'','pid'=>$menu->id,'uuid'=>$manager->uuid,'type'=>1,'module_id'=>$module_id,'sort'=>20]);
            if($menu2){
                $menu3 = Menu::create(['name' => '邮件设置','route' =>'company_admin/customer_service/mail/index','pid'=>$menu2->id,'uuid'=>$manager->uuid,'type'=>2,'module_id'=>$module_id,'sort'=>0]);
                $data=[];
                $data[] =['name' => '邮件增加','route' =>'company_admin/customer_service/mail/add','pid'=>$menu3->id,'uuid'=>$manager->uuid,'type'=>3,'module_id'=>$module_id,'sort'=>0];
                $data[] =['name' => '邮件修改','route' =>'company_admin/customer_service/mail/edit','pid'=>$menu3->id,'uuid'=>$manager->uuid,'type'=>3,'module_id'=>$module_id,'sort'=>0];
                $data[] =['name' => '邮件删除','route' =>'company_admin/customer_service/mail/del','pid'=>$menu3->id,'uuid'=>$manager->uuid,'type'=>3,'module_id'=>$module_id,'sort'=>0];
                DB::table('admin_menu')->insert($data);

                $menu3 = Menu::create(['name' => '邮件模板','route' =>'company_admin/customer_service/mail_template/index','pid'=>$menu2->id,'uuid'=>$manager->uuid,'type'=>2,'module_id'=>$module_id,'sort'=>0]);
                $data=[];
                $data[] =['name' => '模板增加','route' =>'company_admin/customer_service/mail_template/add','pid'=>$menu3->id,'uuid'=>$manager->uuid,'type'=>3,'module_id'=>$module_id,'sort'=>0];
                $data[] =['name' => '模板修改','route' =>'company_admin/customer_service/mail_template/edit','pid'=>$menu3->id,'uuid'=>$manager->uuid,'type'=>3,'module_id'=>$module_id,'sort'=>0];
                $data[] =['name' => '模板删除','route' =>'company_admin/customer_service/mail_template/del','pid'=>$menu3->id,'uuid'=>$manager->uuid,'type'=>3,'module_id'=>$module_id,'sort'=>0];
                DB::table('admin_menu')->insert($data);

                $menu3 = Menu::create(['name' => '邮件任务','route' =>'company_admin/customer_service/mail_task/index','pid'=>$menu2->id,'uuid'=>$manager->uuid,'type'=>2,'module_id'=>$module_id,'sort'=>0]);
                $data=[];
                $data[] =['name' => '任务增加','route' =>'company_admin/customer_service/mail_task/add','pid'=>$menu3->id,'uuid'=>$manager->uuid,'type'=>3,'module_id'=>$module_id,'sort'=>0];
                $data[] =['name' => '任务修改','route' =>'company_admin/customer_service/mail_task/edit','pid'=>$menu3->id,'uuid'=>$manager->uuid,'type'=>3,'module_id'=>$module_id,'sort'=>0];
                $data[] =['name' => '任务删除','route' =>'company_admin/customer_service/mail_task/del','pid'=>$menu3->id,'uuid'=>$manager->uuid,'type'=>3,'module_id'=>$module_id,'sort'=>0];
                $data[] =['name' => '订单邮件','route' =>'company_admin/customer_service/mail_task/order','pid'=>$menu3->id,'uuid'=>$manager->uuid,'type'=>3,'module_id'=>$module_id,'sort'=>0];
                $data[] =['name' => '订单导入','route' =>'company_admin/customer_service/mail_task/import','pid'=>$menu3->id,'uuid'=>$manager->uuid,'type'=>3,'module_id'=>$module_id,'sort'=>0];
                $data[] =['name' => '邮件发送','route' =>'company_admin/customer_service/mail_task/send','pid'=>$menu3->id,'uuid'=>$manager->uuid,'type'=>3,'module_id'=>$module_id,'sort'=>0];
                DB::table('admin_menu')->insert($data);
            }
        }
        $menuData = Menu::where(['module_id'=>$module_id])->get();

        $data=[];
        foreach ($menuData as $val){
            $data[] =['role_id' => 1,'menu_id'=>$val->id];
            $data[] =['role_id' => 2,'menu_id'=>$val->id];
            $data[] =['role_id' => $csRole->id,'menu_id'=>$val->id];
        }
        DB::table('admin_role_menu')->insert($data);

        $dict = Dict::create(['name' => '邮件发送状态','uuid'=>$manager->uuid,'key'=>'mail_send_status','module_id'=>$module_id]);
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
