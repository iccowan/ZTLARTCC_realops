<?php

namespace App\Console\Commands;

use App\Flight;
use Illuminate\Console\Command;

class GetAirlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Pull:GetAirlines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all of the airlines of flights';

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
        $airlines = array();

        $i = 0;
        foreach($flights as $f) {
            $air = substr($f->callsign, 0, 3);
            if(!in_array($air, $airlines)) {
                $airlines[$i] = $air;
                $i++;
            }
        }

        foreach($airlines as $a)
            print($a . ' ');

        print("\n");
    }
}
