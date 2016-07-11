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
        $session=app('\System\Lib\Session');
        if($name===null){
            return $session;
        }
        else{
            return $session->get($name);
        }
    }
}
if (!function_exists('app')) {
    /**
     * @param $className
     * @return mixed
     */
    function app($className)
    {
        return \System\Lib\App::getInstance($className);
    }
}
if (!function_exists('controller')) {
    function controller($control,$method='index')
    {
        global $_G;
        $class=\System\Lib\App::getInstance($control);
        $_G['Controller'] = $class;
        if (!method_exists($class, $method)) {
            $method = 'error';
        }
        $rMethod = new \ReflectionMethod($control, $method);
        $params = $rMethod->getParameters();
        $dependencies = array();
        foreach ($params as $param) {
            if ($param->getClass()) {
                $_name = $param->getClass()->name;
                array_push($dependencies, new $_name());
            } elseif ($param->isDefaultValueAvailable()) {
                array_push($dependencies, $param->getDefaultValue());
            } else {
                array_push($dependencies, null);
            }
        }
        return call_user_func_array(array($class, $method), $dependencies);
    }
}

