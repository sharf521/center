<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/21
 * Time: 11:54
 */

namespace App\Model;


class RebateList extends Model
{
    protected $table='rebate_list';
    public function __construct()
    {
        parent::__construct();
    }

    public function Rebate()
    {
        return $this->hasOne('\App\Model\Rebate','id','rebate_id');
    }
}