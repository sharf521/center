<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/17
 * Time: 10:36
 */

namespace App\Model;


use System\Lib\DB;

class Region extends Model
{
    protected $table='region';
    public function __construct()
    {
        parent::__construct();
    }

    public function getList($pid=0)
    {
        return DB::table('region')->where('pid=?')->bindValues($pid)->all();
    }

    public function getName($id)
    {
        return DB::table('region')->where('id=?')->bindValues($id)->value('name');
    }
}