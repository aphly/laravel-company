<div class="top-bar">
    <h5 class="nav-title">邮件任务</h5>
</div>

<div class="imain">
    <div class="itop ">
        <form method="get" action="/company_admin/customer_service/mail_task/index" class="select_form">
        <div class="search_box ">
            <input type="search" name="id" placeholder="id" value="{{$res['search']['id']}}">
            <button class="" type="submit">搜索</button>
        </div>
        </form>
        <div class="">
            <a class="badge badge-primary ajax_get show_all0_btn" data-href="/company_admin/customer_service/mail_task/add">添加</a>
        </div>
    </div>

    <form method="post"  @if($res['search']['string']) action="/company_admin/customer_service/mail_task/del?{{$res['search']['string']}}" @else action="/company_admin/customer_service/mail_task/del" @endif  class="del_form">
    @csrf
        <div class="table_scroll">
            <div class="table">
                <ul class="table_header">
                    <li >ID</li>
                    <li >username</li>
                    <li >mail</li>
                    <li >template</li>
                    <li >status</li>
                    <li >操作</li>
                </ul>
                @if($res['list']->total())
                    @foreach($res['list'] as $v)
                    <ul class="table_tbody">
                        <li><input type="checkbox" class="delete_box" name="delete[]" value="{{$v['id']}}">{{$v['id']}}</li>
                        <li>{{ $v->manager->username }}</li>
                        <li>{{ $v->mail->from_address??'' }}</li>
                        <li>{{ $v->mailTemplate->name??'' }}</li>
                        <li>
                            @if($dict['mail_send_status'])
                                @if($v['status']==2)
                                    <span class="badge badge-success">{{$dict['mail_send_status'][$v['status']]}}</span>
                                @else
                                    <span class="badge badge-secondary">{{$dict['mail_send_status'][$v['status']]}}</span>
                                @endif
                            @endif
                        </li>
                        <li>
                            <a class="badge badge-info ajax_get" data-href="/company_admin/customer_service/mail_task/edit?id={{$v['id']}}">编辑</a>

                            <a class="badge badge-info ajax_get" data-href="/company_admin/customer_service/mail_task/order?id={{$v['id']}}">订单</a>
                        </li>
                    </ul>
                    @endforeach
                    <ul class="table_bottom">
                        <li>
                            <input type="checkbox" class="delete_box deleteboxall"  onclick="checkAll(this)">
                            <button class="badge badge-danger del" type="submit">删除</button>
                        </li>
                        <li >
                            {{$res['list']->links('laravel::admin.pagination')}}
                        </li>
                    </ul>
                @endif
            </div>
        </div>

    </form>
</div>


