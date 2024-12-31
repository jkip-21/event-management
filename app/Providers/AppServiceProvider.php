<?php

namespace App\Providers;

use App\Models\Atendee;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    // using gates for authentication and authorization
    public function boot(): void {
        Gate::define('update-event', function($user, Event $event){
           return $user->id == $event->user_id;
        });

        Gate::define('delete-attendee', function($user,Event $event, Atendee $atendee){
            return $user->id == $event->user_id || $user->id == $atendee->user_id;
        }
    );

    }
}
