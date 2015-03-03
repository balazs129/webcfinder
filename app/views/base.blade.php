<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-COMPATIBLE" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">

        <link href="/css/main.min.css" rel="stylesheet">

        @yield('head')
    </head>
    <body>
        @include('navbar')
        {{--sidebar--}}
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                @yield('sidebar-content')
                </div>
            </div>
        </div>
        {{--content-area--}}
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-4 col-md-12 col-md-offset-2 main">
                    @yield('content')
                </div>
            </div>
        </div>
        @include('footer')
    <script src="/js/webcfinder.min.js"></script>
    @yield('script')
    </body>
</html>
 