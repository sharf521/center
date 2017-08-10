<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/22
 * Time: 16:50
 */

namespace App\Model;


class Partner extends Model
{
    protected $table='partner';
    protected $dates=array('verify_at','created_at');
    public function __construct()
    {
        parent::__construct();
    }

    public function User()
    {
        return $this->hasOne('\App\Model\User','id','user_id');
    }
}