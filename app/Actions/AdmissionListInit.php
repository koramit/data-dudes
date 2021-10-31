<?php

namespace App\Actions;

use App\APIs\HannahAPI;
use App\Managers\AdmissionManager;
use App\Models\AdmitCall;

class AdmissionListInit
{
    public function run()
    {
        $api = new HannahAPI;
        $manager = new AdmissionManager;

        for ($an = 57611000; $an < 57617240; $an++) {
            $admit = $api->getAdmission($an);
            if (! $admit['found']) {
                AdmitCall::create(['an' => $an, 'retry' => 50]);
            } else {
                $manager->manage($admit);
            }

            if (($an % 1000) === 0) {
                echo "{$an}\n";
            }
        }

        for ($an = 57617240; $an < 57623880; $an++) {
            $admit = $api->getAdmission($an);
            if (! $admit['found']) {
                AdmitCall::create(['an' => $an, 'retry' => 50]);
            } else {
                $manager->manage($admit);
            }

            if (($an % 1000) === 0) {
                echo "{$an}\n";
            }
        }

        for ($an = 57623880; $an < 57629400; $an++) {
            $admit = $api->getAdmission($an);
            if (! $admit['found']) {
                AdmitCall::create(['an' => $an, 'retry' => 50]);
            } else {
                $manager->manage($admit);
            }

            if (($an % 1000) === 0) {
                echo "{$an}\n";
            }
        }

        for ($an = 57629400; $an <= 57635150; $an++) {
            $admit = $api->getAdmission($an);
            if (! $admit['found']) {
                AdmitCall::create(['an' => $an, 'retry' => 40]);
            } else {
                $manager->manage($admit);
            }

            if (($an % 1000) === 0) {
                echo "{$an}\n";
            }
        }
    }
}
