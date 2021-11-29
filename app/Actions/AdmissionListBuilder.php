<?php

namespace App\Actions;

use App\APIs\HannahAPI;
use App\Managers\AdmissionManager;
use App\Models\AdmitCall;

class AdmissionListBuilder
{
    protected $LIMIT_CASES = 25;

    public function run()
    {
        $ans = $this->getList();

        $api = new HannahAPI;
        $manager = new AdmissionManager;
        $founds = collect([]);
        for ($i = 0; $i < $ans->count(); $i++) {
            $admit = $api->getAdmission($ans[$i]);
            if (! $admit['found']) {
                $call = AdmitCall::whereAn($ans[$i])->first();
                $call->retry = $call->retry + 1;
                $call->save();
                continue;
            }

            $founds->push($ans[$i]);
            $manager->manage($admit);
        }

        AdmitCall::whereIn('an', $founds)->update(['found' => true]);
    }

    protected function getList()
    {
        $ans = AdmitCall::select(['an'])
                        ->whereFound(false)
                        ->where('retry', '<', 50) // ~ 1 day
                        ->limit($this->LIMIT_CASES)
                        ->get()
                        ->pluck('an');

        $count = $ans->count();
        if ($ans->count() === $this->LIMIT_CASES) {
            return $ans;
        }

        $max = AdmitCall::max('an');
        for ($i = 1; $i <= ($this->LIMIT_CASES - $count); $i++) {
            $an = $max + $i;
            $ans->push($an);
            AdmitCall::create(['an' => $an]);
        }

        return $ans;
    }
}
