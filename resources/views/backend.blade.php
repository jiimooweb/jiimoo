<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hello</title>
</head>
<style>
    html,body {
            height: 100%;
            overflow: hidden;
            min-width: 600px;
    }

    body::-webkit-scrollbar {
            display: none;
    }

    a {
            text-decoration: none;
    }

    .app {
            height: 100%;
            width: 100%;
    }
</style>
<body>
    <div id="app" style="width:100%;height:100%">

    </div>
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/backend.js') }}"></script>

</body>

</html>