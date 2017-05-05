<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/20
 * Time: 11:15
 */

namespace App\Model;


class Order extends  Model
{
    protected $table='order';
    protected $dates=array('created_at','payed_at');
    public function __construct()
    {
        parent::__construct();
    }
}