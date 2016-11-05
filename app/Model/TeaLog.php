<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/30
 * Time: 15:09
 */

namespace App\Model;


class TeaLog extends Model
{
    protected $table='tea_log';
    public function __construct()
    {
        parent::__construct();
    }

    public function User()
    {
        return $this->hasOne('\App\Model\User','id','user_id');
    }
}