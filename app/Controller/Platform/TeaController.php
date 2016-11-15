<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/15
 * Time: 10:04
 */

namespace App\Controller\Platform;


use App\Model\Tea;
use App\Model\TeaGroup;
use App\Model\TeaUser;

class TeaController extends PlatformController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function myStatus(Tea $tea,TeaUser $teaUser,TeaGroup $teaGroup)
    {
        $teaUser=$teaUser->find($this->user_id);
        $tea=$teaUser->getMyNowTea();
        $teaGroup=$teaGroup->find($tea->group_id);
        $teas=$teaGroup->Teas();
        $data['teas']=$teas;
        $this->view('tea',$data);
    }
}