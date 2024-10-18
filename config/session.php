<?php

use Illuminate\Support\Str;

return [

  /*
  |--------------------------------------------------------------------------
  | Default Session Driver
  |--------------------------------------------------------------------------
  | This option determines the default session driver that is utilized for
  | incoming requests. Laravel supports various storage options to persist
  | session data, with database storage being a common default choice.
  |
  | Supported: "file", "cookie", "database", "apc", "memcached", "redis", 
  | "dynamodb", "array"
  |
  */

'driver' => env('SESSION_DRIVER', 'database'),
  /*
  |--------------------------------------------------------------------------
  | Session Lifetime
  |--------------------------------------------------------------------------
  | Specify the number of minutes that the session should remain idle before
  | it expires. Set 'expire_on_close' to true if you want sessions to expire
  | immediately when the browser is closed.
  |
  */

  'lifetime' => env('SESSION_LIFETIME', 120),
  'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

  /*
  |--------------------------------------------------------------------------
  | Session Encryption
  |--------------------------------------------------------------------------
  | Enable encryption for all session data before storage. This is handled 
  | automatically by Laravel.
  |
  */

  'encrypt' => env('SESSION_ENCRYPT', false),

  /*
  |--------------------------------------------------------------------------
  | Session File Location
  |--------------------------------------------------------------------------
  | Define the storage location for session files when using the "file" 
  | session driver.
  |
  */

  'files' => storage_path('framework/sessions'),

  /*
  |--------------------------------------------------------------------------
  | Session Database Connection
  |--------------------------------------------------------------------------
  | Specify the database connection to manage sessions when using "database"
  | or "redis" drivers.
  |
  */

  'connection' => env('SESSION_CONNECTION'),

  /*
  |--------------------------------------------------------------------------
  | Session Database Table
  |--------------------------------------------------------------------------
  | Specify the table used to store sessions when using the "database" 
  | session driver.
  |
  */

  'table' => env('SESSION_TABLE', 'sessions'),

  /*
  |--------------------------------------------------------------------------
  | Session Cache Store
  |--------------------------------------------------------------------------
  | Define the cache store used to store session data for cache-driven 
  | session backends.
  |
  | Affects: "apc", "dynamodb", "memcached", "redis"
  |
  */

  'store' => env('SESSION_STORE'),

  /*
  |--------------------------------------------------------------------------
  | Session Sweeping Lottery
  |--------------------------------------------------------------------------
  | Define the odds of sweeping old sessions from storage on a given request.
  | Default is 2 out of 100.
  |
  */

  'lottery' => [2, 100],

  /*
  |--------------------------------------------------------------------------
  | Session Cookie Name
  |--------------------------------------------------------------------------
  | Change the name of the session cookie created by the framework. 
  | Typically, this should remain unchanged for security purposes.
  |
  */

  'cookie' => env(
    'SESSION_COOKIE',
    Str::slug(env('APP_NAME', 'laravel'), '_') . '_session'
  ),

  /*
  |--------------------------------------------------------------------------
  | Session Cookie Path
  |--------------------------------------------------------------------------
  | The path for which the cookie is available. Typically, this is the root
  | path of your application.
  |
  */

  'path' => env('SESSION_PATH', '/'),

  /*
  |--------------------------------------------------------------------------
  | Session Cookie Domain
  |--------------------------------------------------------------------------
  | Determines the domain and subdomains available to the session cookie.
  | By default, this is set to the root domain and its subdomains.
  |
  */

  'domain' => env('SESSION_DOMAIN'),

  /*
  |--------------------------------------------------------------------------
  | HTTPS Only Cookies
  |--------------------------------------------------------------------------
  | If true, session cookies will only be sent back to the server over HTTPS.
  | This helps to enhance security.
  |
  */

  'secure' => env('SESSION_SECURE_COOKIE'),

  /*
  |--------------------------------------------------------------------------
  | HTTP Access Only
  |--------------------------------------------------------------------------
  | When true, prevents JavaScript from accessing the cookie value, making it
  | accessible only via HTTP.
  |
  */

  'http_only' => env('SESSION_HTTP_ONLY', true),

  /*
  |--------------------------------------------------------------------------
  | Same-Site Cookies
  |--------------------------------------------------------------------------
  | Controls cookie behavior on cross-site requests, providing protection 
  | against CSRF attacks. Default is "lax".
  |
  | Supported: "lax", "strict", "none", null
  | See: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie#samesitesamesite-value
  |
  */

  'same_site' => env('SESSION_SAME_SITE', 'lax'),

  /*
  |--------------------------------------------------------------------------
  | Partitioned Cookies
  |--------------------------------------------------------------------------
  | If true, ties the cookie to the top-level site for cross-site contexts.
  | Must be flagged as "secure" and have Same-Site set to "none".
  |
  */

  'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];