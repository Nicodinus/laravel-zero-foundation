<?php

use Illuminate\Container\Container;
use Illuminate\Contracts\Broadcasting\Factory as BroadcastFactory;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\Date;

if (! function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param  string|null  $abstract
     * @param  array  $parameters
     * @return mixed|\Illuminate\Contracts\Foundation\Application
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }
}

if (! function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string  $path
     * @return string
     */
    function app_path($path = '')
    {
        return app()->path($path);
    }
}

if (! function_exists('base_path')) {
    /**
     * Get the path to the base of the install.
     *
     * @param  string  $path
     * @return string
     */
    function base_path($path = '')
    {
        return app()->basePath($path);
    }
}

if (! function_exists('bcrypt')) {
    /**
     * Hash the given value against the bcrypt algorithm.
     *
     * @param  string  $value
     * @param  array  $options
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return app('hash')->driver('bcrypt')->make($value, $options);
    }
}

if (! function_exists('broadcast')) {
    /**
     * Begin broadcasting an event.
     *
     * @param  mixed|null  $event
     * @return \Illuminate\Broadcasting\PendingBroadcast
     */
    function broadcast($event = null)
    {
        return app(BroadcastFactory::class)->event($event);
    }
}

if (! function_exists('cache')) {
    /**
     * Get / set the specified cache value.
     *
     * If an array is passed, we'll assume you want to put to the cache.
     *
     * @param  dynamic  key|key,default|data,expiration|null
     * @return mixed|\Illuminate\Cache\CacheManager
     *
     * @throws \Exception
     */
    function cache()
    {
        $arguments = func_get_args();

        if (empty($arguments)) {
            return app('cache');
        }

        if (is_string($arguments[0])) {
            return app('cache')->get(...$arguments);
        }

        if (! is_array($arguments[0])) {
            throw new Exception(
                'When setting a value in the cache, you must pass an array of key / value pairs.'
            );
        }

        return app('cache')->put(key($arguments[0]), reset($arguments[0]), $arguments[1] ?? null);
    }
}

if (! function_exists('config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\Illuminate\Config\Repository
     */
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('config');
        }

        if (is_array($key)) {
            return app('config')->set($key);
        }

        return app('config')->get($key, $default);
    }
}

if (! function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string  $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->configPath($path);
    }
}

if (! function_exists('database_path')) {
    /**
     * Get the database path.
     *
     * @param  string  $path
     * @return string
     */
    function database_path($path = '')
    {
        return app()->databasePath($path);
    }
}

if (! function_exists('decrypt')) {
    /**
     * Decrypt the given value.
     *
     * @param  string  $value
     * @param  bool  $unserialize
     * @return mixed
     */
    function decrypt($value, $unserialize = true)
    {
        return app('encrypter')->decrypt($value, $unserialize);
    }
}

if (! function_exists('dispatch')) {
    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param  mixed  $job
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    function dispatch($job)
    {
        if ($job instanceof Closure) {
            $job = CallQueuedClosure::create($job);
        }

        return new PendingDispatch($job);
    }
}

if (! function_exists('dispatch_now')) {
    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @param  mixed  $job
     * @param  mixed  $handler
     * @return mixed
     */
    function dispatch_now($job, $handler = null)
    {
        return app(Dispatcher::class)->dispatchNow($job, $handler);
    }
}

if (! function_exists('encrypt')) {
    /**
     * Encrypt the given value.
     *
     * @param  mixed  $value
     * @param  bool  $serialize
     * @return string
     */
    function encrypt($value, $serialize = true)
    {
        return app('encrypter')->encrypt($value, $serialize);
    }
}

if (! function_exists('event')) {
    /**
     * Dispatch an event and call the listeners.
     *
     * @param  string|object  $event
     * @param  mixed  $payload
     * @param  bool  $halt
     * @return array|null
     */
    function event(...$args)
    {
        return app('events')->dispatch(...$args);
    }
}

if (! function_exists('factory')) {
    /**
     * Create a model factory builder for a given class and amount.
     *
     * @param  string  $class
     * @param  int  $amount
     * @return \Illuminate\Database\Eloquent\FactoryBuilder
     */
    function factory($class, $amount = null)
    {
        $factory = app(EloquentFactory::class);

        if (isset($amount) && is_int($amount)) {
            return $factory->of($class)->times($amount);
        }

        return $factory->of($class);
    }
}

if (! function_exists('info')) {
    /**
     * Write some information to the log.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    function info($message, $context = [])
    {
        app('log')->info($message, $context);
    }
}

if (! function_exists('logger')) {
    /**
     * Log a debug message to the logs.
     *
     * @param  string|null  $message
     * @param  array  $context
     * @return \Illuminate\Log\LogManager|null
     */
    function logger($message = null, array $context = [])
    {
        if (is_null($message)) {
            return app('log');
        }

        return app('log')->debug($message, $context);
    }
}

if (! function_exists('logs')) {
    /**
     * Get a log driver instance.
     *
     * @param  string|null  $driver
     * @return \Illuminate\Log\LogManager|\Psr\Log\LoggerInterface
     */
    function logs($driver = null)
    {
        return $driver ? app('log')->driver($driver) : app('log');
    }
}

if (! function_exists('now')) {
    /**
     * Create a new Carbon instance for the current time.
     *
     * @param  \DateTimeZone|string|null  $tz
     * @return \Illuminate\Support\Carbon
     */
    function now($tz = null)
    {
        return Date::now($tz);
    }
}

if (! function_exists('report')) {
    /**
     * Report an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    function report(Throwable $exception)
    {
        app(ExceptionHandler::class)->report($exception);
    }
}

if (! function_exists('rescue')) {
    /**
     * Catch a potential exception and return a default value.
     *
     * @param  callable  $callback
     * @param  mixed  $rescue
     * @param  bool  $report
     * @return mixed
     */
    function rescue(callable $callback, $rescue = null, $report = true)
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            if ($report) {
                report($e);
            }

            return $rescue instanceof Closure ? $rescue($e) : $rescue;
        }
    }
}

if (! function_exists('resolve')) {
    /**
     * Resolve a service from the container.
     *
     * @param  string  $name
     * @param  array  $parameters
     * @return mixed
     */
    function resolve($name, array $parameters = [])
    {
        return app($name, $parameters);
    }
}

if (! function_exists('resource_path')) {
    /**
     * Get the path to the resources folder.
     *
     * @param  string  $path
     * @return string
     */
    function resource_path($path = '')
    {
        return app()->resourcePath($path);
    }
}

if (! function_exists('storage_path')) {
    /**
     * Get the path to the storage folder.
     *
     * @param  string  $path
     * @return string
     */
    function storage_path($path = '')
    {
        return app('path.storage').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (! function_exists('today')) {
    /**
     * Create a new Carbon instance for the current date.
     *
     * @param  \DateTimeZone|string|null  $tz
     * @return \Illuminate\Support\Carbon
     */
    function today($tz = null)
    {
        return Date::today($tz);
    }
}

if (! function_exists('trans')) {
    /**
     * Translate the given message.
     *
     * @param  string|null  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    function trans($key = null, $replace = [], $locale = null)
    {
        if (is_null($key)) {
            return app('translator');
        }

        return app('translator')->get($key, $replace, $locale);
    }
}

if (! function_exists('trans_choice')) {
    /**
     * Translates the given message based on a count.
     *
     * @param  string  $key
     * @param  \Countable|int|array  $number
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string
     */
    function trans_choice($key, $number, array $replace = [], $locale = null)
    {
        return app('translator')->choice($key, $number, $replace, $locale);
    }
}

if (! function_exists('__')) {
    /**
     * Translate the given message.
     *
     * @param  string|null  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string|array|null
     */
    function __($key = null, $replace = [], $locale = null)
    {
        if (is_null($key)) {
            return $key;
        }

        return trans($key, $replace, $locale);
    }
}
