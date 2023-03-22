<?php

namespace App\Console\Commands;

use App\APIs\HannahAPI;
use App\Models\Admission;
use App\Models\Ward;
use Illuminate\Console\Command;

class FillWardNo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fill-ward-no';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $api = new HannahAPI;
        Ward::query()
            ->whereNull('no')
            ->each(function ($w)  use($api){
                if (!$admission = Admission::query()->where('ward_id', $w->id)->first()) {
                    return;
                }

                $admissionData = $api->getAdmission($admission->an);
                if (! $admissionData['found']) {
                    return;
                }

                return $w->update(['no' => $admissionData['ward_no']]);
            });

        return 0;
    }
}
