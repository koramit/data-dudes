<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait StayFormatable
{
    protected function formatStay($stay)
    {
        $stay = $stay['doc'] ?? $stay;

        return [
            'ref_id' => $stay['en'],
            'on_work_hour' => $stay['isInOfficeHour'],
            'hn' => $stay['hn'],
            'dob' => Carbon::create($stay['birthdate']),
            'name' => $stay['fname'],
            'gender' => ($stay['sex'] ?? '') === 'หญิง' ? 1 : 2,
            'anonymous' => $stay['anonymous'],
            'origin' => $stay['modeArrival'] ?? null,
            'chief_complaint' => $stay['cc'] ?? null,
            'zone_type' => $stay['zone'] ?? null,
            'zone_name' => $stay['zoneName'] ?? null,
            'tag_number' => $stay['position'] ?? null,
            'movement' => $stay['movementType'] ?? null,
            'severity_level' => $stay['acuityLVL'] ?? null,
            'insurance' => $stay['scheme'] ?? null,
            'triaged_at' => ($stay['TfinishTriage'] ?? false) ? Carbon::createFromTimestamp($stay['TfinishTriage'] / 1000) : null,
            'cpr' => $stay['CPR'] ?? false,
            'tube' => $stay['isTube'] ?? false,
            'observe' => $stay['isObserve'] ?? false,
            'diagnosis' => $stay['dx'] ?? null,
            'sbp' => $stay['bpSys'] ?? null,
            'dbp' => $stay['bpDias'] ?? null,
            'temperature_celsius' => $this->validateTemp($stay['temp'] ?? null),
            'pulse_per_minute' => $stay['pr'] ?? null,
            'respiration_rate_per_minute' => $stay['rr'] ?? null,
            'o2_sat' => $stay['o2'] ?? null,
            'vital_signs_at' => ($stay['vitalSignTime'] ?? null) ? Carbon::createFromTimestamp($stay['vitalSignTime'] / 1000) : null,
            'remark' => $stay['remark'] ?? null,
            'encountered_at' => Carbon::createFromTimestamp($stay['Tcheckin'] / 1000),
        ];
    }

    protected function validateTemp($value)
    {
        if (! $value || strlen($value) > 4 || ! is_numeric($value)) {
            return null;
        }

        return $value;
    }
}
