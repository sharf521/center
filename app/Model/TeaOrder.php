<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/4
 * Time: 10:17
 */

namespace App\Model;

class TeaOrder extends Model
{
    protected $table='tea_order';
    public function __construct()
    {
        parent::__construct();
    }

    public function add($ids,$nums,$post)
    {
        $user_id=$post['user_id'];
        $this->user_id=$user_id;
        $this->username=$post['username'];
        $this->order_sn=time().rand(10000,99999);
        $this->contacts=$post['contacts'];
        $this->phone=$post['phone'];
        $this->province=$post['province'];
        $this->city=$post['city'];
        $this->area=$post['area'];
        $this->address=$post['address'];
        $this->zipcode=$post['zipcode'];
        $total=0;
        foreach ($ids as $key => $id) {
            $id=(int)$id;
            $_num=(int)$nums[$key];
            if($id>0 && $_num>0){
                $package=(new TeaPackage())->find($id);
                $money=math($package->money,$package->discount,'*',2);
                $money=math($money,$_num,'*',2);
                $total=math($total,$money,'+',2);
                $orderGoods=new TeaOrderGoods();
                $orderGoods->order_sn=$this->order_sn;
                $orderGoods->goods_id=$id;
                $orderGoods->quantity=$_num;
                $orderGoods->goods_name=$package->name;
                $orderGoods->price=$package->money;
                $orderGoods->discount=$package->discount;
                $orderGoods->goods_image=$package->picture;
                $orderGoods->save();
            }
        }
        if($total<=0){
            throw new \Exception('请选择套餐！');
        }
        $teaMoney =(new TeaMoney())->find($user_id);
        if($total > $teaMoney->money){
            throw new \Exception('您的电子币不足！');
        }
        $this->order_money=$total;
        $this->status=1;
        $this->save();
        $log = array(
            'user_id' => $user_id,
            'type' => 'buyPackage',
            'money' => '-'.$total,
            'remark' => "订单号：{$this->order_sn}"
        );
        $teaMoney->addLog($log);

        //替别人注册
        if($post['regTeaUser']===true){
            $arr=array(
                'user_id'=>$post['tea_userid'],
                'money'=>$total,
                'p_userid'=>$user_id
            );
            (new Tea())->add($arr);
        }
    }

    public function OrderGoods()
    {
        return $this->hasMany('\App\Model\TeaOrderGoods','order_sn','order_sn');
    }
}