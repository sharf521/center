<?php
namespace App\Controller;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        //echo '首页';
        redirect('member/');
    }
}