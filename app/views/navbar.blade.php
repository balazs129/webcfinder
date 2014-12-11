<nav class="navbar navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Webcfinder</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-left">

                <li><a href="#">Manual</a></li>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Publications</a></li>
                <li><a href="#">Feedback</a></li>
                <li><a href="#">Example Data Set</a></li>
                <li><a href="#">Profile</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><span class="navbar-text">Signed in as {{ Auth::user()->name }} </span></li>
                <li><a href="/logout">Log out</a></li>
            </ul>
        </div>
    </div>
</nav>
 