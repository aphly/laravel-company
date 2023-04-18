
<div class="top-bar">
    <h5 class="nav-title">导入</h5>
</div>
<div class="imain">
    <form method="post" action="/company_admin/customer_service/mail_task/import?id={{$res['info']->id}}" class="save_form_file">
        @csrf
        <div class="">
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

</script>
