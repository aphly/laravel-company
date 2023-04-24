<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>

<div class="imain">
    <div class="itop ">
        <form method="get" action="/company_admin/order/index" class="select_form">
        <div class="search_box ">
            <input type="search" name="order_id" placeholder="order_id" value="{{$res['search']['order_id']}}">
            <input type="search" name="email" placeholder="email" value="{{$res['search']['email']}}">
            <button class="" type="submit">搜索</button>
        </div>
        </form>
        <div class="">
            <a class="badge badge-primary ajax_get show_all0_btn d-none" data-href="/company_admin/order/add">添加</a>
        </div>
    </div>

    <form method="post"  @if($res['search']['string']) action="/company_admin/order/del?{{$res['search']['string']}}" @else action="/company_admin/order/del" @endif  class="del_form">
    @csrf
        <div class="table_scroll">
            <div class="table">
                <ul class="table_header">
                    <li >ID</li>
                    <li >email</li>
                    <li >firstname</li>
                    <li >lastname</li>
                    <li >price</li>
                    <li >telephone</li>
                    <li>add_time</li>
                    <li >操作</li>
                </ul>
                @if($res['list']->total())
                    @foreach($res['list'] as $v)
                    <ul class="table_tbody">
                        <li><input type="checkbox" class="delete_box" name="delete[]" value="{{$v['order_id']}}">{{$v['order_id']}}</li>
                        <li>{{ $v['email'] }}</li>
                        <li>{{ $v['firstname'] }}</li>
                        <li>{{ $v['lastname'] }}</li>
                        <li>{{ $v['currency'] }} {{ $v['price'] }}</li>
                        <li>{{ $v['telephone'] }}</li>
                        <li>{{ $v['add_time'] }}</li>
                        <li>
                            <a class="badge badge-info ajax_get" data-href="/company_admin/order/info?order_id={{$v['order_id']}}">详情</a>
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


