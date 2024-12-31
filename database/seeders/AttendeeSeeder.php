<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();
        $events = \App\Models\Event::all();

        foreach ($users as $user) {
            // each user can attend between 1 to 3 random events
            $eventsToAttend = $events->random(rand(1, 3));

            foreach($eventsToAttend as $event) {
                // Incase where no event is attended by an attendee
                \App\Models\Atendee::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id
                ]);
            }
        }
    }
}
