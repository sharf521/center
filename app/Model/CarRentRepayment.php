<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/11
 * Time: 14:53
 */

namespace App\Model;


class CarRentRepayment extends Model
{
    protected $table='car_rent_repayment';
    protected $dates=array('created_at','repayment_time','repayment_yestime');
    public function __construct()
    {
        parent::__construct();
    }
}