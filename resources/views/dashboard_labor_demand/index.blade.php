@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2>Dashboard Labor Demand</h2>
    @if($dashboard)
        {!! $dashboard->iframe_code !!}
    @else
        <p>No dashboard labor demand available.</p>
    @endif
</div>
@endsection 