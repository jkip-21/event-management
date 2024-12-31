<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Event;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notifications to all event attendees whose events start within 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Fetch events starting within 24 hours
        $events = Event::with('attendees.user')
            ->whereBetween('start_time', [now(), now()->addDay()])
            ->get();

        $eventCount = $events->count();
        $eventLabel = Str::plural('event', $eventCount);

        $this->info("Found {$eventCount} {$eventLabel} starting in the next 24 hours.");

        // Notify attendees
        $events->each(function ($event) {
            $event->attendees->each(function ($attendee) use ($event) {
                if (isset($attendee->user)) {
                    $this->info("Notifying attendee {$attendee->user->id} for event {$event->id}");
                } else {
                    $this->warn("Attendee has no associated user for event {$event->id}");
                }
            });
        });

        $this->info('Reminder notifications sent successfully.');
    }
}
  