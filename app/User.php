<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Validation\ValidationException;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class)->withPivot('ticket');
    }

    public function reserve(Event $event)
    {
        $reserved = $this->events()->whereId($event->id)->first(['id']);

        if ($reserved) {
            return $reserved->pivot->ticket;
        }

        if (!$event->takes_place_at->gt(now())) {
            throw ValidationException::withMessages([
                'takes_place_at' => 'The event already took place.'
            ]);
        }

        if (!$event->tickets) {
            throw ValidationException::withMessages([
                'tickets' => 'All tickets are sold out.'
            ]);
        }

        do {
            $ticket = str_random(134);
        } while (\DB::table('event_user')->whereTicket($ticket)->exists());
        
        \DB::transaction(function () use ($event, $ticket) {
            $this->events()->attach($event, compact('ticket'));
            $event->tickets--;
            $event->save();
        });

        return $ticket;
    }

    public function isReserved(Event $event)
    {
        return $this->events()->whereId($event->id)->exists();
    }
}
