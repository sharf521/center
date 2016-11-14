<?php
namespace App\Model;

use System\Lib\DB;

class User extends Model
{
    protected $table = 'user';

    public function __construct()
    {
        parent::__construct();
        $this->fields = array('name', 'username', 'password', 'addtime', 'status', 'lastip', 'headimgurl','nickname', 'times', 'zf_password', 'email', 'tel', 'qq', 'address');
    }

    function logout()
    {
        session()->remove('user_id');
        session()->remove('username');
        session()->remove('usertype');
        session()->remove('permission_id');
    }

    function login($data)
    {
        $user=array();
        if ($data['direct'] == '1') {
            if (isset($data['id'])) {
                $user = DB::table('user')->where("id=?")->bindValues($data['id'])->row();
            } elseif (isset($data['openid'])) {
                $user = DB::table('user')->where("openid=?")->bindValues($data['openid'])->row();
            }
        } else {
            $user = DB::table('user')->where("username=?")->bindValues($data['username'])->row();
            $id = (int)$user['id'];
            if ($id == 0) {
                return '用户名或密码错误';
            } elseif ($user['password'] != md5(md5($data['password']) . $user['salt'])) {
                return '用户名或密码错误！';
            }
        }
        if (!empty($user)) {
            if ($data['admin'] == true) {
                $usertype = DB::table('usertype')->select('id,permission_id,is_admin')->where("id={$user['type_id']}")->row();
                if ($usertype['is_admin'] != 1) {
                    return '会员禁止登陆！';
                }
                session()->set('usertype', $usertype['id']);
                session()->set('permission_id', $usertype['permission_id']);
            } else {
                session()->set('usertype', 0);
                session()->set('permission_id', '');
            }
            session()->set('user_id', $user['id']);
            session()->set('username', $user["username"]);
            return true;
        } else {
            return '未知错误!';
        }
    }

    public function register($data)
    {
        $check = $this->checkUserName($data['username']);
        if ($check !== true) {
            return $check;
        }
        if (strlen($data['password']) > 15 || strlen($data['password']) < 6) {
            return "密码长度6位到15位！";
        }
        if ($data['password'] != $data['sure_password']) {
            return "两次输入密码不同！";
        }
        $check = $this->checkEmail($data['email']);
        if ($check !== true) {
            return $check;
        }
        //验证邀请人
        $check_arr=array(
            'username'=>$data['invite_user'],
            'app_id'=>$data['app_id'],
            'appid'=>$data['appid']
        );
        $invite_arr=$this->checkInvetUser($check_arr);
        if($invite_arr['status']!==true){
            return $invite_arr['msg'];
        }else{
            $invite_userid=$invite_arr['invite_userid'];
            $invite_path=$invite_arr['invite_path'];
        }
        $salt = rand(100000, 999999);
        $data = array(
            'type_id' => 1,
            'username' => $data['username'],
            'password' => md5(md5($data['password']) . $salt),
            'zf_password' => md5(md5($data['password']) . $salt),
            'created_at' => time(),
            'status' => 0,
            'email' => $data['email'],
            'salt' => $salt,
            'invite_userid' => $invite_userid,
            'invite_path'=>$invite_path
        );
        $id = DB::table('user')->insertGetId($data);
        if (is_numeric($id) && $id > 0) {
            if($data['no_login']!==true){  //不需要登陆
                session()->set('user_id', $id);
                session()->set('username', $data["username"]);
            }
            return true;
        } else {
            return $id;
        }
    }

    public function checkInvetUser($data=array('username'=>'','app_id'=>0,'appid'=>''))
    {
        $username=$data['username'];
        $app_id=(int)$data['app_id'];
        $appid=$data['appid'];

        $invite_userid=0;
        $invite_path='';
        $return=array();
        $return['status']=false;
        if(!empty($username) ){
            if(empty($app_id) && empty($appid)){
                $invite_user=DB::table('user')->select('id,invite_path')->where("username=?")->bindValues($username)->row();
                if($invite_user){
                    $invite_userid=$invite_user['id'];
                    $invite_path=$invite_user['invite_path'].$invite_user['id'].',';
                }else{
                    $return['msg']="推荐人不存在！";
                    return $return;
                }
            }else{
                if(empty($app_id)){
                    $app_id=DB::table('app')->where('appid=?')->bindValues($appid)->value('id');
                }
                $invite_user=DB::table('user u')->select('u.id,u.invite_path')
                    ->leftJoin('app_user au',"u.id=au.user_id")
                    ->where("u.username=? and au.app_id=?")
                    ->bindValues(array($username,$app_id))->row();
                if($invite_user){
                    $invite_userid=$invite_user['id'];
                    $invite_path=$invite_user['invite_path'].$invite_user['id'].',';
                }else{
                    $return['msg']="推荐人不存在！";
                    return $return;
                }
            }
        }
        $return['status']=true;
        $return['invite_userid']=$invite_userid;
        $return['invite_path']=$invite_path;
        return $return;
    }

    function getlist($data = array())
    {
        $_select = " u.*,ut.name as typename,uu.username invite_name";
        $where = " 1=1";
        if (!empty($data['type_id'])) {
            $where .= " and u.type_id={$data['type_id']}";
        }
        if (!empty($data['username'])) {
            $where .= " and u.username like '{$data['username']}%'";
        }
        return DB::table('user u')->select($_select)
            ->leftJoin('user uu', 'u.invite_userid=uu.id')
            ->leftJoin('usertype ut', 'u.type_id=ut.id')
            ->where($where)
            ->page($data['page'], $data['epage']);
    }

    //修改密码
    public function updatePwd($data)
    {
        $user = $this->findOrFail($data['id']);
        if (strlen($data['password']) > 15 || strlen($data['password']) < 6) {
            return "密码长度6位到15位！";
        } elseif (isset($data['old_password'])) {
            if ($user->password != md5(md5($data['old_password']) . $user->salt)) {
                return '原密码错误！';
            }
        }
        $user->password = md5(md5($data['password']) . $user->salt);
        return $user->save();
    }

    //修改支付密码
    public function updateZfPwd($data)
    {
        $user = $this->findOrFail($data['id']);
        if (strlen($data['zf_password']) > 15 || strlen($data['zf_password']) < 6) {
            return "支付密码长度6位到15位！";
        } elseif (isset($data['old_password'])) {
            if ($user->zf_password != md5(md5($data['old_password']) . $user->salt)) {
                return '原密码错误！';
            }
        }
        $user->zf_password = md5(md5($data['zf_password']) . $user->salt);
        return $user->save();
    }

    //验证支付密码
    public function checkPayPwd($pwd,$user)
    {
        if ($user->zf_password == md5(md5($pwd) . $user->salt)) {
            return true;
        }else{
            return false;
        }
    }



    //用户管理编辑
    function edit($data = array())
    {
        $id = (int)$data['id'];
        unset($data['id']);
        $data = $this->filterFields($data, $this->fields);
        return DB::table('user')->where('id=?')->bindValues($id)->limit(1)->update($data);
    }

    public function checkEmail($email)
    {
        if (empty($email)) {
            return '电子邮件不能为空';
        }
        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,5}(\\.[a-z]{2})?)$/i";
        if (preg_match($pattern, $email)) {
            $id = DB::table('user')->where("email=?")->bindValues($email)->value('id', 'int');
            if ($id > 0) {
                return '该 电子邮件 已经被注册';
            }
            return true;
        } else {
            return "电子邮件 格式有误！";
        }
    }

    public function checkUserName($username)
    {
        if (strlen($username) < 5 || strlen($username) > 30) {
            return "用户名长度5位到15位！";
        } else {
            $id = DB::table('user')->where("username=?")->bindValues($username)->value('id', 'int');
            if ($id > 0) {
                return '用户名已经存在';
            }
            return true;
        }
    }

    /**
     * @return \App\Model\UserType
     */
    public function UserType()
    {
        return $this->hasOne('App\Model\UserType', 'id', 'type_id');
    }

    public function Account()
    {
        return $this->hasOne('App\Model\Account','user_id','id');
    }
    public function Bank()
    {
        return $this->hasOne('App\Model\AccountBank','user_id','id');
    }

    //实名认证
    public function UserInfo()
    {
        return $this->hasOne('App\Model\UserInfo','user_id','id');
    }

    public  function Invite()
    {
        return $this->hasOne('App\Model\User', 'id','invite_userid');
    }
}