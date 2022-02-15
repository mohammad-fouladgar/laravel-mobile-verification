<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tokens;

use Exception;
use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Illuminate\Support\Facades\Cache;

class CacheTokenRepository extends AbstractTokenRepository
{
    public function create(MustVerifyMobile $user): string
    {
        $mobile = $user->getMobileForVerification();

        $this->deleteExisting($user);

        $token = $this->createNewToken();

        $this->insert($mobile, $token);

        return $token;
    }

    public function deleteExisting(MustVerifyMobile $user): void
    {
        Cache::forget($user->getMobileForVerification());
    }

    public function exists(MustVerifyMobile $user, string $token): bool
    {
        return Cache::has($user->getMobileForVerification()) &&
            Cache::get($user->getMobileForVerification())['token'] === $token;
    }

    public function latestSentAt(MustVerifyMobile $user, string $token): string
    {
        $key = $user->getMobileForVerification();
        if (! Cache::has($key)) {
            return '';
        }

        return Cache::get($key)['sent_at'];
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    protected function insert(string $mobile, string $token): bool
    {
        return Cache::add($mobile, $this->getPayload($mobile, $token), now()->addMinutes($this->expires));
    }
}
