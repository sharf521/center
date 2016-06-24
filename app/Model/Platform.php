<?php
namespace App\Model;

use System\Lib\DB;

class Platform extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getlinkpage()
    {
        $arr = array();
        $_result = DB::table('linkpage_type a')->select('b.*,a.code,a.name as tname')->join('linkpage b', 'a.id=b.typeid')->orderBy('b.showorder asc');
        foreach ($_result as $_row) {
            $arr[$_row['id']] = $_row['name'];
            $arr[$_row['code']][$_row['value']] = $_row['name'];
        }
        $_result = null;
        return $arr;
    }

    function linklist($data = array())
    {
        global $pager;
        $_select = " * ";
        $where = "where 1=1";
        if (!empty($data['typeid'])) {
            $where .= " and typeid={$data['typeid']}";
        }
        $sql = "select SELECT from {$this->dbfix}linkpage {$where} ORDER LIMIT";
        $_order = isset($data['order']) ? ' order by ' . $data['order'] : 'order by showorder,id';

        //总条数
        $row = DB::get_one(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
        $total = $row['num'];

        $epage = empty($data['epage']) ? 10 : $data['epage'];
        $page = $data['page'];
        if (!empty($page)) {
            $index = $epage * ($page - 1);
        } else {
            $index = 0;
            $page = 1;
        }
        if ($index > $total) {
            $index = 0;
            $page = 1;
        }
        $limit = " limit {$index}, {$epage}";
        $list = DB::get_all(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $limit), $sql));
        global $pager;
        $pager->page = $page;
        $pager->epage = $epage;
        $pager->total = $total;
        return array(
            'list' => $list,
            'total' => $total,
            'page' => $pager->show()
        );
    }

    private function set($post)
    {
        $post['name'] = (strip_tags($post['name']));
        $post['code'] = (strip_tags($post['code']));
        $post['showorder'] = (strip_tags($post['showorder']));
        return $post;
    }

    public function edit($post)
    {
        $id = $post['id'];
        $post = $this->set($post);
        DB::table('linkpage_type')->where('id=?')->bindValues($id)->limit(1)->update($post);
    }

    public function add($post)
    {
        $post = $this->set($post);
        $post['showorder'] = ($post['showorder']) ? $post['showorder'] : 10;
        $post['addtime'] = date('Y-m-d H:i:s');
        return DB::table('linkpage_type')->insert($post);
    }

    public function link_add($data)
    {
        $data['createdate'] = date('Y-m-d H:i:s');
        $data['showorder'] = ($data['showorder']) ? $data['showorder'] : 10;
        return DB::table('linkpage')->insert($data);
    }

    public function Action($data = array())
    {
        $name = $data['name'];
        $code = $data['code'];
        $showorder = $data['showorder'];
        $type = isset($data['type']) ? $data['type'] : "";
        unset($data['type']);
        $riqi = date('Y-m-d H:i:s');
        if ($type == "add") {

            foreach ($name as $key => $val) {
                if ($val != "") {
                    $sql = "insert into {$this->dbfix}linkpage_type set `name`='" . $name[$key] . "',`code`='" . $code[$key] . "',`showorder`='" . $showorder[$key] . "', `createdate`='" . $riqi . "', `status`=1 ";
                    DB::query($sql);
                }
            }
        } else {
            $typeid = $data['typeid'];
            $value = $data['value'];
            foreach ($name as $key => $val) {
                if ($val != "") {
                    $sql = "insert into {$this->dbfix}linkpage set `typeid`='" . $typeid . "',`name`='" . $name[$key] . "',`value`='" . $value[$key] . "',`showorder`='" . $showorder[$key] . "', `createdate`='" . $riqi . "', `status`=1 ";
                    DB::query($sql);
                }
            }
        }
        return true;
    }
}