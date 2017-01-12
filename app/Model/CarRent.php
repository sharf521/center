<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/10
 * Time: 11:46
 */

namespace App\Model;


class CarRent extends Model
{
    protected $table='car_rent';
    public function __construct()
    {
        parent::__construct();
    }

    public function Repayments()
    {
        return $this->hasMany('\App\Model\CarRentRepayment','car_rent_id','id','','id');
    }
}