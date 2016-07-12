<?php
namespace System\Lib;

class Model
{
    //属性必须在这里声明
    protected $table;
    protected $fields=array();
    protected $attributes=array();
    protected $dbfix;
    protected $primaryKey='id';
    protected $is_exist=false;
    public function __construct()
    {
        $this->dbfix = DB::dbfix();
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
        return app($class)->where($foreign_key.'='.$this->$local_key)->first();
    }

    public function hasMany($class,$foreign_key,$local_key='id')
    {
        return app($class)->where($foreign_key.'='.$this->$local_key)->get();
    }

///////////////////////////////////////////////////////////



    public function find($id)
    {
        return $this->where($this->primaryKey."=?")->bindValues($id)->first();
    }

    private function arrToObj($row)
    {
        $obj = clone $this;
        $obj->is_exist=true;
        foreach ($row as $k=>$v){
            $obj->attributes[$k]=$v;
        }
        return $obj;
    }
    /**
     * 获取一个对象
     * @return $this
     */
    public function first()
    {
        $row=$this->row();
        return $this->arrToObj($row);
    }

    /**
     * 返回一个数组，每个元素是一个对象
     * @return array
     */
    public function get()
    {
        $arr=array();
        $result=$this->all();
        foreach ($result as $row){
            $obj = $this->arrToObj($row);
            array_push($arr,$obj);
        }
        return $arr;
    }
    public function pager($page = 1, $pageSize = 10)
    {
        $arr=array();
        $result=$this->page($page,$pageSize);
        foreach ($result['list'] as $row){
            $obj = $this->arrToObj($row);
            array_push($arr,$obj);
        }
        return array(
            'list' => $arr,
            'total' => $result['total'],
            'page' =>$result['page']
        );
    }
    public function save()
    {
        $primaryKey=$this->primaryKey;
        if($this->is_exist){
            $id=$this->$primaryKey;
            unset($this->$primaryKey);
            return DB::table($this->table)->where("{$primaryKey}=?")->bindValues($id)->update($this->attributes);
        }else{
            return DB::table($this->table)->insertGetId($this->attributes);
        }
    }
///////以下重写DB类方法/////////////////////////////////////////////////////////////////////////////////
    /**
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function page($page = 1, $pageSize = 10)
    {
        return DB::table($this->table)->page($page,$pageSize);
    }

    /**
     * @return array
     */
    public function row()
    {
        return DB::table($this->table)->row();
    }

    /**
     * @return array
     */
    public function all()
    {
        return DB::table($this->table)->all();
    }

    public function select($str)
    {
        DB::where($str);
        return $this;
    }
    public function distinct()
    {
        DB::distinct();
        return $this;
    }
    /**
     * @param array|string $str
     * @return $this
     */
    public function where($str)
    {
        DB::where($str);
        return $this;
    }

    public function orderBy($str)
    {
        DB::orderBy($str);
        return $this;
    }

    public function groupBy($str)
    {
        DB::groupBy($str);
        return $this;
    }

    public function having($str)
    {
        DB::having($str);
        return $this;
    }

    public function limit($str)
    {
        DB::limit($str);
        return $this;
    }

    public function bindValues($values = array())
    {
        DB::bindValues($values);
        return $this;
    }
}