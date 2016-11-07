<?php
namespace App\Controller;

use App\Model\Taobao;
use App\Model\User;
use System\Lib\DB;

class IndexController extends Controller
{
    private $token = 'vcivc';

    public function __construct()
    {
        parent::__construct();
    }

    public function index(User $user)
    {
        //echo '首页';
        redirect('member/');
    }


}