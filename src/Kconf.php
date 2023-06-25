<?php

namespace Kconf;

use Exception;
use Psr\SimpleCache\CacheInterface;

class Kconf
{
    public $needCache = true;
    protected $app;
    protected $env;
    protected $password;
    /** @var CacheInterface $cache */
    protected $cache;
    private $url = "https://kconf.kphcdr.com/api/config?appkey=%s&env=%s&password=%s";

    public function __construct(?CacheInterface $cache, $app, $env, $password = "")
    {
        if(is_null($cache)) {
            $this->cache = new Cache();
        } else {
            $this->cache = $cache;
        }
        $this->app = $app;
        $this->env = $env;
        $this->password = $password;
    }

    /**
     * @param bool $needCache
     * @return Kconf
     */
    public function setNeedCache(bool $needCache): Kconf
    {
        $this->needCache = $needCache;
        return $this;
    }

    public function try()
    {
        if (!$this->needCache) {
            return $this->do();
        }
        $key = $this->cacheKey();
        $c = $this->cache->get($this->cacheKey());
        if (is_null($c)) {
            $c = $this->do();
            $this->cache->set($key, $c, 86400);
        }

        return $c;
    }

    private function do()
    {
        $url = sprintf($this->url, $this->app, $this->env, $this->password);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt_array($ch, [
            CURLOPT_TIMEOUT => 5,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //设置curl_exec获取的信息的返回方式

        $resp = curl_exec($ch);
        $info = curl_getinfo($ch);
        if ($info['http_code'] != 200) {
            throw new Exception("配置加载失败");
        }
        curl_close($ch);

        return json_decode($resp, true);
    }

    private function cacheKey()
    {
        return "kconf_" . $this->app . $this->env . $this->password;
    }
}