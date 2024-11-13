<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view all the events
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view an event
     */
    public function view(?User $user, Event $event): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create an event.
     * As the User parameter is not nullable, the user must be authenticated to create an event.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update an event.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->id === $event->user_id;
    }

    /**
     * Determine whether the user can delete and event.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->id === $event->user_id;
    }

    /**
     * Determine whether the user can restore and event.
     */
    public function restore(User $user, Event $event): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete and event.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        //
    }
}
