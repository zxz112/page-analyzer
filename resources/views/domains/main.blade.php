@extends('layouts.app')
@section('content')
<div class="container mt-3">
@include('flash::message')
@if ($errors->any())
    <div class="alert alert-danger ">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="jumbotron jumbotron bg-dark">
  <h1 class="display-3 text-light"" href="{{route('domains.main')}}">Page Analyzer</h1>
  <p class="text-secondary">Check web pages for free</p>
  <hr class="my-4">
  {{Form::open(['url' => route('domains.store')])}}
    <div class="input-group input-group-sm mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-sm">https://www.example.com</span>
      </div>
    {{Form::text('domain', '', ['class' => "form-control"])}}
    {{Form::submit('Check!', ['class' => 'btn btn-warning btn-bg'])}}
  </div>
</div>
  {{Form::close()}}
</div>
@endsection
