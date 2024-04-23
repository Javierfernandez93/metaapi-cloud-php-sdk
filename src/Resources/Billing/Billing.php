<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Billing;

trait Billing
{
    public function balance(): array|string
    {
        return $this->http->get("/users/current/balance");
    }
}
