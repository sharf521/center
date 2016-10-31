<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/31
 * Time: 11:53
 */

namespace App\Controller\Admin;


use App\Model\TeaPackage;
use System\Lib\Request;

class TeaPackageController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request,TeaPackage $package)
    {
        if($_POST)
        {
            $id = $_POST['id'];
            $showorder = $_POST['showorder'];
            foreach ($id as $key => $val) {
                $package = $package->findOrFail($val);
                $package->showorder = intval($showorder[$key]);
                $package->save();
            }
            redirect('TeaPackage')->with('msg','操作成功！');
        }else{
            $user_id = (int)$request->get('user_id');
            $money = (int)$request->get('money');
            $where = "1=1";
            if (!empty($user_id)) {
                $where .= " and user_id={$user_id}";
            }
            if (!empty($money)) {
                $where .= " and money={$money}";
            }
            $data['result'] = $package->where($where)->orderBy('showorder,id')->pager($_GET['page'], 10);
            $this->view('tea_package',$data);
        }
    }

    public function add(Request $request,TeaPackage $package)
    {
        if($_POST){
            $package->name=$request->post('name');
            $package->picture=$request->post('picture');
            $package->money=(float)$request->post('money');
            $package->discount=(float)$request->post('discount');
            $package->title=$request->post('title');
            $package->remark=$request->post('remark');
            $package->status=1;
            $package->showorder=10;
            $package->save();
            redirect('TeaPackage')->with('msg','添加成功！');
        }else{
            $this->view('tea_package');
        }
    }
    public function edit(Request $request,TeaPackage $package)
    {
        $package=$package->findOrFail($request->id);
        if($_POST){
            $package->name=$request->post('name');
            $package->picture=$request->post('picture');
            $package->money=(float)$request->post('money');
            $package->discount=(float)$request->post('discount');
            $package->title=$request->post('title');
            $package->remark=$request->post('remark');
            $package->save();
            redirect('TeaPackage')->with('msg','保存成功！');
        }else{
            $data['row']=$package;
            $this->view('tea_package',$data);
        }
    }
    public function change(TeaPackage $package, Request $request)
    {
        $id = (int)$request->get('id');
        $page = (int)$request->get('page');
        $art = $package->findOrFail($id);
        if ($art->status == '1') {
            $art->status = 0;
        } else {
            $art->status = 1;
        }
        $art->save();
        redirect('TeaPackage/?page=' . $page)->with('msg', '操作成功！');
    }
    public function delete(Request $request,TeaPackage $package)
    {
        $package=$package->findOrFail($request->id);
        $package->delete();
        redirect('TeaPackage')->with('msg','删除成功！');
    }
}