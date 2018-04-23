<form action="/jiimoo/public/admins/xcx/{{$xcx->id}}/check" method="POST">
    {{csrf_field()}}
    @foreach($combos as $combo)
        <div class="checkbox">
            <label>
                <input type="checkbox" name="combos[]"
                       value="{{$combo->id}}">
                {{$combo->name}}
            </label>
            @foreach($combo->module as $comboModule)
                <label>
                    <input type="checkbox" name="modules[]"
                           value="{{$comboModule->id}}">
                    {{$comboModule->name}}
                </label>
            @endforeach
        </div>
    @endforeach
    <button type="submit" class="btn btn-default">提交</button>
</form>
