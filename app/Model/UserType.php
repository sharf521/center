<?php
namespace App\Model;

use System\Lib\DB;

class UserType extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table='usertype';
    }

    function getList()
    {
        return DB::table('usertype')->orderBy('id')->all();
    }
    function add($data = array())
    {
        $arr['name'] = $data['name'];
        $arr['desc'] = $data['desc'];
        $arr['is_admin'] = (int)$data['is_admin'];
        $arr['addtime'] = date('Y-m-d H:i:s');
        $permission['menu'] = $data["menu"];
        $permission['submenu'] = $data["submenu"];
        $permission['func'] = $data["func"];
        $arr['permission_id'] = serialize($permission);
        return DB::table('usertype')->insert($arr);
    }

    function edit($data = array())
    {
        $arr['name'] = $data['name'];
        $arr['desc'] = $data['desc'];
        $arr['is_admin'] = (int)$data['is_admin'];
        $permission['menu'] = $data["menu"];
        $permission['submenu'] = $data["submenu"];
        $permission['func'] = $data["func"];
        $arr['permission_id'] = serialize($permission);
        return DB::table('usertype')->where('id=?')->bindValues($data['id'])->limit(1)->update($arr);
    }

    function delete($data = array())
    {
        return $this->destroy($data);
    }
}