<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/17
 * Time: 16:33
 */

namespace App\Controller\Admin;


use App\Model\UserInfo;
use System\Lib\Request;

class RealNameController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(UserInfo $userInfo,Request $request)
    {
        $page=$request->get('page');
        $data['list']=$userInfo->pager($page,10);
        $this->view('realname',$data);
    }
}