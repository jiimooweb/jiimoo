    <div class="col-sm-10">  
        @include('vendor.UEditor.head')  
        <!-- 加载编辑器的容器 -->  
        <script id="container" name="{{$name ?? 'content'}}" type="text/plain" style="width:{{$width ?? '100%'}};height:{{$height ?? '300px'}}">  
            {{ $slot }}
        </script>  
        <!-- 实例化编辑器 -->  
        <script type="text/javascript">  
            var ue = UE.getEditor('container');  
            ue.ready(function(){  
                ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');   
            });  
        </script>  
    </div>  

