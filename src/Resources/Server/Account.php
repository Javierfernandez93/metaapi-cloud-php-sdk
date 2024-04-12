<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Server;

trait Account
{
    public function symbols(string $accountId = null): array|string
    {
        return $this->http->get("/users/current/accounts/{$accountId}/symbols");
    }
    
    public function orders(string $accountId = null): array|string
    {
        return $this->http->get("/users/current/accounts/{$accountId}/orders");
    }
    
    public function positions(string $accountId = null): array|string
    {
        return $this->http->get("/users/current/accounts/{$accountId}/positions");
    }
    
    public function accountInformation(string $accountId = null): array|string
    {
        return $this->http->get("/users/current/accounts/{$accountId}/account-information");
    }
    
    public function getSymbols(string $accountId = null): array|string
    {
        return $this->http->get("/users/current/accounts/{$accountId}/symbols");
    }

    public function credits(string $accountId = null): array|string
    {
        return $this->http->get("/users/current/accounts/{$accountId}/credits");
    }
}
