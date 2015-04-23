<?php

namespace Joelvardy\Memcached\Console;

class Connection {


    protected static $memcached = false;


    protected static function check($memcached) {

        if (array_values($memcached->getVersion())[0] === '255.255.255') return false;

        return $memcached;

    }


    public static function get($host = '127.0.0.1', $port = 11211) {

        if (static::$memcached) return static::check(static::$memcached);

        static::$memcached = new \Memcached();
        static::$memcached->addServer($host, $port);

        return static::check(static::$memcached);

    }


}
