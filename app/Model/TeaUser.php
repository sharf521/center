<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/31
 * Time: 18:33
 */

namespace App\Model;


class TeaUser extends Model
{
    protected $table='tea_user';
    public function __construct()
    {
        parent::__construct();
    }

    public function account()
    {
        return $this->hasOne('\App\Model\TeaMoney','user_id','id');
    }

    public function User()
    {
        return $this->hasOne('\App\Model\User','id','id');
    }

    public function inviteUsers()
    {
        return $this->hasMany('\App\Model\TeaUser','invite_id','id');
    }

    public function getMyNowTea($id=null)
    {
        if($id!=null){
            $this->id=$id;
        }
        return (new Tea())->where("status=1 and user_id={$this->id}")->first();
    }
}