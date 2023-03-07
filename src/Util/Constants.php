<?php

namespace Supabase\Util;

class Constants
{
    public static $VERSION = '0.0.0-automated';

    public static function getDefaultHeaders()
    {
        return [
            'X-Client-Info' => 'postgrest-php/'.self::$VERSION,
        ];
    }
}
