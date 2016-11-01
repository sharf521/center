<?php
namespace App\Controller\Platform;

use System\Lib\Controller as BaseController;

class PlatformController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->template='platform';
        $this->control	=application('control');
        $this->user_typeid	=session('usertype');

        if($this->control !='login' && $this->control !='logout'){
            if(empty($this->user_id)){
                $url=urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
                $this->redirect("login?url={$url}");
                exit;
            }
        }
    }

    public function error()
    {
        echo 'not find page';
    }
}