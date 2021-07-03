### パッケージングの参考
https://qiita.com/os1ma/items/286eeec028e30e27587d

### PHPUnit

#### LaravelでのPHPUnit

```php
#一般的なPHPUnit
use PHPUnit\Framework\TestCase;
class HogeTest extends TestCase

#LaravelのPHPUnit
use Tests\TestCase;
class HogeTest extends TestCase
```
おそらくLaravelのフレームワークの機能を使ってテストをする場合は後者を使う必要がある感じがする


#### phpunit.xml

##### testsuite
ひとまとまりにしたテストみたいな感じ？
```xml
    <testsuites>
        <testsuite name="Domain">
            <directory suffix="Test.php">./tests/Domain/Model</directory>
        </testsuite>
        <testsuite name="Application">
            <directory suffix="Test.php">./tests/Application</directory>
        </testsuite>
        <testsuite name="infra_EloquentRepository">
            <directory suffix="Test.php">./tests/infra/EloquentRepository</directory>
        </testsuite>
        <testsuite name="infra_mysqlquery">
            <directory suffix="Test.php">./tests/infra/mysqlquery</directory>
        </testsuite>
    </testsuites>
```
上から順番にテストが実行されるっぽい
なので、順番に依存するテストがあるならそのように並べるのが吉かも


Laravel8 + Sanctum + ReactでSPA認証  
https://nochio12.hatenablog.com/entry/2020/10/03/215718

Laravel Breeze APIでサクッと認証機能を実装する  
https://zenn.dev/nrikiji/articles/dcde3df1ea8d85

テストDBへのマイグレーション   
tool/run.sh artisan "migrate --env=testing"



composer require laravel/breeze

composer require laravel/sanctum

php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

