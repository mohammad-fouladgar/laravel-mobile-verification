<?php

namespace Fouladgar\MobileVerification\Tokens;

use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Illuminate\Support\Facades\Cache;

class CacheTokenRepository extends AbstractTokenRepository
{
    public function create(MustVerifyMobile $user): string
    {
        $mobile = $user->getMobileForVerification();

        $this->deleteExisting($user);

        $token = $this->createNewToken();

        $this->insertIntoStorageDriver($mobile, $token);

        return $token;
    }

    public function deleteExisting(MustVerifyMobile $user): ?int
    {
        return Cache::forget($user->getMobileForVerification());
    }

    /**
     * @inheritDoc
     */
    protected function insertIntoStorageDriver($mobile, $token): bool
    {
        return Cache::add($mobile, $this->getPayload($mobile, $token), now()->addMinutes($this->expires));
    }

    public function exists($user, $token): bool
    {
        return Cache::has($user->getMobileForVerification()) &&
            Cache::get($user->getMobileForVerification())['token'] == $token;
    }
}
