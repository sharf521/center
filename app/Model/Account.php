<?php

namespace App\Model;


class Account extends Model
{
    protected  $table='account';
    protected  $primaryKey='user_id';
    public function __construct()
    {
        parent::__construct();
    }
}