
## use in laravel

``` php
        //store config
        $r = (new Kconf(\Illuminate\Support\Facades\Cache::repository(\Illuminate\Support\Facades\Cache::getStore()), config("app.name"), $this->app->environment(), 1234))->setNeedCache($this->app->environment("production"))->try();
        if ($c = Arr::get($r, 'data.config')) {
            config([
                "kconf"=>$c
            ]);
        }
```
