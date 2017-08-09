<?php
namespace App\Model;

class FbbLog extends Model
{
    protected $table='fbb_log';
    public function __construct()
    {
        parent::__construct();
    }
    public function User()
    {
        return $this->hasOne('App\Model\User', 'id','user_id');
    }
}