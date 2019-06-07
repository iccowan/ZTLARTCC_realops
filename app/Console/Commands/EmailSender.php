<?php

namespace App\Console\Commands;

use App\Email;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Mail;

class EmailSender extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends the emails in the queue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get all of the emails
        $emails = Email::where('sent', 0)->get();

        // Loop through each email and send it
        foreach($emails as $e) {
            $user = User::where('email', $e->email_address)->first();
            if($user->email != 'Bad email') {
                try {
                    Mail::send($e->view, ['email' => $e], function ($m) use ($e) {
                        $m->from('realops@notams.ztlartcc.org', 'ZTL ARTCC Real Ops');
                        $m->to($e->email_address)->subject($e->subject);
                    });
                } catch (\Exception $except) {
                    $user = User::where('email', $e->email_address)->first();
                    $user->email = 'Bad email';
                    $user->save();
                }
            }

            // Set the email as sent
            $e->sent = 1;
            $time_now = Carbon::now();
            $e->sent_at = $time_now;
            $e->save();
        }
    }
}
