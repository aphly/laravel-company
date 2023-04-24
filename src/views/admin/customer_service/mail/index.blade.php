<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>

<div class="imain">
    <div class="itop ">
        <form method="get" action="/company_admin/customer_service/mail/index" class="select_form">
        <div class="search_box ">
            <input type="search" name="from_address" placeholder="from_address" value="{{$res['search']['from_address']}}">
            <button class="" type="submit">搜索</button>
        </div>
        </form>
        <div class="">
            <a class="badge badge-primary ajax_get show_all0_btn" data-href="/company_admin/customer_service/mail/add">添加</a>
        </div>
    </div>

    <form method="post"  @if($res['search']['string']) action="/company_admin/customer_service/mail/del?{{$res['search']['string']}}" @else action="/company_admin/customer_service/mail/del" @endif  class="del_form">
    @csrf
        <div class="table_scroll">
            <div class="table">
                <ul class="table_header">
                    <li >ID</li>
                    <li >username</li>
                    <li >from_address</li>
                    <li >status</li>
                    <li >操作</li>
                </ul>
                @if($res['list']->total())
                    @foreach($res['list'] as $v)
                    <ul class="table_tbody">
                        <li><input type="checkbox" class="delete_box" name="delete[]" value="{{$v['id']}}">{{$v['id']}}</li>
                        <li>{{ $v->manager->username }}</li>
                        <li>{{ $v['from_address'] }}</li>
                        <li>
                            @if($dict['status'])
                                @if($v['status']==1)
                                    <span class="badge badge-success">{{$dict['status'][$v['status']]}}</span>
                                @else
                                    <span class="badge badge-secondary">{{$dict['status'][$v['status']]}}</span>
                                @endif
                            @endif
                        </li>
                        <li>
                            <a class="badge badge-info ajax_get" data-href="/company_admin/customer_service/mail/edit?id={{$v['id']}}">编辑</a>
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


