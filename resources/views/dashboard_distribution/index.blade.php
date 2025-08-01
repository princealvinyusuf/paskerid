@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2>Dashboard Persediaan Tenaga Kerja</h2>
    @if($dashboard)
        {!! $dashboard->iframe_code !!}
    @else
        <p>Sedang dikembangkan</p>
    @endif
</div>
@endsection 