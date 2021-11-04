<?php

namespace App\APIs;

use Exception;
use Illuminate\Support\Facades\Http;

class StayAPI
{
    public function getQueue()
    {
        $data = $this->makePost(config('services.STAY_QUEUE_URL'));
        if (! $data || ! $data['rows']) {
            return false;
        }

        return $data['rows'];
    }

    public function getStatus($refId)
    {
        $url = str_replace('REF_ID', $refId, config('services.STAY_STATUS_URL'));
        $data = $this->makePost($url);
        if (! $data || ! $data['en']) {
            return false;
        }

        return $data;
    }

    public function getStatusNotes($refId)
    {
        $url = str_replace('REF_ID+1', $refId + 1, config('services.STAY_STATUS_NOTES_URL'));
        $url = str_replace('REF_ID', $refId, $url);
        $data = $this->makePost($url);
        if (! $data || ! $data['rows']) {
            return false;
        }

        return $data['rows'];
    }

    public function getOutcome($refId)
    {
        $url = str_replace('REF_ID', $refId, config('services.STAY_OUTCOME_URL'));
        $data = $this->makePost($url);
        if (! $data || ! $data['en']) {
            return false;
        }

        return $data;
    }

    public function getOutcomeNotes($refId)
    {
        $url = str_replace('REF_ID+1', $refId + 1, config('services.STAY_OUTCOME_NOTES_URL'));
        $url = str_replace('REF_ID', $refId, $url);
        $data = $this->makePost($url);
        if (! $data || ! $data['rows']) {
            return false;
        }

        return $data['rows'];
    }

    protected function makePost($url)
    {
        try {
            $response = Http::acceptJson()
                        ->withHeaders([
                            'authorization' => 'Basic '.config('services.STAY_TOKEN'),
                        ])
                        ->timeout(4)
                        ->retry(5, 100)
                        ->get($url);
        } catch (Exception $e) {
            return false;
        }

        if ($response->ok()) {
            return $response->json();
        }

        return false;
    }
}
