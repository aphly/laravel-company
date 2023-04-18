<div class="top-bar">
    <h5 class="nav-title">邮件订单</h5>
</div>

<div class="imain">
    <div class="itop ">
        <form method="get" action="/company_admin/mail_task/order" class="select_form">
        <div class="search_box ">
            <input type="search" name="order_id" placeholder="order_id" value="{{$res['search']['order_id']}}">
            <button class="" type="submit">搜索</button>
        </div>
        </form>
        <div class="">
            <a class="badge badge-info ajax_get show_all0_btn" data-href="/company_admin/mail_task/import?id={{$res['info']->id}}">导入</a>
            <a class="badge badge-primary ajax_get show_all0_btn" data-href="/company_admin/mail_task/send?id={{$res['info']->id}}">发送</a>
        </div>
    </div>

    <form method="post"  class="del_form">
        <div class="table_scroll">
            <div class="table">
                <ul class="table_header">
                    <li >order_id</li>
                    <li >email</li>
                    <li >firstname</li>
                    <li >lastname</li>
                    <li >price</li>
                    <li >status</li>
                </ul>
                @if($res['list']->total())
                    @foreach($res['list'] as $v)
                    <ul class="table_tbody">
                        <li><input type="checkbox" class="delete_box" name="delete[]" value="{{$v['order_id']}}">{{$v['order_id']}}</li>
                        <li>{{ $v->order->email }}</li>
                        <li>{{ $v->order->firstname }}</li>
                        <li>{{ $v->order->lastname }}</li>
                        <li>{{ $v->order->currency }} {{ $v->order->price }}</li>
                        <li>
                            @if($dict['mail_send_status'])
                                @if($v['status']==2)
                                    <span class="badge badge-success">{{$dict['mail_send_status'][$v['status']]}}</span>
                                @else
                                    <span class="badge badge-secondary">{{$dict['mail_send_status'][$v['status']]}}</span>
                                @endif
                            @endif
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


