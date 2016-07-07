<?php
if (!function_exists('url')) {
    function url($path)
    {
        global $_G;
        if (substr($path, 0, 1) != '/') {
            $path = $_G['Controller']->base_url . $path;
        }
        return $path;
    }
}

if (!function_exists('redirect')) {
    /**
     * Get an instance of the redirector.
     *
     * @param  string|null $to
     * @return  \System\Lib\Redirect;
     */
    function redirect($to = null)
    {
        return new \System\Lib\Redirect($to);
    }
}

if (!function_exists('session')) {
    /**
     * @param string $name
     * @return \System\Lib\Session
     */
    function session($name=null)
    {

        static $session = null;

        if ($session===null) {
            $session=new \System\Lib\Session();
        }
        echo 4444;
        exit;
        if($name===null){
            return $session;
        }
        else{
            return $session->get($name);
        }
    }
}