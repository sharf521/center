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

    function getShippingRegion()
    {
        $area=array();
        $area['华东']=$this->where('pid=0 and id in(40,924,1057,1170,1414)')->get(true);
        $area['华北']=$this->where('pid=0 and id in(1,21,102,297,439,1536)')->get(true);
        $area['华中']=$this->where('pid=0 and id in(1711,1905,2034)')->get(true);
        $area['华南']=$this->where('pid=0 and id in(1310,2184,2541,2403)')->get(true);
        $area['东北']=$this->where('pid=0 and id in(561,690,768)')->get(true);
        $area['西北']=$this->where('pid=0 and id in(3128,3256,3369,3422,3454)')->get(true);
        $area['西南']=$this->where('pid=0 and id in(61,2570,2791,2892,3046)')->get(true);
        $area['港澳台']=$this->where('pid=0 and id in(3573,3575,3571)')->get(true);
        return $area;
    }
}