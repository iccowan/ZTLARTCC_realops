<?php

namespace App\Console\Commands;

use App\RwFlight;
use App\Flight;
use Illuminate\Console\Command;

class TransferFlights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Pull:TransferFlights';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfers all of the pulled flights';

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
        $flights = RwFlight::get();
        foreach($flights as $f) {
            $flight = Flight::where('callsign', $f->code)->first();
            if($flight == null) {
                $newf = new Flight();
                $newf->callsign = $f->code;
                $newf->departure = $f->depicao;
                $newf->arrival = $f->arricao;
                $newf->flight_plan = $f->route;
                $newf->dep_time = $f->deptime;
                $newf->arr_time = $f->arrtime;
                $newf->save();
            }
        }
    }
}
