<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/25
 * Time: 18:08
 */

namespace App\Model;


class AccountBank extends Model
{
    protected $table='account_bank';
    protected $primaryKey='user_id';
    public function __construct()
    {
        parent::__construct();
    }

    public function User()
    {
        return $this->hasOne('\App\Model\User','id','user_id');
    }

    public function UserInfo()
    {
        return $this->hasOne('\App\Model\UserInfo','user_id','user_id');
    }
}