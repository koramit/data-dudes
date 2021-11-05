<?php

namespace App\Actions;

use App\APIs\StayAPI;
use App\Models\Stay;
use Illuminate\Support\Carbon;

class StayListBuilder
{
    public function run()
    {
        $api = new StayAPI;

        $data = $api->getQueue();

        if (! $data) {
            return;
        }

        $activeStays = Stay::select('ref_id')
                           ->whereNull('dismissed_at')
                           ->get()
                           ->pluck('ref_id');

        // return collect($data)->filter(fn ($stay) => ! $activeStays->contains($stay['id']));

        $this->newStays($data, $activeStays);
        // $this->dismissStays($data, $activeStays->toArray());

        // return $stays;
    }

    protected function newStays(array $data, $activeStays)
    {
        $stays = collect($data)->filter(fn ($stay) => ! $activeStays->contains($stay['id']))
                               ->map(fn ($stay) => $this->formatStay($stay) + [
                                    'updated_at' => now(),
                                    'created_at' => now(),
                               ]);

        if (! $stays->count()) {
            return;
        }

        dd($stays);

        Stay::insert($stays->values()->toArray());
    }

    protected function dismissStays(array $data, array $activeStays)
    {
        $stays = collect($data)->map(fn ($stay) => [
                                    'ref_id' => $stay['id'],
                                ])
                                ->whereIn('ref_id', $activeStays)
                                ->pluck('ref_id');

        if (! $stays->count()) {
            return;
        }

        Stay::whereIn('ref_id', $stays)
            ->update(['dismissed_at' => now()]);
    }

    protected function formatStay($stay)
    {
        $stay = $stay['doc'] ?? $stay;

        return [
            'ref_id' => $stay['en'],
            'on_work_hour' => $stay['isInOfficeHour'],
            'hn' => $stay['hn'],
            'dob' => Carbon::create($stay['birthdate']),
            'name' => $stay['fname'],
            'gender' => ($stay['gender'] ?? '') === 'หญิง' ? 1 : 2,
            'anonymouse' => $stay['anonymouse'],
            'origin' => $stay['modeArrival'] ?? null,
            'chief_complaint' => $stay['cc'] ?? null,
            'zone_type' => $stay['zone'] ?? null,
            'zone_name' => $stay['zoneName'] ?? null,
            'tag_number' => $stay['position'] ?? null,
            'movement' => $stay['movementType'] ?? null,
            'severity_level' => $stay['acuityLVL'] ?? null,
            'insurance' => $stay['scheme'] ?? null,
            'triaged_at' => ($stay['TfinishTriage'] ?? false) ? Carbon::createFromTimestamp($stay['TfinishTriage'] / 1000) : null,
            'cpr' => $stay['CPR'] ?? null,
            'tube' => $stay['isTube'] ?? null,
            'observe' => $stay['isObserve'] ?? null,
            'diagnosis' => $stay['dx'] ?? null,
            'sbp' => $stay['bpSys'] ?? null,
            'dbp' => $stay['bpDias'] ?? null,
            'temperature_celsius' => $stay['temp'] ?? null,
            'pulse_per_minute' => $stay['pr'] ?? null,
            'respiration_rate_per_minute' => $stay['rr'] ?? null,
            'o2_sat' => $stay['o2'] ?? null,
            'vital_signs_at' => ($stay['vitalSignTime'] ?? null) ? Carbon::createFromTimestamp($stay['TfinishTriage'] / 1000) : null,
            'medicine_consulted_at' => ($stay['isConsultMed'] ?? false) ? now() : null,
            'remark' => 'remark' ?? null,
            'encountered_at' => Carbon::createFromTimestamp($stay['Tcheckin'] / 1000),
        ];
    }
}
