<?php

declare(strict_types=1);

namespace App;

use Redis;
use Psr\Log\LoggerInterface;

class CachedService
{
    public function __construct(
        private readonly Redis $redisClient,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function getClassicallyCachedValue(string $key, int $ttl = 10): int
    {
        $value = $this->redisClient->get($key);

        if ($value === false) {
            $value = $this->heavyComputationJob();

            $this->redisClient->setex($key, $ttl, $value);
        }

        return (int) $value;
    }

    public function getProbabilisticCachedValue(string $key, int $ttl = 10): int
    {
        $cachedData = $this->redisClient->hgetall($key);
        $value = $cachedData['value'] ?? null;
        $computationTime = $cachedData['computationTime'] ?? null;
        $expiry = $cachedData['expiry'] ?? null;

        if (!$value || ((($timeDelta = ($expiry - time())) < 3  * $computationTime) * (($prob = rand(0, 100)) > 95))) {

            if (!$value) {
                $this->logger->info("VALUE_NOT_FOUND");
            } else {
                $this->logger->info("PROBABILISTIC_COMPUTATION", [
                    'expiry' => $expiry,
                    'computation_time' => $computationTime,
                    'timeDelta' => $timeDelta,
                    'prob' => $prob,
                ]);
            }

            $start = time();
            $value = $this->heavyComputationJob();
            $computationTime = time() - $start;
            $expiry = time() + $ttl;

            $this->redisClient->hmset($key, array(
                'value' => $value,
                'computationTime' => $computationTime,
                'expiry' => $expiry,
            ));

            $this->redisClient->expireat($key, $expiry);
        }

        return (int)$value;
    }

    private function heavyComputationJob(): int
    {
        $this->logger->info("Heavy computation job was started");

        sleep(3);

        return random_int(0, 99);
    }
}
