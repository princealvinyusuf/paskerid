@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2>{{ $info->title }}</h2>
    {!! $info->iframe_url !!}
    <a href="{{ route('informasi.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection 