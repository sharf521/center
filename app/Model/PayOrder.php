<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/20
 * Time: 11:15
 */

namespace App\Model;


class PayOrder extends  Model
{
    protected $table='pay_order';
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \App\Model\App
     */
    public function App()
    {
        return $this->hasOne('\App\Model\App','id','app_id');
    }
}