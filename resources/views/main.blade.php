@extends('layouts.app')


@section('content')

<div class="container mt-3">
@include('flash::message')
<div class="jumbotron jumbotron">
        <h1 class="display-3" href="{{route('main')}}">Page Analyzer</h1>
        <p class="lead">Check web pages for free</p>
        <hr class="my-4">
{{Form::open(['url' => route('store')])}}
    {{Form::text('domain')}}
    {{Form::submit('Click Me!')}}
{{Form::close()}}
</div>
@endsection
