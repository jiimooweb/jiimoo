<form action="/jiimoo/public/admin/module/create" method="POST">
    {{csrf_field()}}
    <label>name</label>
    <input name="name" type="text" >
    <label>desc</label>
    <input name="desc" type="text" >
    <button type="submit" class="btn btn-default">提交</button>
</form>
