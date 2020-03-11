<h1 align="left"><a href="http://im.zhaodaka.cn/">laravelcors</a></h1>

## Requirement

1. PHP >= 7.1
2. **[Composer](https://getcomposer.org/)**

## Installation

```shell
$ composer require zdk/laravel-cors
```

生成配置文件
```config
php artisan vendor:publish --provider="Zdk\Cors\ServiceProvider"
```

###Usage
添加到全局路由中
```php
  protected $middleware = [
          \App\Http\Middleware\TrustProxies::class,
          ........,
          HandleCors::class
  ];
```

###配置项参数
<table>
  <tr>
     <td>配置项</td>
     <td>示例</td>
  </tr>
  <tr>
     <td>allow_methods</td>
     <td>["GET", "POST", "PUT", "DELETE", "HEAD", "OPTIONS"]/["*"]</td>
  </tr>
   <tr>
       <td>allow_headers</td>
       <td>["*"]或者其他具体项数组</td>
    </tr>
    <tr>
       <td>allow_origin</td>
       <td>["*"]或者其他具体项数组</td>
    </tr>
    <tr>
        <td>allow_path</td>
        <td>未完善</td>
    </tr>
</table> 
