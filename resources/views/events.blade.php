@extends('layouts.app')


@section('content')
<div class="container">
    <form method="GET" class="form-group">
        <input type="text" name="q" value="{{ $q }}" class="form-control mb-2 mr-sm-2">
        <button type="submit" class="btn btn-primary btn-block ">Search</button>
      </form>
<ul>
    

    @forEach($events as $event)
    <div class="card" style="width: 63rem;">
      <img class="card-img-top" src="{{ route('events.image', $event->image) }}">
      <div class="card-body">
        <h5 class="card-title">{{$event->name}}</h5>
        <p class="card-text">
          Type :{{ $event->type }}
          <br>
          Takes Place: {{ $event->takes_place_at->diffForHumans() }}
          <br>
          <small> Number of Tickets : {{$event->tickets}}</small>
        </p>
      </div>
      
      <div class="card-body">
        <a href="{{ url('/events') }}/{{$event->id}}" class="btn btn-info btn-lg">Show the details</a>

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
      </div>
    </div>
    <br>
    @endforEach
</ul>
</div>
@endsection