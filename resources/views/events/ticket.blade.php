@extends('layouts.app')


@section('content')
<div class="container text-center">
    <h1>Here is your ticket</1>
    <form method="POST" action="{{ route('events.reserve', $event) }}?pdf">
        @csrf
        <button class="btn btn-primary">Download as PDF</button>
    </form>
    <div class="d-inline-block">
        {!! $qrcode !!}
    </div>
</div>
@endsection