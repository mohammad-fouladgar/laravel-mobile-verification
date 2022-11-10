<?php

namespace Fouladgar\MobileVerification\Tokens;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Manager;

class TokenRepositoryManager extends Manager
{

    public function getDefaultDriver()
    {
        return $this->config->get('mobile_verifier.token_storage', 'cache');
    }

    protected function createCacheDriver(): TokenRepositoryInterface
    {
        return new CacheTokenRepository(
            $this->config->get('mobile_verifier.token_lifetime', 5),
            $this->config->get('mobile_verifier.token_length', 5)
        );
    }

    protected function createDatabaseDriver(): TokenRepositoryInterface
    {
        return new DatabaseTokenRepository(
            $this->config->get('mobile_verifier.token_lifetime', 5),
            $this->config->get('mobile_verifier.token_length', 5),
            $this->config->get('mobile_verifier.token_table', 'mobile_verification_tokens'),
            $this->container->make(ConnectionInterface::class)
        );
    }
}
