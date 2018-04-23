<form action="/jiimoo/public/admins/user/login" method="POST">
    {{csrf_field()}}
    <label>nameoremail</label>
    <input name="account" type="text" >
    <label>password</label>
    <input name="password" type="text" >
    <button type="submit" class="btn btn-default">提交</button>
</form>
