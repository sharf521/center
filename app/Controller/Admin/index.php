<?php
use \App\Controller\Admin\IndexController;

//if (in_array($_G['class'], array('login', 'logout', 'index', 'changepwd'))) {
//    $class = new IndexController();
//    call_user_func(array($class, $_G['class']), array());
//} elseif (file_exists(__DIR__ . '/' . ucfirst($_G['class']) . 'Controller.php')) {
//    $_classpath = "\\App\\Controller\\Admin\\" . ucfirst($_G['class']) . "Controller";
//    $class = new $_classpath();
//    if (method_exists($class, $_G['func'])) {
//        return call_user_func(array($class, $_G['func']), array());
//    } else {
//        return call_user_func(array($class, 'error'), array());
//    }
//} else {
//    echo 'page error';
//}


if (file_exists(__DIR__ . '/' . ucfirst($_G['class']) . 'Controller.php')) {
    $_classpath = "\\App\\Controller\\Admin\\" . ucfirst($_G['class']) . "Controller";
    $class = new $_classpath();
    if (method_exists($class, $_G['func'])) {
        return call_user_func(array($class, $_G['func']), array());
    } else {
        return call_user_func(array($class, 'error'), array());
    }
} else {
    $class = new IndexController();
    if (method_exists($class, $_G['class'])) {
        return call_user_func(array($class, $_G['class']), array());
    } else {
        return call_user_func(array($class, 'error'), array());
    }
}