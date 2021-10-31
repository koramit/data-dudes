<?php

namespace App\Actions;

use App\APIs\HannahAPI;
use App\Managers\AdmissionManager;
use App\Models\Admission;

class AdmissionListUpdater
{
    protected $LIMIT_CASES = 250;

    public function run()
    {
        $ans = $this->getList();

        $api = new HannahAPI;
        $manager = new AdmissionManager;

        $ans->each(function ($an) use ($api, $manager) {
            $admit = $api->getAdmission($an);

            if ($admit['found']) {
                $manager->manage($admit);
            }
        });
    }

    protected function getList()
    {
        $ans = Admission::select('an')
                        ->whereNull('dismissed_at')
                        ->orderBy('checked_at')
                        ->limit($this->LIMIT_CASES)
                        ->get()
                        ->pluck('an');

        return $ans;
    }
}
