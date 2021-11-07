<?php

namespace App\Actions;

use App\APIs\StayAPI;
use App\APIs\StayHannahAPI;
use App\Models\Stay;
use App\Traits\StayFormatable;

class StayListBuilder
{
    use StayFormatable;

    public function run()
    {
        // $api = new StayAPI;
        $api = new StayHannahAPI;

        $data = $api->getQueue();

        if (! $data) {
            return;
        }

        $activeStays = Stay::select('ref_id')
                           ->whereNull('dismissed_at')
                           ->get()
                           ->pluck('ref_id');

        $this->newStays($data, $activeStays);

        $this->dismissStays($data, $activeStays);
    }

    protected function newStays(array $data, $activeStays)
    {
        $stays = collect($data)->filter(fn ($stay) => ! $activeStays->contains($stay['id']))
                               ->map(fn ($stay) => $this->formatStay($stay) + [
                                    'updated_at' => now(),
                                    'created_at' => now(),
                               ]);

        logger('new stay:'.$stays->count());
        if (! $stays->count()) {
            return;
        }

        Stay::insert($stays->values()->toArray());
    }

    protected function dismissStays(array $data, $activeStays)
    {
        $stays = collect($data)->map(fn ($stay) => [$stay['id']])->flatten();

        $dismissStays = $activeStays->filter(fn ($stay) => ! $stays->contains($stay));

        logger('dismiss stay:'.$dismissStays->count());
        if (! $dismissStays->count()) {
            return;
        }

        Stay::whereIn('ref_id', $dismissStays)
            ->update(['dismissed_at' => now()]);
    }
}
