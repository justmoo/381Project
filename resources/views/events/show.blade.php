@extends('layouts.app')


@section('content')
<div class="container">
        <hr>
        <a href="{{ url('/events') }}" class="btn btn-primary btn-lg btn-block">back</a>
        <hr>
        <img class="card-img-top" src="{{ route('events.image', $event->image) }}">

        <div class="text-center">
            <h2>{{ $event->name }}</h2>
            <p>{{ $event->type }}</p>
            <small>takes place {{$event->created_at->diffForHumans()}} </small>
            <br>

            @if(auth()->check())

            @if(auth()->user()->isReserved($event))

            <form method="POST" action="{{ route('events.reserve', $event->id) }}" class="d-inline-block">
            @csrf
            <button class="btn btn-info btn-lg">Show Ticket</button>
            </form>

            @if($event->takes_place_at->gt(now()->addDays(2)))
            <form method="POST" action="{{ route('events.cancel', $event->id) }}" class="d-inline-block">
            @csrf
            <button class="btn btn-danger btn-lg">Cancel your reservation</button>
            </form>
            @else
            <button class="btn btn-danger btn-lg" disabled>You cannot cancel the reservation less than 48 hours before event start date</button>
            @endif

            @else

            <form method="POST" action="{{ route('events.reserve', $event->id) }}" class="d-inline-block">
            @csrf
            <button class="btn btn-primary btn-lg">Reserve</button>
            </form>

            @endif

            @endif

            @if(auth('admin')->check())
            <form method="POST" action="{{ route('events.tickets', $event) }}" class="d-inline-block">
                @csrf
                <button class="btn btn-primary btn-lg">Download Tickets</button>
            </form>
            @endif
        </div>
        <hr>
</div>
@endsection