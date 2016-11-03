<?php
namespace App\Controller\Platform;

use App\Model\TeaUser;
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
        $this->teaUser=(new TeaUser())->find($this->user_id);
        if(!$this->teaUser->is_exist){
            echo '<h2>无效用户！！</h2>';
            exit;
        }
    }

    public function error()
    {
        echo 'not find page';
    }
}