@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2>Dashboard Distribution</h2>
    @if($dashboard)
        {!! $dashboard->iframe_code !!}
    @else
        <p>No dashboard distribution available.</p>
    @endif
</div>
@endsection 