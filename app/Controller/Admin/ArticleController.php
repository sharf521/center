<?php
namespace App\Controller\Admin;

use App\Model\Article;
use App\Model\Category;
use System\Lib\Request;

class ArticleController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Article $article, Category $category)
    {
        $where = ' status>-1 ';
        if (!empty($_GET['categorypath'])) {
            $where .= " and category_path like '{$_GET['categorypath']}%'";
        }
        if (!empty($_GET['keyword'])) {
            $where .= " and title like '%{$_GET['keyword']}%'";
        }
        $result = $article->orderBy('id desc')->where($where)->pager($_GET['page']);
        $data['result'] = $result;
        $data['cates'] = $category->echoOption(array('pid' => 2, 'path' => $_GET['categorypath']));
        $this->view('article', $data);
    }

    //添加文章
    function add(Category $category)
    {
        if ($_POST) {
            $errormsg = "";
            if ($_POST['title'] == "") {
                $errormsg .= "文章标题不能为空<br>";
                redirect()->back()->with('error','文章标题不能为空');
            }
            if ($_POST['categoryid'] == "") {
                $errormsg .= "文章分类必选<br>";
            }
            if (!empty($_POST['lable'])) {
                $lable = $this->mysql->one('article', array('lable' => $_POST['lable'], 'subsite_id' => $_POST['subsite_id']));
                if (is_array($lable)) {
                    $errormsg .= "该标签已存在<br>";
                }
            }
            if ($errormsg != "") {
                show_msg(array($errormsg));
                exit;
            }
            //分类start
            $arr_category = $_POST['categoryid'];
            $categoryid = $arr_category[count($arr_category) - 1];
            if (empty($categoryid)) {
                //最后一个元素为空取末第二个
                $categoryid = $arr_category[count($arr_category) - 2];
            }
            $categoryid = (int)$categoryid;
            if ($categoryid != 0) {
                $row = $this->mysql->one('category', array("id" => $categoryid));
                $categorypath = $row['path'];
            }
            //分类end

            //添加文章信息
            $arr = array();
            $arr['user_id'] = $this->user_id;
            $arr['subsite_id'] = $_POST['subsite_id'];
            $arr['title'] = $_POST['title'];
            $arr['typeid'] = (int)$_POST['typeid'];
            $arr['categoryid'] = $categoryid;
            $arr['categorypath'] = $categorypath;
            $arr['lable'] = $_POST['lable'];
            $arr['status'] = $_POST['status'];
            $arr['picture'] = $_POST['picture'];
            $arr['addtime'] = date('Y-m-d H:i:s');
            $result = $this->mysql->insert('article', $arr);
            if ($result !== true) {
                show_msg(array('添加文章信息失败'));
                exit;
            }
            $artice_id = $this->mysql->insert_id();

            //添加文章内容
            $arr = array();
            $arr['id'] = $artice_id;
            $arr['content'] = $_POST['content'];
            $result = $this->mysql->insert('article_data', $arr);
            if ($result !== true) {
                show_msg(array('添加文章内容失败'));
                exit;
            }

            show_msg(array('添加文章成功', '', $this->base_url('article')));
        } else {
            //一级分类
            $data['cates'] = $category->getlist(array('pid' => 2));
            $this->view('article', $data);
        }
    }

    //修改文章
    function edit()
    {
        $id = (int)$_REQUEST['id'];
        if ($_POST) {
            $errormsg = "";
            if ($_POST['title'] == "") {
                $errormsg .= "文章标题不能为空<br>";
            }
            if ($_POST['categoryid'] == "") {
                $errormsg .= "文章分类必选<br>";
            }
            if (!empty($_POST['lable'])) {
                $sql = "select * from plf_article where lable='{$_POST['lable']}' and subsite_id={$_POST['subsite_id']} and id != {$id}";
                $lable = $this->mysql->get_one($sql);
                if (is_array($lable)) {
                    $errormsg .= "该标签已存在<br>";
                }
            }
            if ($errormsg != "") {
                show_msg(array($errormsg));
                exit;
            }
            //分类start
            $arr_category = $_POST['categoryid'];
            $categoryid = $arr_category[count($arr_category) - 1];
            if (empty($categoryid)) {
                //最后一个元素为空取末第二个
                $categoryid = $arr_category[count($arr_category) - 2];
            }
            $categoryid = (int)$categoryid;
            if ($categoryid != 0) {
                $row = $this->mysql->one('category', array("id" => $categoryid));
                $categorypath = $row['path'];
            }
            //分类end

            //修改文章信息
            $arr = array();
            $arr['user_id'] = $this->user_id;
            $arr['subsite_id'] = $_POST['subsite_id'];
            $arr['title'] = $_POST['title'];
            $arr['typeid'] = $_POST['typeid'];
            $arr['categoryid'] = $categoryid;
            $arr['categorypath'] = $categorypath;
            $arr['lable'] = $_POST['lable'];
            $arr['status'] = $_POST['status'];
            $arr['picture'] = $_POST['picture'];
            $arr['edittime'] = date('Y-m-d H:i:s');
            $result = $this->mysql->update('article', $arr, "id={$id}");
            if ($result !== true) {
                show_msg(array('修改文章信息失败'));
                exit;
            }

            //修改文章内容
            $arr = array();
            $arr['content'] = $_POST['content'];
            $result = $this->mysql->update('article_data', $arr, "id={$id}");
            if ($result !== true) {
                show_msg(array('修改文章内容失败'));
                exit;
            }

            show_msg(array('修改文章成功', '', $this->base_url('article')));
        } else {
            //一级分类
            $data['cates'] = m('category/getlist', array('pid' => 7));
            $data['row'] = m('article/getone', array('id' => $id));
            $categoryid = $data['row']['categoryid'];
            $categorypath = $data['row']['categorypath'];
            $categorypath = explode(',', $categorypath);
            array_shift($categorypath);
            array_pop($categorypath);
            $i = 1;
            $str = '';
            foreach ($categorypath as $c) {

                $sel = "getsel($i,$c);";
                $str .= $sel;
                $i++;
            }
            $data['row']['sel'] = $str;

            $subsite = m('substation/getlist');
            foreach ($subsite as $row) {
                $data['subsite'][$row['id']] = $row['name'];
            }

            $this->view('article', $data);
        }
    }

    //文章状态切换
    public function change(Article $article, Request $request)
    {
        $id = $request->get('id', 'int');
        $page = $request->get('page', 'int');
        $art = $article->findOrFail($id);
        if ($art->status == '1') {
            $art->status = 0;
        } else {
            $art->status = 1;
        }
        //var_dump($art);
        $art->save();
        redirect('article/?page=' . $page)->with('msg', '操作成功！');
    }

    //删除文章
    public function delete(Article $article, Request $request)
    {
        $id = $request->get('id', 'int');
        $page = $request->get('page', 'int');
        $art = $article->findOrFail($id);
        $art->status = -1;
        
        if ($art->save()) {
            redirect('article/?page=' . $page)->with('msg', '删除成功！');
        } else {
            redirect('article/?page=' . $page)->with('error', '删除失败！');
        }
    }
}