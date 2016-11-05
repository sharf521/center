<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/2
 * Time: 11:17
 */

namespace App\Controller\Platform;


use App\Model\TeaMoney;
use App\Model\TeaOrder;
use App\Model\TeaOrderGoods;
use App\Model\TeaPackage;
use App\Model\User;
use System\Lib\DB;
use System\Lib\Request;

class PackageController extends PlatformController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function register(TeaPackage $package,Request $request,TeaMoney $teaMoney)
    {
        $user_id=$this->user_id;
        $teaMoney =$teaMoney->find($user_id);
        if($_POST){
            $ids = $request->post('id');
            $num = $request->post('num');
            try {
                DB::beginTransaction();

                $checkPwd=$this->user->checkPayPwd($request->post('zf_password'),$this->user);
                if($checkPwd!==true){
                    throw new \Exception('支付密码错误！');
                }

                if($request->post('regType')==1){
                    $data = array(
                        'no_login'=>true,
                        'username' => $request->post('username'),
                        'email'=>$request->post('email'),
                        'password' => $request->post('password'),
                        'sure_password'=>$request->post('sure_password'),
                        'invite_user'=>$this->username
                    );
                    $result = (new User())->register($data);
                    if ($result !== true) {
                        throw new \Exception($result);
                    }
                    $user=(new User())->where("username=?")->bindValues($request->post('username'))->first();
                }else{
                    $user=(new User())->where("username=?")->bindValues($request->post('username2'))->first();
                }

                $_POST['user_id']=$this->user_id;
                $_POST['username']=$this->username;

                $_POST['regTeaUser']=true;
                $_POST['tea_userid']=$user->id;
                (new TeaOrder())->add($ids,$num,$_POST);


                DB::commit();
                redirect('package/order')->with('msg','操作己完成！');
            } catch (\Exception $e) {
                DB::rollBack();
                $error= "Failed: " . $e->getMessage();
                redirect('package/register')->with('error',$error);
            }
        }else{
            $data['packages']=$package->where('status=1')->orderBy('showorder')->get();
            $data['teaMoney']=$teaMoney;
            $this->view('package_reg',$data);
        }
    }

    public function index(TeaPackage $package,Request $request,TeaMoney $teaMoney)
    {
        $user_id=$this->user_id;
        $teaMoney =$teaMoney->find($user_id);
        if($_POST){
            $ids = $request->post('id');
            $num = $request->post('num');
            try {
                DB::beginTransaction();

                $checkPwd=$this->user->checkPayPwd($request->post('zf_password'),$this->user);
                if($checkPwd!==true){
                    throw new \Exception('支付密码错误！');
                }
                $_POST['user_id']=$user_id;
                $_POST['username']=$this->username;
                (new TeaOrder())->add($ids,$num,$_POST);


                DB::commit();
                redirect('package/order')->with('msg','己成功下单！');
            } catch (\Exception $e) {
                DB::rollBack();
                $error= "Failed: " . $e->getMessage();
                redirect('package')->with('error',$error);
            }
        }else{
            $data['packages']=$package->where('status=1')->orderBy('showorder')->get();
            $data['teaMoney']=$teaMoney;
            $this->view('package',$data);
        }
    }

    public function order(TeaOrder $teaOrder)
    {
        $where = " user_id={$this->user_id}";
        $data['result']=$teaOrder->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $this->view('order',$data);
    }

    public function get(Request $request,TeaPackage $package)
    {
        $id=(int)$request->get('id');
        $row=$package->where("id={$id}")->first(true);
        $row['money_dis']=math($row['money'],$row['discount'],'*',2);
        $row['discount_show']=math($row['discount'],100,'*',2).'%';
        echo json_encode($row);
    }
}