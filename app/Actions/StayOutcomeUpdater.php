<?php

namespace App\Actions;

use App\APIs\StayHannahAPI;
use App\Models\Stay;
use App\Models\StayNote;
use App\Traits\StayFormatable;
use App\Traits\StayNoteFormatable;
use Illuminate\Support\Carbon;

class StayOutcomeUpdater
{
    use StayFormatable, StayNoteFormatable;

    public function run()
    {
        $dismissedStays = Stay::select(['id', 'ref_id'])
                              ->whereNull('outcome')
                              ->whereNotNull('dismissed_at')
                              ->get();

        $api = new StayHannahAPI;
        $upsertNotes = [];
        foreach ($dismissedStays as $stay) {
            if (! $data = $api->getOutcome($stay->ref_id)) {
                continue;
            }

            $formatted = $this->formatStay($data);
            $formatted['outcome'] = $data['dispose'];
            $formatted['dismissed_at'] = isset($data['disposeTimeActual'])
                                            ? Carbon::createFromTimestamp($data['disposeTimeActual'] / 1000)
                                            : Carbon::createFromTimestamp($data['disposeTime'] / 1000);

            $stay->update($formatted);

            if (! $notes = $api->getOutcomeNotes($stay->ref_id)) {
                continue;
            }

            $stayNotes = $this->formatNotes($notes)
                            ->filter(fn ($note) => collect(['order', 'consult', 'note', 'dispose'])->contains($note['type']))
                            ->values()
                            ->map(fn ($note) => ['stay_id' => $stay->id] + $note);

            if (! $stayNotes->count()) {
                continue;
            }

            foreach ($stayNotes as $note) {
                $upsertNotes[] = $note;
            }
        }
        StayNote::upsert($upsertNotes, ['ref_id'], ['note', 'date_note']);
    }
}
