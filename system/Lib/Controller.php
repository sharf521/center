<?php
namespace System\Lib;
////import("ORG/User/Info.class.php");
////ORG/User/Info.class.php
//function import($file)
//{
//    static $_file = array();
//    if (file_exists(ROOT . 'model/' . $file . '.class.php')) {
//        $file = ROOT . 'model/' . $file . '.class.php';
//    } else {
//        $file = $file;
//    }
//    if (isset($_file[$file]))
//        return true;
//    else
//        $_file[$file] = true;
//    require($file);
//}
//
//function new_model($name)
//{
//    static $_model = array();
//    if (isset($_model[$name])) {
//        return $_model[$name];
//    }
//    import($name);
//    $classname = $name . "Class";
//    if (class_exists($classname)) {
//        $model = new $classname();
//        $_model[$name] = $model;
//        return $model;
//    } else {
//        //return false;
//        die("error new_model {$name}");
//    }
//}
//
////模块
//function m($url, $vars = array())
//{
//    $_url = explode('/', $url);
//
//    $class = new_model($_url[0]);
//    $func = $_url[1];
//    if ($func == '') $func = 'index';
//    if ($class && method_exists($class, $func)) {
//        return call_user_func(array($class, $func), $vars);
//    } else {
//        //return false;
//        die("error class or method {$url}");
//    }
//}


class Controller
{
    public $base_url;
    public $template;
    public $user_id;
    public $username;

    public function __construct()
    {
        global $inputClass;
        $this->input = $inputClass;
        $this->base_url = '/index.php/';
        $this->control = ($this->input->get(0) != '') ? $this->input->get(0) : 'index';
        $this->func = ($this->input->get(1) != '') ? $this->input->get(1) : 'index';
        $this->user_id = getSession('user_id');
        $this->username = getSession('username');
        $this->user_typeid = getSession('usertype');
        $this->dbfix=\App\Config::$db1['dbfix'];
    }

    //显示模板
    public function view($tpl, $data = array())
    {
        global $_G;
        if (!empty($data)) {
            extract($data);
        }
        $file = __DIR__ . '/../../app/themes/' . $this->template . '/' . $tpl . '.tpl.php';
        if (file_exists($file)) {
            require $file;
        } else {
            echo 'Error:no file ' . $this->template . '/' . $tpl . '.tpl.php';
        }
    }

    public function base_url($control = '')
    {
        return $this->base_url . $control;
    }

    public function anchor($control, $title = '', $attributes = '')
    {
        $url = $this->base_url($control);
        if ($attributes != '') {
            if (is_array($attributes)) {
                $str = '';
                foreach ($attributes as $k => $v) {
                    $str .= " {$k}=\"{$v}\"";
                }
            } else {
                $str = $attributes;
            }
        }

        return '<a href="' . $url . '" ' . $str . '>' . $title . '</a>';
    }

    public function redirect($control)
    {
        $url = $this->base_url($control);
        header("location:$url");
        exit;
    }

    public function error()
    {
        echo '找不到当前网页';
    }
}