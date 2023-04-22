
<div class="top-bar">
    <h5 class="nav-title">info</h5>
</div>
<div class="imain">
    <form method="post" @if($res['info']->id) action="/company_admin/customer_service/mail/edit?id={{$res['info']->id}}" @else action="/company_admin/customer_service/mail/add" @endif class="save_form">
        @csrf
        <div class="">
            <div class="form-group">
                <label for="">username</label>
                <input type="text" name="username" class="form-control " value="{{$res['info']->username}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">password</label>
                <input type="text" name="password" class="form-control " value="{{$res['info']->password}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">from_address</label>
                <input type="text" name="from_address" class="form-control " value="{{$res['info']->from_address}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">from_name</label>
                <input type="text" name="from_name" class="form-control " value="{{$res['info']->from_name}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">host</label>
                <select name="host"  class="form-control">
                    <option value="smtp.gmail.com" @if($res['info']->host=='smtp.gmail.com') selected @endif>smtp.gmail.com</option>
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">port</label>
                <input type="text" name="port" class="form-control " value="{{$res['info']->port??465}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">encryption</label>
                <input type="text" name="encryption" class="form-control " value="{{$res['info']->encryption??'ssl'}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">状态</label>
                <select name="status"  class="form-control">
                    @if(isset($dict['status']))
                        @foreach($dict['status'] as $key=>$val)
                            <option value="{{$key}}" @if($res['info']->status==$key) selected @endif>{{$val}}</option>
                        @endforeach
                    @endif
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
