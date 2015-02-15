<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-COMPATIBLE" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <link href="css/login.min.css" rel="stylesheet">
    @yield('title')
</head>
<body>
    <div class="jumbotron">
        <div class="text-center banner">
            <img src="/img/banner3.gif">
        </div>
    </div>
    <div class="jumbotron">
        <div class="page-header text-center">
            <h2>CFinder on the web</h2>
        </div>
    </div>
    <div class="jumbotron"
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                @yield('content')
            </div>
        </div>
    </div>
    <div class="jumbotron">
        <div class="page-header text-center"></div>
    </div>
    <div class="jumbotron text-center">
        <p>&copy 2015 <a href="#">ELTE, Dept. of Biol. Phys.</a></p>
    </div>
</body>
</html>
 