<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

class UserCacheService
{
    private const USERS_API_CACHE_KEY = 'users.api.all.v2';
    private const USERS_WEB_CACHE_KEY = 'users.web.all.v2';
    private const USERS_CACHE_SECONDS = 300;

    public function apiUsers(): array
    {
        return $this->cache()->remember(
            self::USERS_API_CACHE_KEY,
            self::USERS_CACHE_SECONDS,
            fn () => User::get()->toArray()
        );
    }

    public function webUsers(): array
    {
        return $this->cache()->remember(
            self::USERS_WEB_CACHE_KEY,
            self::USERS_CACHE_SECONDS,
            fn () => User::latest()
                ->get(['id', 'name', 'email', 'username', 'address', 'created_at'])
                ->map(fn (User $user) => $this->webCacheData($user))
                ->all(),
        );
    }

    public function add(User $user): void
    {
        $cache = $this->cache();

        if ($cache->has(self::USERS_API_CACHE_KEY)) {
            $users = $cache->get(self::USERS_API_CACHE_KEY, []);
            $users[] = $user->toArray();
            $cache->put(self::USERS_API_CACHE_KEY, $users, self::USERS_CACHE_SECONDS);
        }

        if ($cache->has(self::USERS_WEB_CACHE_KEY)) {
            $users = $cache->get(self::USERS_WEB_CACHE_KEY, []);
            array_unshift($users, $this->webCacheData($user));
            $cache->put(self::USERS_WEB_CACHE_KEY, $users, self::USERS_CACHE_SECONDS);
        }
    }

    public function update(User $user): void
    {
        $cache = $this->cache();

        if ($cache->has(self::USERS_API_CACHE_KEY)) {
            $users = collect($cache->get(self::USERS_API_CACHE_KEY, []))
                ->map(fn (array $cachedUser) => (int) $cachedUser['id'] === $user->id ? $user->toArray() : $cachedUser)
                ->all();

            $cache->put(self::USERS_API_CACHE_KEY, $users, self::USERS_CACHE_SECONDS);
        }

        if ($cache->has(self::USERS_WEB_CACHE_KEY)) {
            $users = collect($cache->get(self::USERS_WEB_CACHE_KEY, []))
                ->map(fn (array $cachedUser) => (int) $cachedUser['id'] === $user->id ? $this->webCacheData($user) : $cachedUser)
                ->all();

            $cache->put(self::USERS_WEB_CACHE_KEY, $users, self::USERS_CACHE_SECONDS);
        }
    }

    public function delete(User $user): void
    {
        $cache = $this->cache();

        if ($cache->has(self::USERS_API_CACHE_KEY)) {
            $users = collect($cache->get(self::USERS_API_CACHE_KEY, []))
                ->reject(fn (array $cachedUser) => (int) $cachedUser['id'] === $user->id)
                ->values()
                ->all();

            $cache->put(self::USERS_API_CACHE_KEY, $users, self::USERS_CACHE_SECONDS);
        }

        if ($cache->has(self::USERS_WEB_CACHE_KEY)) {
            $users = collect($cache->get(self::USERS_WEB_CACHE_KEY, []))
                ->reject(fn (array $cachedUser) => (int) $cachedUser['id'] === $user->id)
                ->values()
                ->all();

            $cache->put(self::USERS_WEB_CACHE_KEY, $users, self::USERS_CACHE_SECONDS);
        }
    }

    private function cache(): Repository
    {
        return Cache::store('redis');
    }

    private function webCacheData(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
            'address' => $user->address,
            'created_at' => $user->created_at?->format('d M Y'),
        ];
    }
}
