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
            $value = $this->heavyComputedJob();

            $this->redisClient->setex($key, $ttl, $value);
        }

        return (int) $value;
    }

    private function heavyComputedJob(): int
    {
        $this->logger->info("Heavy computed job was started");

        sleep(5);

        return random_int(0, 99);
    }
}
