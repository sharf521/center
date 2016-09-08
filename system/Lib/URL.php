<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/8
 * Time: 10:36
 */

namespace System\Lib;

class URL
{
    public static function domain()
    {
        $protocol = (!empty($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== 'off'
            || (int) $_SERVER['SERVER_PORT'] === 443) ? 'https://' : 'http://';

        return $protocol.$_SERVER['HTTP_HOST'];
    }

    /**
     * Get current url.
     *
     * @return string
     */
    public static function current()
    {
        return self::host().$_SERVER['REQUEST_URI'];
    }
}