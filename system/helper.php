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
     * @param null $method
     * @return mixed
     */
    function app($className,$method=null)
    {
        //echo $className;
        //echo '<hr>';
        $class=\System\Lib\App::getInstance($className);
        echo 1;
        if($method!==null){
            echo 2;
            if (!method_exists($class, $method)) {
                $method = 'error';
            }
            echo 3;
            $rMethod = new \ReflectionMethod($className, $method);
            echo 4;
            $params = $rMethod->getParameters();
            echo 5;
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
            echo 6;
            return call_user_func_array(array($class, $method), $dependencies);
        }
        return $class;
    }
}

