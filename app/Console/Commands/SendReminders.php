<?php

namespace App\Console\Commands;

use App\Notifications\EventReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

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
    protected $description = 'Sends Notification to all events attendees that the events starts soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // filter through the events to check events which starts in 24 hours
        $events = \App\Models\Event::with('attendees.user')
            ->whereBetween('start_time', [now(), now()->addDay()])
            ->get();

        $eventCount = $events->count();
        $eventLabel = Str::plural('event', $eventCount);

        $this->info("Found {$eventCount} {$eventLabel}");

        // iterate over all the events and to tell every single attendee that a particular event is due in 24 hrs

        $events->each(fn($event) => $event->attendees->each(
            fn($attendee) =>
            $attendee->user->notify(
                new EventReminderNotification(
                    $event
                )
            )
        ));

        $this->info('Reminder notification sent successfully');
    }
}
