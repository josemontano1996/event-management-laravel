<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Gate;
use Response;

class EventController extends Controller implements HasMiddleware
{
    use CanLoadRelationships, AuthorizesRequests;


    private array $relations = ['user', 'attendees', 'attendees.user'];

    public static function middleware(): array
    {
        return [
            new Middleware('throttle:60, 1', only: ['store', 'update', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {

        $this->authorize('viewAny', Event::class);

        $query = $this->loadRelationships(Event::query());

        return EventResource::collection($query->latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): EventResource
    {

        $this->authorize('create', Event::class);

        $validated_data = $request->validate(
            [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ]
        );

        $event = Event::create([...$validated_data, 'user_id' => 1]);

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): EventResource
    {
        $this->authorize('view', $event);

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event): EventResource
    {

        /*   if (Gate::denies('update-event', $event)) {
              abort(403, 'You are not authorized to update this event.');
          } */

        /*         Gate::authorize('update', $event);
         */

        $this->authorize('update', $event);

        $validated_data = $request->validate(
            [
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time',
            ]
        );

        $event->update($validated_data);

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): Response|ResponseFactory
    {
        $this->authorize('delete', $event);

        $event->delete();

        return response(status: 204);
    }


}
