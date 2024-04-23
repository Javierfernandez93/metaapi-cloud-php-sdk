<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use Victorycodedev\MetaapiCloudPhpSdk\Resources\Billing\Billing;

class BillingApi
{
    use Billing;
    public Http $http;

    public string $serverUrl = 'https://billing-api-v1.agiliumtrade.agiliumtrade.ai';

    public function __construct(private string $token, string $serverUrl = null)
    {
        $this->token = $token;
        $this->serverUrl = $serverUrl ?? $this->serverUrl;
        $this->http = new Http($this->token, $this->serverUrl);
    }
}
