<?php

namespace App\Console\Commands;

use App\Booking;
use App\User;
use App\Email;
use Illuminate\Console\Command;

/**
 * Class SendReminders
 * @package App\Console\Commands
 *
 * This will send reminders the night before the event. This will need to be
 * run MANUALLY. This will NOT run automatically.
 */

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Send:ReminderEmails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will send the reminder emails the night before the event.';

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
        // Get all of the bookings
        $bookings = Booking::get();

        foreach($bookings as $b) {
            // Send the pilot an email letting them know their booking was removed
            $pilot = User::find($b->pilot_id);
            $email = new Email();
            $email->email_address = $pilot->email;
            $email->subject = '[Reminder] Remember ZTL ARTCC Real Ops Tomorrow, June 29!';
            $email->view = 'emails.reminder';
            $email->sent = 0;
            $email->save();
        }
    }
}
