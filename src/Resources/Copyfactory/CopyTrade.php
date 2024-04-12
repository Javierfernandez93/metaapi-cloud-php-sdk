<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory;

use Victorycodedev\MetaapiCloudPhpSdk\AccountApi;

trait CopyTrade
{
    use Configuration;

    public function logs(string $strategyId = null): array|string
    {
        return $this->http->get("/users/current/strategies/{$strategyId}/user-log");
    }

    public function suscribers(): array|string
    {
        return $this->http->get("/users/current/configuration/subscribers");
    }
    
    public function removeSuscription(string $id = null,string $strategyId = null,array $data = null): array|string
    {
        return $this->http->delete("/users/current/configuration/subscribers/{$id}/subscriptions/{$strategyId}",$data);
    }

    public function stream(string $subscriberId = null): array|string
    {
        return $this->http->get("/users/current/subscribers/{$subscriberId}/transactions/stream");
    }

    public function suscriber(string $id = null): array|string
    {
        return $this->http->get("/users/current/configuration/subscribers/{$id}");
    }

    public function copy(string $providerAccount, string $subscriberAccount, string $strategyId = null,string $name = null,float $multiplier = null): array|string
    {
        try {
            $account = new AccountApi($this->token);

            $masterMetaapiAccount = $account->readById($providerAccount);
            $slaveMetaapiAccount = $account->readById($subscriberAccount);


            if (!in_array('PROVIDER', $masterMetaapiAccount['copyFactoryRoles'])) {
                $response = "{'message': 'Account {$providerAccount} is not a provider account. Please specify PROVIDER copyFactoryRoles value in your MetaApi account in order to use it in CopyFactory API'}";

                throw new \Exception((string) $response);
            }

            if (!in_array('SUBSCRIBER', $slaveMetaapiAccount['copyFactoryRoles'])) {
                $response = "{'message': 'Account {$subscriberAccount} is not a subscriber account. Please specify SUBSCRIBER copyFactoryRoles value in your MetaApi account in order to use it in CopyFactory API'}";

                throw new \Exception((string) $response);
            }

            // get strategy ID
            if (empty($strategyId)) {
                $strategies = $this->strategies();
                $strategy = [];

                foreach ($strategies as $value) {
                    if ($value['accountId'] == $masterMetaapiAccount['_id']) {
                        $strategy = $value;
                        break;
                    }
                }

                if (!empty($strategy)) {
                    $strategyId = $strategy['_id'];
                } else {
                    $strategyId = $this->generateStrategyId()['id'];
                }
            }

            // create a strategy being copied
            // $this->http->put("/users/current/configuration/strategies/{$strategyId}", [
            //     'name'        => 'Test strategy',
            //     'description' => 'Some useful description about your strategy',
            //     'accountId'   => $masterMetaapiAccount['_id'],
            // ]);

            // create subscriber
            $this->updateSubscriber($slaveMetaapiAccount['_id'], [
                'name' => $name,
                'subscriptions' => [
                    [
                        'strategyId' => $strategyId,
                        'multiplier' => $multiplier,
                    ],
                ],
            ]);

            return  ['message' => 'Copy trade created successfully'];
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}
