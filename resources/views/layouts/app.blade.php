<html>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<body class = bg-light>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="{{ route('main') }}">PageAnalyzer</a>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
  <ul class="navbar-nav mr-auto">
  <li class="nav-item"><a class="navbar-text" href="{{ route('main') }}">Home </a></li>
  <li class="nav-item"><a class="navbar-text" href="{{ route('index') }}">Domains</a></li>
  </ul>
  </div>
</nav>
@yield('content')
</html>