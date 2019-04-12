@extends('layouts.app')


@section('content')
<div class="container">
        <form method="post" action="{{ route('events.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name"/>
            </div>

            <div class="form-group">
                <label for="type">Type:</label>
                <input type="text" class="form-control" id="type" name="type"/>
            </div>

            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control" id="image" name="image"/>
            </div>

            <div class="form-group">
                <label for="takes_place_at">Takes Place at (UTC):</label>
                <input type="datetime-local" min="{{ Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" value="{{ Carbon\Carbon::now()->addDays(2)->format('Y-m-d\TH:i') }}" class="form-control" id="takes_place_at" name="takes_place_at"/>
            </div>

            <div class="form-group">
                <label for="tickets">Number of Available Tickets:</label>
                <input type="number" min="1" value="1" class="form-control" id="tickets" name="tickets"/>
            </div>

            <button type="submit" class="btn btn-primary">Add</button>
        </form>
</div>
@endsection