<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait StayNoteFormatable
{
    protected function formatNotes($notes)
    {
        return collect($notes)->map(function ($note) {
            $note = $note['doc'];

            $data['ref_id'] = $note['_id'];

            if ($note['cardType'] === 'order') {
                $data['type'] = 'order';
                $items = collect($note['ordersList']);
                $data['note'] = $items->pluck('name')->unique()->join(' | ');
                $data['date_note'] = $note['status'] === 2
                                        ? Carbon::createFromTimestamp($note['Tfinish'] / 1000)
                                        : null;
            } elseif ($note['cardType'] === 'consult') {
                $data['type'] = 'consult';
                $items = collect($note['consultDepartment']);
                $data['note'] = $items->pluck('name')->unique()->join(' | ');
                $data['date_note'] = $note['status'] === 2
                                        ? Carbon::createFromTimestamp($note['Tfinish'] / 1000)
                                        : null;
            } elseif ($note['cardType'] === 'note') {
                $data['type'] = 'note';
                $data['note'] = $note['text'];
                $data['date_note'] = isset($note['Tstart'])
                                        ? Carbon::createFromTimestamp($note['Tstart'] / 1000)
                                        : null;
            } elseif ($note['cardType'] === 'dispose') {
                $data['type'] = 'dispose';
                $data['note'] = $note['disposeType'].' '.$note['disposeData']['disposeDescription'];
                $data['date_note'] = $note['status'] === 2
                                        ? Carbon::createFromTimestamp($note['Tfinish'] / 1000)
                                        : null;
            } else {
                $data['type'] = $note['cardType'];
            }

            return $data;
        });
    }
}
