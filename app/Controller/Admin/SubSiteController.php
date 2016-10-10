<?php

namespace App\Controller\Admin;

use App\Model\SubSite;
use System\Lib\Request;

class SubSiteController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(SubSite $site)
    {
        $data['list']=$site->orderBy('id desc')->get();
        $this->view('subSite',$data);
    }

    public function add(Request $request,SubSite $site)
    {
        if($_POST){
            $site->name=$request->post('name');
            $site->domain=$request->post('domain');
            $site->logo=$request->post('logo');
            $site->title=$request->post('title');
            $site->keywords=$request->post('keywords');
            $site->description=$request->post('description');
            $site->cloud_url=$request->post('cloud_url');
            $site->cloud_url_wap=$request->post('cloud_url_wap');
            $site->pos_url=$request->post('pos_url');
            $site->pos_url_wap=$request->post('pos_url_wap');
            $site->mall_url=$request->post('mall_url');
            $site->mall_url_wap=$request->post('mall_url_wap');
            $site->crowd_url=$request->post('crowd_url');
            $site->crowd_url_wap=$request->post('crowd_url_wap');
            $site->save();
            redirect('subSite')->with('msg','添加成功！');
        }else{
            $this->view('subSite');
        }
    }
    public function edit(Request $request,SubSite $site)
    {
        $site=$site->findOrFail($request->id);
        if($_POST){
            $site->name=$request->post('name');
            $site->domain=$request->post('domain');
            $site->logo=$request->post('logo');
            $site->title=$request->post('title');
            $site->keywords=$request->post('keywords');
            $site->description=$request->post('description');
            $site->cloud_url=$request->post('cloud_url');
            $site->cloud_url_wap=$request->post('cloud_url_wap');
            $site->pos_url=$request->post('pos_url');
            $site->pos_url_wap=$request->post('pos_url_wap');
            $site->mall_url=$request->post('mall_url');
            $site->mall_url_wap=$request->post('mall_url_wap');
            $site->crowd_url=$request->post('crowd_url');
            $site->crowd_url_wap=$request->post('crowd_url_wap');
            $site->save();
            redirect('subSite')->with('msg','保存成功！');
        }else{
            $data['row']=$site;
            $this->view('subSite',$data);
        }
    }
    public function delete(Request $request,SubSite $site)
    {
        $site=$site->findOrFail($request->id);
        $site->delete();
        redirect('subSite')->with('msg','删除成功！');
    }

}