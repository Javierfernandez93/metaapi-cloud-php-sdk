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

    public function existStrategy(string $strategyId = null): array|string
    {
        $response = $this->strategy($strategyId);

        if(!$response)
        {
            return false;
        }

        return true;
    }

    public function createStrategy(array $data = null): array|string
    {
        $strategyId = $this->generateStrategyId()['id'];

        $this->http->put("/users/current/configuration/strategies/{$strategyId}", $data);

        return $strategyId;
    }

    public function copy(array $data = null): array|string
    {
        try {
            $account = new AccountApi($this->token);

            $masterAccount = $account->readById($data['providerAccountId']);
            $slaveAccount = $account->readById($data['subscriberAccountId']);

            if (!in_array('PROVIDER', $masterAccount['copyFactoryRoles'])) {
                throw new \Exception((string) json_encode([
                    'message' => 'Account ' . $data['providerAccountId'] . ' is not a provider account. Please specify PROVIDER copyFactoryRoles value in your MetaApi account in order to use it in CopyFactory API'
                ]));
            }
            
            if (!in_array('SUBSCRIBER', $slaveAccount['copyFactoryRoles'])) {
                throw new \Exception((string) json_encode([
                    'message' => 'Account ' . $data['subscriberAccountId'] . ' is not a subscriber account. Please specify SUBSCRIBER copyFactoryRoles value in your MetaApi account in order to use it in CopyFactory API'
                ]));
            }

            if(!$this->existStrategy($data['strategyId']))
            {
                throw new \Exception((string) json_encode([
                    'message' => 'Strategy ' . $data['strategyId'] . ' does not exist'
                ]));
            }

            // create a strategy being copied
            // $this->http->put("/users/current/configuration/strategies/{$strategyId}", [
            //     'name'        => 'Test strategy',
            //     'description' => 'Some useful description about your strategy',
            //     'accountId'   => $masterAccount['_id'],
            // ]);

            try {
                $response = $this->suscriber($data['subscriberAccountId']);

                if($response)
                {
                    $data['copyTrading']['subscriptions'] = self::sanitizeSuscriptions($response['subscriptions'],$data['copyTrading']['subscriptions'][0]);
                }
            } catch (\Throwable $th) {
                
            }
        
            $response = $this->updateSubscriber($slaveAccount['_id'], $data['copyTrading']);

            if(!$response)
            {
                return ['message' => 'Copy trade not created successfully'];
            }

            if(!$response['success'])
            {
                return ['message' => $response['message']];
            }

            return  ['message' => 'Copy trade created successfully'];
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public static function sanitizeSuscriptions(array $currentSubscriptions = null,array $subscription = null)
    {
        $subscriptions = [];

        if(sizeof($currentSubscriptions))
        {
            $key = array_search($subscription['strategyId'], array_column($currentSubscriptions, 'strategyId'));

            if($key !== false)
            {
                $currentSubscriptions[$key] = [...$currentSubscriptions[$key],...$subscription];
            } else {
                $currentSubscriptions[] = $subscription;
            }

            $subscriptions = $currentSubscriptions;
        } else {
            $subscriptions[] = $subscription;
        }

        return $subscriptions;
    }
}
