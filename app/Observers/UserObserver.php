<?php

namespace App\Observers;

use App\Models\User;
use App\Services\UserCacheService;

class UserObserver
{
    public function created(User $user): void
    {
        app(UserCacheService::class)->add($user);
    }

    public function updated(User $user): void
    {
        app(UserCacheService::class)->update($user);
    }

    public function deleted(User $user): void
    {
        app(UserCacheService::class)->delete($user);
    }
}
