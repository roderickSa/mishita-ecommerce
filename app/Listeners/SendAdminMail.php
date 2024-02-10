<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Mail\UserCreated as MailUserCreated;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendAdminMail implements ShouldQueue
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
    public function handle(UserCreated $event): void
    {
        $super_user = User::Where('is_superadmin', '=', true)->first();

        Mail::to($super_user)->send(new MailUserCreated($event->email));
    }
}
