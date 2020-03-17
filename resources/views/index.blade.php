<?php
use DebugBar\StandardDebugBar;

$debugbar = new StandardDebugBar();
$debugbarRenderer = $debugbar->getJavascriptRenderer();

$debugbar["messages"]->addMessage("hello world!");
?>
<html>
<head>
    <?php echo $debugbarRenderer->renderHead() ?>
</head>
<body>
    <?php echo $debugbarRenderer->render() ?>
</body>
</html>
@extends('layouts.app')

@section('content')
<div class="container mt-3">
@include('flash::message')
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">id</th>
      <th scope="col">name</th>
      <th scope="col">status code</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($domains as $domain)
    <tr> 
      <td>{{$domain->id}}</td>
      <td><a href="{{route('show', $domain->id)}}"> {{$domain->name}}</a></td>
      <td>{{$domain->status}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>
@endsection
