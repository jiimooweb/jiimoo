<form action="/jiimoo/public/admin/xcx/create" method="POST">
    {{csrf_field()}}
    <label>name</label>
    <input name="name" type="text" >
    <label>appid</label>
    <input name="appid" type="text" >
    <label>userid</label>
    <input name="userid" type="text" >
    <button type="submit" class="btn btn-default">提交</button>
</form>
