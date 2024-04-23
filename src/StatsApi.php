<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use Victorycodedev\MetaapiCloudPhpSdk\Resources\Stats\Stats;

class StatsApi
{
    use Stats;

    public Http $http;

    public string $serverUrl = 'https://metastats-api-v1.new-york.agiliumtrade.ai/api-docs.json';

    public function __construct(private string $token, string $serverUrl = null)
    {
        $this->token = $token;
        $this->serverUrl = $serverUrl ?? $this->serverUrl;
        $this->http = new Http($this->token, $this->serverUrl);
    }
}
