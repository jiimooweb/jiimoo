<form action="/jiimoo/public/admins/user/register" method="POST">
    {{csrf_field()}}
    <label>name</label>
    <input name="username" type="text" >
    <label>email</label>
    <input name="email" type="text" >
    <label>password</label>
    <input name="password" type="text" >
    <button type="submit" class="btn btn-default">提交</button>
</form>
