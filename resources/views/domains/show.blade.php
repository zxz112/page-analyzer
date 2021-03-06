@extends('layouts.app')

@section('content')
<div class="container mt-3">
@include('flash::message')
<h1>{{$domain->name}}</h1>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">id</th>
      <th scope="col">name</th>
      <th scope="col">created_at</th>
      <th scope="col">updated_at</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>{{$domain->id}}</td>
      <td>{{$domain->name}}</td>
      <td>{{$domain->created_at}}</td>
      <td>{{$domain->updated_at}}</td>
    </tr>
  </tbody>
</table>

<h1> Checks </h1>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">status_code</th>
      <th scope="col">updated_at</th>
      <th scope="col">h1</th>
      <th scope="col">keywords</th>
      <th scope="col">description</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($checks as $check)
    <tr>  
      <td>{{$check->status_code}}</td>
      <td>{{$check->updated_at}}</td>
      <td>{{$check->h1}}</td>
      <td>{{$check->keywords}}</td>
      <td>{{$check->description}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
{{Form::open(['url' => route('domains.checks.store', $domain->id)])}}
    {{Form::submit('Check!', ['class' => 'btn btn-dark btn-bg'])}}
{{Form::close()}}
</div> 
@endsection
