<?php
namespace System\Lib;

class Model
{
    protected $table;
    protected $fields=array();
    private $pdo;

    public function __construct()
    {
        $this->dbfix = \App\Config::$db1['dbfix'];
        $this->pdo=DB::instance();
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

    public function hasOne($class,$foreign_key,$local_key='id')
    {
        $mod = new $class();
        return $mod->where($foreign_key.'='.$this->$local_key)->first();
    }

    public function hasMany($class,$foreign_key,$local_key='id')
    {
        $mod = new $class();
        return $mod->where($foreign_key.'='.$this->$local_key)->all();
    }

///////////////////////////////////////////////////////////



    public function find($id)
    {
        return $this->where('id=?')->bindValues($id)->first();
    }
    //取一行
    public function first()
    {
        $this->pdo->table($this->table);
        echo $this->pdo->getSql();
        $row=$this->pdo->row();
        foreach ($row as $k=>$v){
            $this->$k=$v;
        }
        unset($this->dbfix);
        unset($this->pdo);
        return $this;
    }

    //取多行
    public function get()
    {
        $arr=array();
        $this->pdo->table($this->table);
        $result=$this->pdo->all();
        unset($this->dbfix);
        unset($this->pdo);
        foreach ($result as $row){
            $obj = clone $this;
            foreach ($row as $k=>$v){
                $obj->$k=$v;
            }
            array_push($arr,$obj);
        }
        return $arr;
    }

    public function page($page = 1, $pageSize = 10)
    {
        $arr=array();
        $this->pdo->table($this->table);
        $result=$this->pdo->page($page,$pageSize);
        unset($this->dbfix);
        unset($this->pdo);
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
        $this->pdo->where($str);
        return $this;
    }
    public function distinct()
    {
        $this->pdo->distinct();
        return $this;
    }
    /**
     * @param array|string $str
     * @return $this
     */
    public function where($str)
    {
        $this->pdo->where($str);
        return $this;
    }

    public function orderBy($str)
    {
        $this->pdo->orderBy($str);
        return $this;
    }

    public function groupBy($str)
    {
        $this->pdo->groupBy($str);
        return $this;
    }

    public function having($str)
    {
        $this->pdo->having($str);
        return $this;
    }

    public function limit($str)
    {
        $this->pdo->limit($str);
        return $this;
    }

    public function bindValues($values = array())
    {
        $this->pdo->bindValues($values);
        return $this;
    }

}