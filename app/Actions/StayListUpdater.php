<?php

namespace App\Actions;

use App\APIs\StayPortalAPI;
use App\Models\Stay;
use App\Models\StayNote;
use App\Traits\StayFormatable;
use App\Traits\StayNoteFormatable;

class StayListUpdater
{
    use StayFormatable, StayNoteFormatable;

    public function run()
    {
        $activeStays = Stay::select(['id', 'ref_id', 'medicine_consulted_at'])
                           ->whereNull('dismissed_at')
                           ->get();

        $api = new StayPortalAPI;
        $upsertNotes = [];
        foreach ($activeStays as $stay) {
            if (! $data = $api->getStatus($stay->ref_id)) {
                continue;
            }

            $stay->update($this->formatStay($data));

            if (! $notes = $api->getStatusNotes($stay->ref_id)) {
                continue;
            }

            $stayNotes = $this->formatNotes($notes)
                            ->filter(fn ($note) => collect(['order', 'consult', 'note', 'dispose'])->contains($note['type']))
                            ->values()
                            ->map(fn ($note) => ['stay_id' => $stay->id] + $note);

            if (! $stayNotes->count()) {
                continue;
            }

            if (! $stay->medicine_consulted_at) {
                $reversed = $stayNotes->reverse()->values();
                $index = $reversed->search(fn ($n) => $n['type'] === 'consult' && str_contains($n['note'], 'med'));
                if ($index !== false) {
                    $stay->update(['medicine_consulted_at' => $reversed[$index]['date_note']]);
                }
            }

            foreach ($stayNotes as $note) {
                $upsertNotes[] = $note;
            }
        }
        StayNote::upsert($upsertNotes, ['ref_id'], ['note', 'date_note']);
    }
}
