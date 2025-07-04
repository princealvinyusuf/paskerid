@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2>Dashboard Performance</h2>
    @if($dashboard)
        {!! $dashboard->iframe_code !!}
    @else
        <p>No dashboard performance available.</p>
    @endif
</div>
@endsection 