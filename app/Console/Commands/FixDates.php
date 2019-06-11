<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Flight;
use Illuminate\Console\Command;

class FixDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Pull:FixDates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix the dates for all of the flights';

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
            $dep_day = new Carbon($f->dep_time);
            $arr_day = new Carbon($f->arr_time);
            $dep_day = $dep_day->format('N');
            $arr_day = $arr_day->format('N');
            if($dep_day == 6)
                $dep_time = new Carbon('2019-06-29' . ' ' . substr($f->dep_time, 11));
            elseif($dep_day == 7)
                $dep_time = new Carbon('2019-06-30' . ' ' . substr($f->dep_time, 11));
            $dep_time = $dep_time->toDateTimeString();

            if($arr_day == 6)
                $arr_time = Carbon::parse('2019-06-29' . ' ' . substr($f->arr_time, 11));
            elseif($arr_day == 7)
                $arr_time = Carbon::parse('2019-06-30' . ' ' . substr($f->arr_time, 11));

            $arr_time = $arr_time->toDateTimeString();

            $f->dep_time = $dep_time;
            $f->arr_time = $arr_time;
            $f->save();
        }
    }
}
