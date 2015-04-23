<?php

namespace Joelvardy\Memcached\Console;

class Selector {


    public static function toRegex($string) {
        return '/^'.str_replace('*', '(.*)', $string).'$/';
    }


}
