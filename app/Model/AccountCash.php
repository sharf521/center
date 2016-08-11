<?php
namespace App\Model;

class AccountCash extends Model
{
    protected $table='account_cash';
    public function __construct()
    {
        parent::__construct();
    }

    public function User()
    {
        return $this->hasOne('App\Model\User','id','user_id');
    }
}