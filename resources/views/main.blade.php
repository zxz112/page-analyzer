<html>
    <head>
        <title>App Name - Page Analyzer</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="https://limitless-garden-68924.herokuapp.com/">Analyzer</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="https://limitless-garden-68924.herokuapp.com/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://limitless-garden-68924.herokuapp.com/domains">Domains</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container mt-3">
                            <div class="jumbotron">
        <h1 class="display-3">Page Analyzer</h1>
        <p class="lead">Check web pages for free</p>
        <hr class="my-4">

        <form action="https://limitless-garden-68924.herokuapp.com/domains" method="post" class="d-flex justify-content-center form-inline">
            <input type="hidden" name="_token" value="AUA0gnAEGHAHijDjpZEiv8EPBlgwimYueScF6qOM">            <input type="text" name="domain[name]" class="form-control form-control-lg" placeholder="https://www.example.com">
            <button type="submit" class="btn btn-lg btn-primary ml-3">Add</button>
        </form>
    </div>
        </div>
    </body>
</html>
