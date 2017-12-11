<?php

namespace App\Utils;

class XSS
{
    /**
     * Allowable tags
     *
     * @var string
     */
    public static $whitelist = '<h1><h2><h3><h4><h5><h6><p><img><strong><b><i><u><br>';

    /**
     * Deniable tags
     *
     * @var string
     */
    public static $blacklist = '';

    /**
     * Strip the unwanted tags
     *
     * @param string $str
     *
     * @return string $str
     */
    public static function strip($str)
    {
        return strip_tags($str, self::$whitelist);
    }
}
