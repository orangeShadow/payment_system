<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" value="{{csrf_token()}}">
        <link rel="stylesheet" href="//cdn.materialdesignicons.com/2.0.46/css/materialdesignicons.min.css">
        <title>Payments</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link href="{{asset('/css/app.css')}}" rel="stylesheet">
    </head>
    <body>
        <div class="container is-fluid" id="app">
            <top-navbar></top-navbar>
            <router-view></router-view>
        </div>
        <script src="{{asset('/js/app.js')}}"></script>
    </body>
</html>
