
<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>
<div class="imain">
    <form method="post" @if($res['info']->id) action="/company_admin/customer_service/mail_task/edit?id={{$res['info']->id}}" @else action="/company_admin/customer_service/mail_task/add" @endif class="save_form">
        @csrf
        <div class="">
            <div class="form-group">
                <label for="">mail_template_id</label>
                <select name="mail_template_id"  class="form-control">
                    @if($res['mail_template']->count())
                        @foreach($res['mail_template'] as $val)
                            <option value="{{$val->id}}" @if($res['info']->mail_template_id==$val->id) selected @endif>{{$val->name}}</option>
                        @endforeach
                    @endif
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">from_address</label>
                <select name="mail_id" class="form-control">
                    @if($res['mail']->count())
                        @foreach($res['mail'] as $val)
                            <option value="{{$val->id}}" @if($res['info']->mail_id==$val->id) selected @endif>{{$val->from_address}}</option>
                        @endforeach
                    @endif
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">状态</label>
                <select name="status" class="form-control">
                    <option value="1" @if($res['info']->status==1) selected @endif>未发送</option>
                    <option value="2" @if($res['info']->status==2) selected @endif>已发送</option>
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <button class="btn btn-primary" type="submit">保存</button>
        </div>
    </form>

</div>
<style>

</style>
<script>

</script>
