
<div class="top-bar">
    <h5 class="nav-title">info</h5>
</div>
<div class="imain">
    <form method="post" @if($res['info']->id) action="/company_admin/order_mail/edit?id={{$res['info']->id}}" @else action="/company_admin/order_mail/add" @endif class="save_form_file">
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
                <label for="">xlsx文件</label>
                <input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" name="xlsx" class="form-control-file" >
                <div class="invalid-feedback"></div>
            </div>

            <button class="btn btn-primary" type="submit">保存</button>
        </div>
    </form>

</div>
<style>

</style>
<script>
function save_form_file_res(res, that) {
    console.log(res, that)
}
</script>
