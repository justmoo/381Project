<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SVG\SVG;
use App\Event;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth')->only('reserve', 'cancel');
        $this->middleware('auth:eo')->only('create', 'store');
        $this->middleware('auth:admin')->only('edit', 'update', 'destroy', 'tickets');
    }

    public function index(Request $request)
    {
        $q = $request->get('q');

        $events = Event::whereApprove(true)->where(function ($query) use ($q) {
            foreach (['name', 'type', 'takes_place_at'] as $col) {
                $query = $query->orWhere($col, 'LIKE', "%$q%");
            }
        })->orderBy('created_at', 'desc')->get();

        return view('events')->with(compact('events', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'image' => 'required|image',
            'takes_place_at' => 'required|date',
            'tickets' => 'required|integer|min:1'
          ]);
        
        $image = $request->file('image');
        do {
            $image_name = str_random(134) . '.' . $image->getClientOriginalExtension();
        } while (Event::where('image', $image_name)->exists());
        $image->move(storage_path('uploads/events/images'), $image_name);

        //create event
        $event = new Event;
        $event->name = $request->input('name');
        $event->type = $request->input('type');
        $event->image = $image_name;
        $event->takes_place_at = new \Carbon\Carbon($request->input('takes_place_at'));
        $event->tickets = $request->input('tickets');
        $event->eo_id = auth()->user()->id;
        $event->save();

        return redirect('/events')->with('success', 'event has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        if (!$event->approve && !auth('admin')->check()) {
            if (!auth('eo')->check() || $event->eo_id != auth('eo')->id()) {
                throw ValidationException::withMessages([
                'approve' => 'The selected event is not yet approved'
            ]);
            }
        }
        return view('events.show')->with('event', $event);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('events.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $event->approve =true;
        $event->save();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        \Storage::delete('uploads/events/images/' . $event->image);
        $event->delete();
        return back();
    }

    public function reserve(Request $request, Event $event)
    {
        $ticket = auth()->user()->reserve($event);

        if ($request->has('pdf')) {
            $qrcode = \QrCode::format('png')->size(720)->generate($ticket);
            $image = base64_encode($qrcode);
            return \PDF::loadHTML("<img src='data:image/png;base64,$image'>")->download();
        }

        $qrcode = \QrCode::size(498)->generate($ticket);
        return view('events.ticket', compact('event', 'qrcode'));
    }

    public function cancel(Event $event)
    {
        if ($event->takes_place_at->lt(now()->addDays(2))) {
            throw ValidationException::withMessages([
                'takes_place_at' => 'You cannot cancel a reservation less than 48 hours before event start time.'
            ]);
        }

        \DB::transaction(function () use ($event) {
            auth()->user()->events()->detach($event);
            $event->tickets++;
            $event->save();
        });

        return back();
    }

    public function tickets(Event $event)
    {
        $html = "";
        foreach ($event->users as $user) {
            $qrcode = \QrCode::format('png')->size(720)->generate($user->pivot->ticket);
            $image = base64_encode($qrcode);
            $html .= "<img src='data:image/png;base64,$image'>";
        }
        return \PDF::loadHTML($html)->download();
    }
}
