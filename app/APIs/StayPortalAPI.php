<?php

namespace App\APIs;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StayPortalAPI
{
    public function getQueue()
    {
        $data = $this->makePost('/stay/queue');
        if (! $data || ! ($data['ok'] ?? false)) {
            return false;
        }

        return $data['queue'];
    }

    public function getStatus($refId)
    {
        $data = $this->makePost('/stay/status/'.$refId);

        if (! $data || ! ($data['ok'] ?? false)) {
            return false;
        }

        return $data['status'];
    }

    public function getStatusNotes($refId)
    {
        $data = $this->makePost('/stay/status-notes/'.$refId);

        if (! $data || ! ($data['ok'] ?? false) || ! count($data['notes'] ?? [])) {
            return false;
        }

        return $data['notes'];
    }

    public function getOutcome($refId)
    {
        $data = $this->makePost('/stay/outcome/'.$refId);

        if (! $data || ! ($data['ok'] ?? false)) {
            return false;
        }

        return $data['outcome'];
    }

    public function getOutcomeNotes($refId)
    {
        $data = $this->makePost('/stay/outcome-notes/'.$refId);

        if (! $data || ! ($data['ok'] ?? false) || ! count($data['notes'] ?? [])) {
            return false;
        }

        return $data['notes'];
    }

    protected function makePost($url)
    {
        try {
            $response = Http::acceptJson()
                            ->timeout(4)
                            ->withToken(config('services.portal_access_token'))
                            ->retry(5, 100, fn ($exception) => $exception instanceof ConnectionException)
                            ->post(config('services.portal_base_url').$url);
        } catch (Exception $e) {
            $errorsInAWhile = Cache::remember('connection-errors-in-a-while', 60, fn () => 0) + 1;
            Cache::increment('connection-errors-in-a-while');
            if ($errorsInAWhile > 3) {
                Log::error($url.'@hannah '.$e->getMessage());
                Cache::put('connection-errors-in-a-while', 0);
            }

            return ['failed' => true];
        }

        if ($response->successful()) {
            return $response->json();
        }

        return ['failed' => true];
    }
}
