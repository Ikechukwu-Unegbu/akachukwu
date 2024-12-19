<?php

namespace App\Listeners;

use Livewire\Component;
use App\Events\RateLimitExceeded;
use Illuminate\Support\Facades\Session;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleRateLimitExceeded
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RateLimitExceeded $event): void
    {
        Session::flash('error', $event->message);
    }
}
