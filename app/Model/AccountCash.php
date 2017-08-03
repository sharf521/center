<?php
namespace App\Model;

use App\Helper;

class AccountCash extends Model
{
    protected $table='account_cash';
    public function __construct()
    {
        parent::__construct();
    }

    //己提现天数
    public function getPastDays()
    {
        if($this->status==1 || $this->status==2){
            $date1=substr($this->created_at,0,10);
            $date2=substr(date('Y-m-d',time()),0,10);
            return Helper::betweenDays($date1,$date2);
        }
    }

    public function User()
    {
        return $this->hasOne('App\Model\User','id','user_id');
    }
}