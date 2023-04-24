
<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>
<div class="imain">
    <form method="post" @if($res['info']->id) action="/company_admin/work/report/edit?id={{$res['info']->id}}" @else action="/company_admin/work/report/add" @endif class="save_form_file">
        @csrf
        <div class="">
            <div class="form-group">
                <label for="">内容</label>
                <textarea name="content" class="form-control" style="height: 300px;">{{$res['info']->content}}</textarea>
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">计划</label>
                <textarea name="plan" class="form-control" style="height: 300px;">{{$res['info']->plan}}</textarea>
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">文件</label>
                <input type="file"  name="xlsx" class="form-control-file" >
                <div class="invalid-feedback"></div>
            </div>
            @if($action[1] =='edit')
                <div class="form-group">
                    <a href="/upload_file/download?id={{$res['info']->upload_file_id}}">下载文件</a>
                </div>
            @endif
            <button class="btn btn-primary" type="submit">保存</button>
        </div>
    </form>

</div>
<style>

</style>
<script>

</script>
