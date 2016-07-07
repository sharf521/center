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

    public function index($a=100,$b,$c=300)
    {
        print_r($a);
        echo '<br>';
        print_r($b);echo '<br>';
        print_r($c);echo '<br>';
        echo 1111;
    }
}