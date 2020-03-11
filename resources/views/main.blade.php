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
        <!-- <form action="{{ route('store') }}" method="post" class="d-flex justify-content-center form-inline">
            <input type="hidden" name="_token" value="RjXXnPdhNqkzhRXyS8UwSpbUOBdBBHHAjYtRFPDc">            <input type="text" class="form-control form-control-lg" placeholder="https://www.example.com">
            <button type="submit" class="btn btn-lg btn-primary ml-3">Add</button>
        </form> -->
</div>
@endsection
