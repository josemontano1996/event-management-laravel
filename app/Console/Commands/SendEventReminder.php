<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Str;

class SendEventReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notifications to all event attendees whose event starts soon.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = Event::with('attendees.user')->whereBetween('start_time', [now(), now()->addDay()])->get();

        $eventCount = $events->count();

        $eventLabel = Str::plural('event', $eventCount);

        $this->info("Found {$eventCount} {$eventLabel}.");

        $events->each(fn($event) => $event->attendees->each(
            fn($attendee) => $this->info("Sending reminder to {$attendee->user->name} for event {$event->name}.")
        ));

        $this->info('Reminder notifications sent succesfully.');
    }
}
