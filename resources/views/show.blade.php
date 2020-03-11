@extends('layouts.app')

@section('content')
<div class="container mt-3">
<h1>{{$domain->name}}</h1>
<h1>{{$domain->created_at}}</h1>
<h1>{{$domain->updated_at}}</h1>
</div>
@endsection
