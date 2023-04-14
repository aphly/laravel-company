
<div class="top-bar">
    <h5 class="nav-title">info</h5>
</div>
<div class="imain">
    <form method="post" @if($res['info']->order_id) action="/company_admin/order/edit?order_id={{$res['info']->order_id}}" @else action="/company_admin/order/add" @endif class="save_form">
        @csrf
        <div class="">
            <div class="form-info">
                <label for="">order_id</label>
                <input type="text" name="order_id" class="form-control " value="{{$res['info']->order_id}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-info">
                <label for="">email</label>
                <input type="text" name="email" class="form-control " value="{{$res['info']->email}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-info">
                <label for="">firstname</label>
                <input type="text" name="firstname" class="form-control " value="{{$res['info']->firstname}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-info">
                <label for="">lastname</label>
                <input type="text" name="lastname" class="form-control " value="{{$res['info']->lastname}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-info">
                <label for="">country</label>
                <input type="text" name="country" class="form-control " value="{{$res['info']->country}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-info">
                <label for="">city</label>
                <input type="text" name="city" class="form-control " value="{{$res['info']->city}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-info">
                <label for="">address</label>
                <input type="text" name="address" class="form-control " value="{{$res['info']->address}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-info">
                <label for="">postcode</label>
                <input type="text" name="postcode" class="form-control " value="{{$res['info']->postcode}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-info">
                <label for="">telephone</label>
                <input type="text" name="telephone" class="form-control " value="{{$res['info']->telephone}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-info">
                <label for="">price</label>
                <input type="text" name="price" class="form-control " value="{{$res['info']->price}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-info">
                <label for="">currency</label>
                <input type="text" name="currency" class="form-control " value="{{$res['info']->currency}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-info">
                <label for="">add_time</label>
                <input type="text" name="add_time" class="form-control " value="{{$res['info']->add_time}}">
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
