<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use Victorycodedev\MetaapiCloudPhpSdk\Resources\Server\Account;

class ServerApi
{
    use Account;

    public Http $http;

    public string $serverUrl = 'https://mt-client-api-v1.new-york.agiliumtrade.ai';

    public function __construct(private string $token, string $serverUrl = null)
    {
        $this->token = $token;
        $this->serverUrl = $serverUrl ?? $this->serverUrl;
        $this->http = new Http($this->token, $this->serverUrl);
    }
}
