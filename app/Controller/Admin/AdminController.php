<?php
namespace App\Controller\Admin;

use System\Lib\Controller as BaseController;

class AdminController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        global $_G;
        $this->base_url='/index.php/'.$_G['system']['houtai'].'/';
        $this->template='admin';
        $this->control	=($this->input->get(1)!='')?$this->input->get(1):'index';
        $this->func		=($this->input->get(2)!='')?$this->input->get(2):'index';
        $this->user_typeid	=getSession('usertype');
        $this->permission_id=getSession('permission_id');

        if($this->control !='login' && $this->control !='logout'){
            if(empty($this->user_id) || empty($this->permission_id)){
                $url=urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
                $this->redirect("login?url={$url}");
                exit;
            }
            // $this->user=m('user/one',array('user_id'=>$this->user_id));
        }
        ////主界面不验证权限
        if(!in_array($this->control,array('index','login','logout','changepwd'))){
            /*if(! check_cmvalue($class.'_'.$func)){
                echo 'no permission_id';
                exit;
            }*/
            $permission_id=$this->permission_id;
            if($permission_id!='ALL'){
                $permission_id=unserialize($permission_id);
                if(empty($permission_id['func'])){
                    $permission_id['func']=array();
                }
                if(!in_array($this->control.'_'.$this->func,$permission_id['func'])){
                    echo '无权限';
                    exit;
                }
            }
        }
    }

    public function error()
    {
        echo 'not find page';
    }
}