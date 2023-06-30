## use in laravel

``` php
        //store config
        $r = (new Kconf(Cache::repository(Cache::getStore()), config("app.name"), $this->app->environment(), 123456))->setNeedCache(false)->try();

        if ($c = Arr::get($r, 'data.config')) {
            config(["kconf" => $c]);
        }
```
