<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/22
 * Time: 17:05
 */

namespace App\Controller\Admin;


use App\Model\LinkPage;
use App\Model\Tea;
use App\Model\TeaGroup;
use App\Model\TeaLog;
use App\Model\TeaMoney;
use App\Model\TeaOrder;
use App\Model\TeaUser;
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

    public function log(TeaLog $teaLog,LinkPage $linkPage)
    {
        $arr=array(
            'type'		=>$_GET['type'],
            'user_id'		=>(int)$_GET['user_id'],
            'money'		=>(int)$_GET['money']
        );

        $where = " 1=1";
        if (!empty($arr['user_id'])) {
            $where .= " and user_id={$arr['user_id']}";
        }
        if (!empty($arr['money'])) {
            $where .= " and money={$arr['money']}";
        }
        if (!empty($arr['type'])) {
            $where .= " and type='{$arr['type']}'";
        }

        $data['result']=$teaLog->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $data['account_type']=$linkPage->echoLink('tea_money_type',$_GET['type'],array('name'=>'type'));
        $this->view('tea',$data);
    }

    public function user(TeaUser $teaUser,Request $request)
    {
        $arr=array(
            'type'		=>$_GET['type'],
            'id'		=>(int)$_GET['id'],
            'money'		=>(int)$_GET['money']
        );

        $where = " 1=1";
        $where2=' 1=1';
        if (!empty($arr['id'])) {
            $where .= " and id={$arr['id']}";
            $where2.=" and user_id={$arr['id']}";
        }

        if (!empty($arr['money'])) {
            $where .= " and money={$arr['money']}";
        }

        $invite_id=(int)$request->get('invite_id');
        if($invite_id!=0){
            $where.=" and invite_id={$invite_id}";
        }
        $data['result']=$teaUser->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $data['moneySum']=(new TeaMoney())->where($where2)->value('sum(money)');
        $this->view('tea',$data);
    }

    public function order(TeaOrder $teaOrder)
    {
        $arr=array(
            'type'		=>$_GET['type'],
            'user_id'		=>(int)$_GET['user_id'],
            'money'		=>(int)$_GET['money']
        );

        $where = " 1=1";
        if (!empty($arr['user_id'])) {
            $where .= " and user_id={$arr['user_id']}";
        }
        if (!empty($arr['money'])) {
            $where .= " and money={$arr['money']}";
        }

        $data['result']=$teaOrder->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $this->view('tea_order',$data);
    }

    public function order_shipping(TeaOrder $teaOrder,Request $request)
    {
        $teaOrder=$teaOrder->findOrFail($request->get('id'));
        if($_POST){
            $teaOrder->shipping_name=$request->post('shipping_name');
            $teaOrder->shipping_no=$request->post('shipping_no');
            $teaOrder->shipping_fee=(float)$request->post('shipping_fee');
            $teaOrder->status=2;
            $teaOrder->save();
            redirect('tea/order')->with('msg','保存成功！');
        }
        $data['order']=$teaOrder;
        $this->view('tea_order',$data);
    }
}