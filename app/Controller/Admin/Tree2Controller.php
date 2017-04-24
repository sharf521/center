<?php
namespace App\Controller\Admin;

use App\Model\Tree2;
use App\Model\Tree2Log;
use App\Model\Tree2Profit;
use System\Lib\DB;
use System\Lib\Request;

class Tree2Controller extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    function index(Request $request, Tree2 $tree2)
    {
        $user_id = (int)$request->get('user_id');
        $id = (int)$request->get('id');
        $money = (int)$request->get('money');
        $where = "1=1";
        if (!empty($user_id)) {
            $where .= " and user_id={$user_id}";
        }
        if (!empty($money)) {
            $where .= " and money={$money}";
        }
        if (!empty($id)) {
            $pids = DB::table('tree2')->where("id=?")->bindValues($id)->value('pids');
            $where .= " and  pids like '{$pids}%'";
        }
        $data['result'] = $tree2->where($where)->orderBy('id desc')->pager($_GET['page'], 10);
        $this->view('tree2', $data);
    }

    function add($data)
    {
        if ($_POST) {
            try{
                DB::beginTransaction();

                $post = array(
                    'user_id' => $_POST['user_id'],
                    'p_userid' => (int)$_POST['p_userid'],
                    'money' => $_POST['money']
                );
                (new Tree2())->add($post);

                DB::commit();
                redirect('tree2')->with('msg','添加成功！');
            }catch(\Exception $e){
                DB::rollBack();
                $error= "Failed: " . $e->getMessage();
                redirect()->back()->with('error',$error);
            }
        } else {
            $this->view('tree2', $data);
        }
    }

    function calTree2(Request $request)
    {
        if($_POST){
            $typeid=$request->post('typeid');
            $money=(float)$request->post('money');
            $car_money=(float)$request->post('car_money');
            $transfer_money=(float)$request->post('transfer_money');
            if($money==0){
                redirect()->back()->with('error','套餐金额为空');
            }
            try {
                DB::beginTransaction();
                switch ($typeid){
                    case 'default':
                        (new Tree2())->calTree2_default($money,$car_money,$transfer_money);
                        break;
                    case 'zong' :
                        $layer2first_money=(float)$request->post('layer2first_money');
                        $layer2full_money=(float)$request->post('layer2full_money');
                        $layer3full_money=(float)$request->post('layer3full_money');
                        $layer4dian_money=(float)$request->post('layer4dian_money');
                        $layer5fist_money=(float)$request->post('layer5fist_money');
                        $layer6fist_money=(float)$request->post('layer6fist_money');
                        (new Tree2())->calTree2_zong($money,$layer2first_money,$layer2full_money,$layer3full_money,$layer4dian_money,$layer5fist_money,$layer6fist_money);
                        break;
                    case 'type1' :
                        (new Tree2())->calTree2_type1($money,$car_money);
                        break;
                    case 'type2' :
                        (new Tree2())->calTree2_type2($money,$car_money);
                        break;
                    case 'type3':
                        (new Tree2())->calTree2_type3($money,$car_money);
                        break;
                    case 'type4':
                        (new Tree2())->calTree2_type4($money,$car_money);
                        break;
                    case 'type5':
                        $layer3full_money=(float)$request->post('layer3full_money');
                        (new Tree2())->calTree2_type5($money,$car_money,$layer3full_money);
                        break;
                    case 'type6':
                        (new Tree2())->calTree2_type6($money,$car_money);
                        break;
                }
                DB::commit();
                redirect('tree2/tree2log')->with('msg','计算完成！');
            } catch (\Exception $e) {
                DB::rollBack();
                $error= "Failed: " . $e->getMessage();
                redirect()->back()->with('error',$error);
            }
        }else{
            $this->view('tree2_call');
        }
    }

    function tree2log(Tree2Log $tree2Log)
    {
        $arr = array(
            'typeid' => $_GET['typeid'],
            'user_id' => (int)$_GET['user_id'],
            'tree_id' => (int)$_GET['tree_id'],
            'in_tree_id' => (int)$_GET['in_tree_id'],
            'in_user_id' => (int)$_GET['in_user_id'],
            'money' => (float)$_GET['money']
        );
        $where = " 1=1";
        if (!empty($arr['user_id'])) {
            $where .= " and user_id={$arr['user_id']}";
        }
        if (!empty($arr['money'])) {
            $where .= " and money={$arr['money']}";
        }
        if (!empty($arr['tree_id'])) {
            $where .= " and tree_id={$arr['tree_id']}";
        }
        if (!empty($arr['in_tree_id'])) {
            $where .= " and in_tree_id={$arr['in_tree_id']}";
        }
        if (!empty($arr['in_user_id'])) {
            $where .= " and in_user_id={$arr['in_user_id']}";
        }
        if (!empty($arr['typeid'])) {
            $where .= " and typeid='{$arr['typeid']}'";
        }
        $data['result'] = $tree2Log->where($where)->orderBy('id desc')->pager($_GET['page'], 10);

        $user_count=(new Tree2Profit())->lists('user_count');
        $rate=(new Tree2Profit())->lists('rate');
        $data['user_count']=$user_count;
        $data['rate']=$rate;
        $data['received']=(new Tree2Profit())->lists('received');
        $data['support']=(new Tree2Profit())->lists('support');;

        $this->view('tree2', $data);
    }
}