@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2>Dashboard Struktur Ketenagakerjaan</h2>
    @if($dashboard)
        {!! $dashboard->iframe_code !!}
    @else
        <p>No Dashboard Struktur Ketenagakerjaan available.</p>
    @endif
</div>
@endsection 