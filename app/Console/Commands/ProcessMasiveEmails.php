<?php

namespace App\Console\Commands;

use App\Mail\UserCreated;
use App\Models\SendMasiveMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ProcessMasiveEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-masive-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails registered';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SendMasiveMail::select('id', 'email', 'sent')->where('sent', '=', 0)->chunkById(50, function ($emails) {
            foreach ($emails as $key => $email) {
                Mail::to($email->email)->send(new UserCreated($email->email));
                $email->sent = 1;
                $email->save();
            }
        });
    }
}
