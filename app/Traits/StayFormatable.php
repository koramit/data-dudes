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
            'dob' => ($stay['birthdate'] ?? null) ? Carbon::create($stay['birthdate']) : null,
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
            'diagnosis' => $this->validateString($stay['dx'] ?? null),
            'sbp' => $this->validateVitalSign($stay['bpSys'] ?? null),
            'dbp' => $this->validateVitalSign($stay['bpDias'] ?? null),
            'temperature_celsius' => $this->validateTemp($stay['temp'] ?? null),
            'pulse_per_minute' => $this->validateVitalSign($stay['pr'] ?? null),
            'respiration_rate_per_minute' => $this->validateVitalSign($stay['rr'] ?? null),
            'o2_sat' => $this->validateVitalSign($stay['o2'] ?? null),
            'vital_signs_at' => ($stay['vitalSignTime'] ?? null) ? Carbon::createFromTimestamp($stay['vitalSignTime'] / 1000) : null,
            'remark' => $stay['remark'] ?? null,
            'encountered_at' => Carbon::createFromTimestamp($stay['Tcheckin'] / 1000),
        ];
    }

    protected function validateTemp($value)
    {
        if (! $value || strlen($value) > 4 || ! is_numeric($value) || $value > 99) {
            return null;
        }

        return $value;
    }

    protected function validateVitalSign($value)
    {
        if (! $value || strlen($value) > 3 || ! is_int($value) || $value > 255) {
            return null;
        }

        return $value;
    }

    protected function validateString($value)
    {
        if (! $value) {
            return null;
        }

        $value = preg_replace('!\s+!', ' ', $value);

        if (strlen($value) > 255) {
            return substr($value, 0, 254);
        }

        return $value;
    }
}
