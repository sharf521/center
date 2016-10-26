<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/22
 * Time: 17:05
 */

namespace App\Controller\Admin;


use App\Model\Tea;
use App\Model\TeaGroup;
use System\Lib\DB;
use System\Lib\Request;

class TeaController extends AdminController
{
    public function index(Tea $tea,TeaGroup $group,Request $request)
    {
        $arr=array(
            'user_id'		=>(int)$_GET['user_id'],
            'id'		=>(int)$_GET['id'],
            'money'		=>(int)$_GET['money'],
            'group_id'		=>(int)$_GET['group_id'],
        );
        $invite_uid=(int)$request->get('invite_uid');
        $where = " 1=1";
        if (!empty($arr['user_id'])) {
            $where .= " and user_id={$arr['user_id']}";
        }
        if (!empty($arr['money'])) {
            $where .= " and money={$arr['money']}";
        }
        if (!empty($arr['group_id'])) {
            $where .= " and group_id={$arr['group_id']}";
        }
        if (!empty($arr['id'])) {
            $where .= " and  id={$arr['id']}";
        }
        if($invite_uid!=0){
            $ids = $tea->where("user_id=?")->bindValues($invite_uid)->lists('id');
            if(count($ids)>0){
                $ids=implode(',',$ids);
                $where .= " and  invite_id in({$ids})";
            }else{
                $where.=' and invite_id=-1';
            }
        }
        $data['result']=$tea->where($where)->orderBy('id desc')->pager($_GET['page'],10);


        $groups=$group->orderBy('id')->get();
        foreach($groups as $i=>$group){
            $teas=DB::table('tea')->select("id,user_id,pid,invite_count")->where("group_id={$group->id}")->orderBy('id')->all();
            $teas= $this->genTree($teas);
            $teas= $this->returnChatData($teas);
            $groups[$i]->datas=$teas;
        }
        $data['groups']=$groups;
        $this->view('tea',$data);
    }

    private function returnChatData($datas)
    {
        $string = '';
        foreach ($datas as $data) {
            if ($string == '') {
                $string .= '{';
            } else {
                $string .= ',{';
            }
            $string .= "name:'{$data['id']}_user:{$data['user_id']}',value:{$data['invite_count']}";
            //if (isset($data['children'])){
                $string .= ',children:';
                $string .= ' [';
                $string .= $this->returnChatData($data['children']);
                $string .= ']';
            //}
            $string .= '}';
        }
        return $string;
    }

    private function genTree($data)
    {
        //结果转换为特定格式
        $items = array();
        foreach ($data as $row) {
            $items[$row['id']] = $row;
        }
        $tree = array(); //格式化好的树
        foreach ($items as $item){
            if (isset($items[$item['pid']]))
                $items[$item['pid']]['children'][] = &$items[$item['id']];
            else
                $tree[] = &$items[$item['id']];
        }
        return $tree;
    }

    public function add(Tea $tea)
    {
        if ($_POST) {
            try {
                DB::beginTransaction();

                $post = array(
                    'user_id' => $_POST['user_id'],
                    'p_userid' => (int)$_POST['p_userid'],
                    'money' => $_POST['money']
                );
                $tea->add($post);

                DB::commit();

                redirect('tea/')->with('msg', '添加成功！');
            } catch (\Exception $e) {
                DB::rollBack();
                $error = "Failed: " . $e->getMessage();
                redirect()->back()->with('error', $error);
            }
        } else {
            $this->view('tea');
        }
    }

    public function call(Tea $tea)
    {
        try {
            DB::beginTransaction();

            $tea->call();
            DB::commit();
            redirect('tea/')->with('msg', '完成！');
        } catch (\Exception $e) {
            DB::rollBack();
            $error= "Failed: " . $e->getMessage();
            redirect()->back()->with('error', $error);
        }
    }
}