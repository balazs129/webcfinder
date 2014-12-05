<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-COMPATIBLE" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">

        <link href="css/bootstrap.min.css" rel="stylesheet">

        @yield('head')
    </head>
    <body>
        @include('navbar')
        {{--sidebar--}}
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                @yield('sidebar')
                </div>
            </div>
        </div>
        {{--content-area--}}
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        @yield('content')
        </div>
    <script src="js/webcfinder.min.js"></script>
    </body>
</html>
 