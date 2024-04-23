<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Stats;

trait Stats
{
    public function stats(string $accountId = null): array|string
    {
        return $this->http->get("/users/current/accounts/{$accountId}/metrics");
    }
  
    public function metrics(string $accountId = null): array|string
    {   
        return $this->http->get("/users/current/accounts/{$accountId}/metrics");
    }

    public function historicalTrades(string $accountId = null,string $startTime = null,string $endTime = null): array|string
    {
        return $this->http->get("/users/current/accounts/{$accountId}/historical-trades/{$startTime}/{$endTime}");
    }
    
    public function openTrades(string $accountId = null): array|string
    {
        return $this->http->get("/users/current/accounts/{$accountId}/open-trades");
    }
}
