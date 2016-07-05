<?php
namespace System\Lib;

class Model
{
    protected $table;
    protected $fields;

    public function __construct()
    {
        $this->fields = array();
        $this->dbfix = \App\Config::$db1['dbfix'];
    }

    public function filterFields($post, $fields = array())//过滤字段
    {
        if (empty($fields)) {
            $fields = $this->fields;
        }
        if (!is_array($post)) {
            return array();
        }
        foreach ($post as $i => $v) {
            if (!in_array($i, $fields)) {
                unset($post[$i]);
            }
        }
        return $post;
    }

    public function getOne($data = array(),$table='')
    {
        if($table==''){
            $table=$this->table;
        }
        $where = " 1=1";
        $params = array();
        foreach ($data as $field => $v) {
            $where .= " and {$field}=:{$field}";
            $params["{$field}"] = $v;
        }
        return DB::table($table)->where($where)->bindValues($params)->row();
    }

    //删除
    public function destroy($data=array())
    {
        return DB::table($this->table)->where($data)->delete();
    }

    public function dirty($data=array(),$table='')
    {
        if($table==''){
            $table=$this->table;
        }
        return DB::table($table)->where($data)->update(array('status'=>-1));
    }

    public function __get($key)
    {
        if (isset($this->$key)) {
            return ($this->$key);
        }
    }

    public function __set($key, $value)
    {
        $this->$key = $value;
    }

}