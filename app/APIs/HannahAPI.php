<?php

namespace App\APIs;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HannahAPI
{
    public function getPatient($hn)
    {
        $data = $this->makePost('patient', ['hn' => $hn]);
        if (! $data || ! $data['ok']) { // error: $data = null
            return [
                'found' => false,
                'message' => __('service.failed'),
            ];
        }

        if (! isset($data['found']) || ! $data['found']) {
            $data['message'] = __('service.item_not_found', ['item' => 'HN']);
            unset($data['body']);

            return $data;
        }
        $data['marital_status_name'] = $data['marital_status'];
        unset($data['marital_status']);

        return $data;
    }

    public function getAdmission($an)
    {
        $data = $this->makePost('admission', ['an' => $an]);

        if (! isset($data['found'])) {
            $data['found'] = false;
        }

        if (! $data['found']) {
            return $data;
        }

        return $this->transformAdmission($data);
    }

    public function recentlyAdmission($hn)
    {
        $data = $this->makePost('patient-recently-admit', ['hn' => $hn]);

        if (isset($data['found']) && $data['found']) { // error: not found found
            return $this->transformAdmission($data);
        }

        $data['message'] = __('service.item_not_found', ['item' => 'admission']);
        if (isset($data['patient']) && $data['patient']['found']) { // error not found patient
            $data['patient']['marital_status_name'] = $data['patient']['marital_status'];
            unset($data['patient']['marital_status']);

            return $data;
        }

        $data['patient']['message'] = __('service.item_not_found', ['item' => 'HN']);

        return $data;
    }

    protected function makePost($url, $data)
    {
        $headers = ['app' => config('services.SUBHANNAH_API_NAME'), 'token' => config('services.SUBHANNAH_API_TOKEN')];
        try {
            $response = Http::timeout(2)
                            ->withHeaders($headers)
                            ->retry(5, 100, fn ($exception) => $exception instanceof ConnectionException)
                            ->post(config('services.SUBHANNAH_API_URL').$url, $data);
        } catch (Exception $e) {
            $errorsInAWhile = Cache::remember('connection-errors-in-a-while', 60, fn () => 0) + 1;
            Cache::increment('connection-errors-in-a-while');
            if ($errorsInAWhile > 3) {
                Log::error($url.'@hannah '.$e->getMessage());
            }

            return ['ok' => false];
        }

        if ($response->successful()) {
            return $response->json();
        }

        return ['ok' => false];
    }

    protected function transformAdmission(array $data)
    {
        $data['patient']['found'] = true;
        $data['attending_name'] = $data['attending'];
        $data['discharge_type_name'] = $data['discharge_type'];
        $data['discharge_status_name'] = $data['discharge_status'];
        $data['encountered_at'] = $data['admitted_at'] ? Carbon::parse($data['admitted_at'], 'asia/bangkok')->tz('UTC') : null;
        $data['dismissed_at'] = $data['discharged_at'] ? Carbon::parse($data['discharged_at'], 'asia/bangkok')->tz('UTC') : null;
        $data['patient']['marital_status_name'] = $data['patient']['marital_status'];
        unset(
            $data['attending'],
            $data['discharge_type'],
            $data['discharge_status'],
            $data['patient']['marital_status'],
            $data['admitted_at'],
            $data['discharged_at']
        );

        return $data;
    }
}
