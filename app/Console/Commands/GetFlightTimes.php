<?php

namespace App\Console\Commands;

use App\Flight;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetFlightTimes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Pull:GetFlightTimes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates flight times for all of the flights.';

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
        $flights = Flight::get();

        foreach($flights as $f) {
            $dep_time = new Carbon($f->dep_time);
            $arr_time = new Carbon($f->arr_time);

            $diff_in_time = number_format($dep_time->diffInMinutes($arr_time) / 60, 3);
            $f->flight_time = $diff_in_time;
            $f->save();
        }
    }
}
