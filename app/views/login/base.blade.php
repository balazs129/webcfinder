<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-COMPATIBLE" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    @yield('title')
    </head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-md-offset-4">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
 