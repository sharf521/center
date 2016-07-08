<?php
namespace App\Model;

use System\Lib\DB;

class User extends Model
{
    protected $table='user';
    public function __construct()
    {
        parent::__construct();
        $this->fields = array('name', 'username', 'password', 'addtime', 'status', 'lastip', 'portrait', 'times', 'zf_password', 'email', 'tel', 'qq', 'address');
    }

    function logout()
    {
        session()->remove('user_id');
        session()->remove('username');
        session()->remove('lastip');
        session()->remove('usertype');
        session()->remove('permission_id');
    }

    function login($data)
    {
        $return['status'] = 0;
        if ($data['direct'] == '1') {
            //后台WAP登录
            $uid = (int)$data['id'];
        } else {

            list($uid, $username, $password, $email) = outer_call('uc_user_login', array($data['username'], $data['password']));

            //$uid=1;

        }
        if ($uid > 0) {
            $return['status'] = 1;
            $user = DB::table('user')->select('*')->where("id=?")->bindValues($uid)->row();
            if ($user) {
                if ($data['admin'] == true) {
                    $usertype = DB::table('usertype')->select('id,permission_id,is_admin')->where("id={$user['type_id']}")->row();
                    if ($usertype['is_admin'] != 1) {
                        $return['msg'] = '会员禁止登陆！';
                        return $return;
                    }
                    session()->set('usertype', $usertype['id']);
                    session()->set('permission_id', $usertype['permission_id']);
                } else {
                    session()->set('usertype', 0);
                    session()->set('permission_id', '');
                }
                session()->set('user_id', $user['id']);
                session()->set('username', $user["username"]);
                session()->set('lastip', $user["lastip"]);
                $arr = array(
                    'lastip' => ip()
                );
                DB::table('user')->where("id={$user["id"]}")->limit(1)->update($arr);
                return true;
            } else {
                $return['msg'] = '本地用户错误！';
            }
        } elseif ($uid == -1 || $uid == -2) {
            $return['msg'] = '用户名或密码错误！';
        } else {
            $return['msg'] = '未知错误！';
        }
        return $return;
    }

    function register($data)
    {
        if (empty($data['username'])) {
            return "用户名不能为空！";
        }
        if (strlen($data['username']) < 4 || strlen($data['username']) > 30) {
            return "用户名长度5位到15位！";
        }
        if (strlen($data['password']) == 0) {
            return "密码不能为空！";
        }
        if (strlen($data['password']) > 15 || strlen($data['password']) < 6) {
            return "密码长度6位到15位！";
        }
        if ($data['password'] != $data['sure_password']) {
            return "两次输入密码不同！";
        }
        if (strlen($data['email']) == 0) {
            return "邮箱不能为空！";
        }
        if (!empty($data['names'])) {
            $invite_userid = DB::table('user')->where('username=?')->bindValues($data['names'])->value('id', 'int');
            if ($invite_userid == 0) {
                return "介绍人用户名不正确！";
            }
        }
        $status = outer_call('uc_user_register', array($data['username'], $data['password'], $data['email']));
        if ($status > 0) {
             $data = array(
                'id' => $status,
                'type_id' => 3,
                'username' => $data['username'],
                'zf_password' => md5($data['password']),
                'addtime' => date('Y-m-d H:i:s'),
                'times' => 0,
                'status' => 0,
                'lastip'=>ip(),
                'email' => $data['email'],
                'invite_userid' => (int)$invite_userid
            );
            DB::table('user')->insert($data);
            return true;
        } elseif ($status == -1) {
            $returnmsg = '用户名不合法！';
        } elseif ($status == -2) {
            $returnmsg = '包含不允许注册的词语！';
        } elseif ($status == -3) {
            $returnmsg = '用户名已经存在！';
        } elseif ($status == -4) {
            $returnmsg = 'Email 格式有误！';
        } elseif ($status == -5) {
            $returnmsg = 'Email 不允许注册！';
        } elseif ($status == -6) {
            $returnmsg = '该 Email 已经被注册！';
        }
        return $returnmsg;
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
    function changepwd($data)
    {
        $status = outer_call('uc_user_edit', array($data['username'], $data['old_password'], $data['password'], ""));
        if ($status == 1) {
            $returnmsg = '修改密码成功！';
        } elseif ($status == -1) {
            $returnmsg = '原密码错误！';
        } elseif ($status == -7 || $status == 0) {
            $returnmsg = '没有做任何修改！';
        } else {
            $returnmsg = '未知错误！';
        }
        return $returnmsg;
    }

    //找回密码
    function updatepwd($data)
    {
        $status = outer_call('uc_user_edit', array($data['username'], "", $data['password'], "", 1));
        if ($status == 1 || $status == -7 || $status == 0) {
            $returnmsg = '重置密码成功！';
        } else {
            $returnmsg = '未知错误！';
        }
        return $returnmsg;
    }

    //修改支付密码
    function paypwd($data)
    {
        $arr['zf_password'] = md5($data['zf_password']);
        return DB::table('user')->where("id=?")->bindValues($data['id'])->limit(1)->update($arr);
    }

    //实名认证显示信息
    function userinfoone($data)
    {
        $select = "u.*,i.*,b.account";
        $where = "where u.id=" . $data['id'];
        $sql = "select {$select} from {$this->dbfix}user u 
left join {$this->dbfix}userinfo i on u.id=i.user_id 
left join {$this->dbfix}account_bank b on u.id=b.user_id {$where}";
        return $this->mysql->get_one($sql);
    }

    //用户管理编辑
    function edit($data = array())
    {
        $id = (int)$data['id'];
        unset($data['id']);
        $data = $this->filterFields($data, $this->fields);
        return DB::table('user')->where('id=?')->bindValues($id)->limit(1)->update($data);
    }

    /**
     * @return \App\Model\UserType
     */
    public function UserType()
    {
        return $this->hasOne('App\Model\UserType','id','type_id');
    }
}