<form action="/jiimoo/public/admins/combo/create" method="POST">
    {{csrf_field()}}
    <label>name</label>
    <input name="name" type="text" >
    <label>desc</label>
    <input name="desc" type="text" >
    @foreach($modules as $module)
    <div class="checkbox">
        <label>
            <input type="checkbox" name="modules[]"
                   value="{{$module->id}}">
            {{$module->name}}
        </label>
    </div>
    @endforeach
    <button type="submit" class="btn btn-default">提交</button>
</form>
