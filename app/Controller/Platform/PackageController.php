<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/2
 * Time: 11:17
 */

namespace App\Controller\Platform;


use App\Model\TeaPackage;
use System\Lib\Request;

class PackageController extends PlatformController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(TeaPackage $package,Request $request)
    {
        if($_POST){
            $ids = $request->post('id');
            $num = $request->post('num');
            $total=0;
            foreach ($ids as $key => $id) {
                $id=(int)$id;
                $_num=(int)$num[$key];
                if($id>0 && $_num>0){
                    $package=$package->find($id);
                    $money=math($package->money,$package->discount,'*',2);
                    $money=math($money,$_num,'*',2);
                    $total=math($total,$money,'+',2);
                }
            }
            echo $total;
        }else{
            $data['packages']=$package->where('status=1')->orderBy('showorder')->get();
            $this->view('package',$data);
        }
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