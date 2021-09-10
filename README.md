## About Task Management System

This basic task management system build as [RESTful API](https://restfulapi.net/) using [Laravel](https://laravel.com) a ([PHP](https://www.php.net/) framework), and uses official and third-party libraries:

- [Laravel Sanctum](https://laravel.com/docs/8.x/sanctum) for SPA authentication.
- [Laravel IDE Helper Generator](https://github.com/barryvdh/laravel-ide-helper) to generate accurate autocompletion.
- [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) to enforces everybody is using the same coding standard
- [Laravel Telescope](https://laravel.com/docs/8.x/telescope) provides insight into the requests coming into your application, exceptions, log entries, database queries, queued jobs, mail, notifications, cache operations, scheduled tasks, variable dumps, and more.
- [Laravel Enum](https://github.com/BenSampo/laravel-enum) Simple, extensible and powerful enumeration implementation for Laravel.
- [flysystem aws s3](https://github.com/thephpleague/flysystem-aws-s3-v3) Flysystem Adapter for AWS SDK V3
- [Predis](https://github.com/predis/predis) A flexible and feature-complete Redis client for PHP.


### PSR2 Coding Standard

You can now run the test simply typing
<pre><code>./vendor/bin/phpcs</code></pre>
Fixing PHP CodeSniffer has built-in tool that can fix a lot of the style errors, you can fix your code by simply typing
<pre><code>./vendor/bin/phpcbf</code></pre>

The test directory is ignore in the `phpcs.xml` since I choose `snake_case` over `CamelCase` in the class methods for readability purpose.

You can read [here](https://laravel.com/docs/master/contributions#coding-style)

### Postman

You can find `Task Management System.postman_collection.json` and `Task Management System.postman_environment.json` in the **docs** directory.

### Requirements
Laravel server requirements [here](https://laravel.com/docs/7.x#server-requirements)
- PHP: `^8.0`
- PHP extension `php_zip` enabled
- PHP extension `php_zip` enabled
- PHP extension `php_xml` enabled
- PHP extension `php_gd2` enabled
- PHP extension `php_iconv` enabled
- PHP extension `php_simplexml` enabled
- PHP extension `php_xmlreader` enabled
- PHP extension `php_zlib` enabled

### Getting Started
Clone the repository
```
$ git clone https://github.com/markmarilag27/laravel-task-management-api.git
```
Install dependencies
```
$ composer install
```
Run artisan commands
```
php artisan migrate:fresh && php artisan db:seed --class=StateSeeder && php artisan db:seed --class=TaskSeeder
```
Open your [localhost](http://localhost) environment
