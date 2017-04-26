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

    public function pay()
    {
        $url='http://gateway.ulinkpay.com:8002/asaop/rest/api/';
        
    }


}