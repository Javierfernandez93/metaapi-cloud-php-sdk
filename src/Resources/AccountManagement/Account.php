<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement;

trait Account
{
    public function balance(): array|string
    {
        return $this->http->get("/users/current/balance");
    }
    
    public function readById(string $accountId): array|string
    {
        return $this->http->get("/users/current/accounts/{$accountId}");
    }

    public function readAll(): array|string
    {
        return $this->http->get('/users/current/accounts');
    }

    public function symbols(string $accountId = null): array|string
    {
        return $this->http->get("/users/current/accounts/{$accountId}/symbols");
    }

    public function create(array $data): array|string
    {
        return $this->http->post('/users/current/accounts', $data);
    }

    public function update(string $accountId, array $data): array|string
    {
        return $this->http->put("/users/current/accounts/{$accountId}", $data);
    }

    public function unDeploy(string $accountId, bool $executeForAllReplicas = true): array|string
    {
        return $this->http->post("/users/current/accounts/{$accountId}/undeploy?executeForAllReplicas={$executeForAllReplicas}");
    }

    public function deploy(string $accountId, bool $executeForAllReplicas = true): array|string
    {
        return $this->http->post("/users/current/accounts/{$accountId}/deploy?executeForAllReplicas={$executeForAllReplicas}");
    }

    public function reDeploy(string $accountId, bool $executeForAllReplicas = true): array|string
    {
        return $this->http->post("/users/current/accounts/{$accountId}/redeploy?executeForAllReplicas={$executeForAllReplicas}");
    }

    public function delete(string $accountId): array|string
    {
        return $this->http->delete("/users/current/accounts/{$accountId}");
    }
    
    public function createMt4DemoAccount(string $profileId,array $data = null): array|string
    {
        return $this->http->post("/users/current/provisioning-profiles/{$profileId}/mt4-demo-accounts", $data);
    }

    public function createMt5DemoAccount(string $profileId,array $data = null): array|string
    {
        return $this->http->post("/users/current/provisioning-profiles/{$profileId}/mt5-demo-accounts", $data);
    }

    public function enableAccountFeatures(string $accountId,array $enabledAccountFeatures = null): array|string
    {
        return $this->http->post("/users/current/accounts/{$accountId}/enable-account-features", $enabledAccountFeatures);
    }

    public function getServers(string $version = null,string $query = null): array|string
    {
        return $this->http->get("/known-mt-servers/{$version}/search?query={$query}");
    }
}
