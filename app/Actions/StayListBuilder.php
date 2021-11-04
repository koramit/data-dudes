<?php

namespace App\Actions;

use App\APIs\StayAPI;
use App\Models\Stay;

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
                           ->whereNull('dismissed_st')
                           ->get()
                           ->pluck('ref_id');

        $this->newStays($data, $activeStays);

        // return $stays;
    }

    protected function newStays(array $data, array $activeStays)
    {
        $stays = collect($data)->map(fn ($stay) => [
                                    'ref_id' => $stay['id'],
                                    'hn' => $stay['doc']['hn'],
                                    'encountered_at' => $stay['doc']['Tcheckin'] / 1000,
                                    'updated_at' => now(),
                                    'created_at' => now(),
                                ])
                               ->whereNotIn('ref_id', $activeStays);

        if (! $stays->count()) {
            return;
        }

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
}
