<?php

namespace Kconf;

use Psr\SimpleCache\CacheInterface;

class Cache implements CacheInterface
{
    public function get(string $key, mixed $default = null): mixed
    {
        $fileName = "/tmp/" . md5($key);
        if(!is_file($fileName)) {
            return null;
        }
        $f = json_decode(file_get_contents($fileName),true);

        if($f['expire'] > time()) {
            return $f['data'];
        }
        return null;
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        file_put_contents("/tmp/" . md5($key), json_encode([
            'expire' => time() + $ttl,
            'data' => $value
        ]));
        return true;
    }

    public function delete(string $key): bool
    {
        return true;
    }

    public function clear(): bool
    {
        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        return [];
    }

    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        return true;
    }

    public function has(string $key): bool
    {
        return true;
    }
}