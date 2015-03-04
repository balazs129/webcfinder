<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-COMPATIBLE" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <link href="/css/visualize.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid max-height">
        <div class="row-fluid max-height">
            <div class="col-xs-3 control-panel">
                <div class="btn-group-vertical">
                    <button class="btn btn-xs btn-default">Communities</button>
                    <button class="btn btn-xs btn-default">Vertices</button>
                    <button class="btn btn-xs btn-default">Cliques</button>
                    <button class="btn btn-xs btn-default">Stats</button>
                    <button class="btn btn-xs btn-default">Graph of communities</button>

                    <button class="btn btn-xs btn-info">Settings</button>
                    <button class="btn btn-xs btn-default">Export graph</button>
                </div>

            <div class="data-container">
                <ul class="data-list text-muted"></ul>
            </div>
                </div>
            <div class="col-xs-9 max-height vertical-center">
                <svg class="visualization"></svg>
            </div>
        </div>
    </div>
</body>
</html>
