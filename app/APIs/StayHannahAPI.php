<?php

namespace App\APIs;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StayHannahAPI
{
    public function getQueue()
    {
        $data = $this->makePost('stay-queue');
        if (! $data || isset($data['failed'])) {
            return false;
        }

        return $data;
    }

    public function getStatus($refId)
    {
        $data = $this->makePost('stay-status/'.$refId);

        if (! $data || isset($data['failed'])) {
            return false;
        }

        if (! isset($data['en']) || ! $data['en']) {
            return false;
        }

        return $data;
    }

    public function getStatusNotes($refId)
    {
        $data = $this->makePost('stay-status-notes/'.$refId);

        if (! $data || isset($data['failed'])) {
            return false;
        }

        return $data;
    }

    public function getOutcome($refId)
    {
        $data = $this->makePost('stay-outcome/'.$refId);

        if (! $data || isset($data['failed'])) {
            return false;
        }

        if (! isset($data['en']) || ! $data['en']) {
            return false;
        }

        return $data;
    }

    public function getOutcomeNotes($refId)
    {
        $data = $this->makePost('stay-outcome-notes/'.$refId);

        if (! $data || isset($data['failed'])) {
            return false;
        }

        return $data;
    }

    protected function makePost($url)
    {
        $headers = ['app' => config('services.SUBHANNAH_API_NAME'), 'token' => config('services.SUBHANNAH_API_TOKEN')];
        try {
            $response = Http::timeout(2)
                            ->withHeaders($headers)
                            ->retry(5, 100, fn ($exception) => $exception instanceof ConnectionException)
                            ->get(config('services.SUBHANNAH_API_URL').$url);
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
