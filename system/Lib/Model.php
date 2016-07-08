<?php
namespace System\Lib;

class Model
{
    protected $table;
    protected $fields=array();
    protected $mysql;
    protected $attributes=array();
    protected $dbfix;
    public function __construct()
    {
        $this->dbfix = \App\Config::$db1['dbfix'];
        $this->mysql=DB::instance('db1');
    }

    public function __get($key)
    {
        return $this->attributes[$key];
    }

    public function __set($key, $value)
    {
        $this->attributes[$key]=$value;
    }
    public function __isset($key){
        return isset($this->attributes[$key]);
    }
    public function __unset($key)
    {
        unset($this->attributes[$key]);
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

    /**
     * @param array $data
     * @param string $table
     * @return array
     */
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




    public function hasOne($class,$foreign_key,$local_key='id')
    {
        $mod = new $class();
        return $mod->where($foreign_key.'='.$this->$local_key)->first();
    }

    public function hasMany($class,$foreign_key,$local_key='id')
    {
        $mod = new $class();
        return $mod->where($foreign_key.'='.$this->$local_key)->get();
    }

///////////////////////////////////////////////////////////



    public function find($id)
    {
        return $this->where('id=?')->bindValues($id)->first();
    }
    //取一行
    public function first()
    {
        $this->mysql->table($this->table);
        $row=$this->mysql->row();
        foreach ($row as $k=>$v){
            $this->$k=$v;
        }
        unset($this->dbfix);
        unset($this->mysql);
        return $this;
    }

    //取多行
    public function get()
    {
        $arr=array();
        $this->mysql->table($this->table);
        $result=$this->mysql->all();
        unset($this->dbfix);
        unset($this->mysql);
        foreach ($result as $row){
            $obj = clone $this;
            foreach ($row as $k=>$v){
                $obj->$k=$v;
            }
            array_push($arr,$obj);
        }
        return $arr;
    }

    public function save()
    {
        if(isset($this->id)){
            $id=$this->id;
            unset($this->id);
            return DB::table($this->table)->where('id=?')->bindValues($id)->update($this->attributes);
        }else{
            return DB::table($this->table)->insertGetId($this->attributes);
        }
    }

    public function page($page = 1, $pageSize = 10)
    {
        $arr=array();
        $this->mysql->table($this->table);
        $result=$this->mysql->page($page,$pageSize);
        unset($this->dbfix);
        unset($this->mysql);
        foreach ($result['list'] as $row){
            $obj = clone $this;
            foreach ($row as $k=>$v){
                $obj->$k=$v;
            }
            array_push($arr,$obj);
        }
        return array(
            'list' => $arr,
            'total' => $result['total'],
            'page' =>$result['page']
        );
    }

    public function select($str)
    {
        $this->mysql->where($str);
        return $this;
    }
    public function distinct()
    {
        $this->mysql->distinct();
        return $this;
    }
    /**
     * @param array|string $str
     * @return $this
     */
    public function where($str)
    {
        $this->mysql->where($str);
        return $this;
    }

    public function orderBy($str)
    {
        $this->mysql->orderBy($str);
        return $this;
    }

    public function groupBy($str)
    {
        $this->mysql->groupBy($str);
        return $this;
    }

    public function having($str)
    {
        $this->mysql->having($str);
        return $this;
    }

    public function limit($str)
    {
        $this->mysql->limit($str);
        return $this;
    }

    public function bindValues($values = array())
    {
        $this->mysql->bindValues($values);
        return $this;
    }

}