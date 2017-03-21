<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/22
 * Time: 16:19
 */

namespace App\Controller\Member;

use System\Lib\Request;

class PlanController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $this->view('plan',array());
    }
}