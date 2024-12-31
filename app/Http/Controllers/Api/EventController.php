<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    use CanLoadRelationships;
    /**
     * Display a listing of the resource.
     */
    private array $relations = ['user', 'attendees', 'attendees.user'];

    // protecting routes
    public function __construct()
    {
        // $this->authorizeResource(Event::class, 'event');
    }

    public function index()
    {
        $relations =
        $query = $this->loadRelationships(Event::query());


        return EventResource::collection(
            $query->latest()->paginate()
        );
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $event = Event::create(
            [
                ...$request->validate([
                    'name' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'start_time' => 'required|date',
                    'end_time' => 'required|date|after:start_time',
                ]),
                'user_id' => $request->user()->id
            ]
        );
        return new EventResource($this->loadRelationships($event));
    }


    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Event $event)
    {
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {

        if(Gate::denies('update-event', $event)){
            abort(403, 'You are not allowed to update');
        }
        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time',
            ]),
        );

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response(status: 204);
    }
}
