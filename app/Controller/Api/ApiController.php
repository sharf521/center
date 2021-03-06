<?php
namespace App\Controller\Api;

use System\Lib\Controller as BaseController;
use System\Lib\DB;

class ApiController extends BaseController
{
    protected $app_id;
    protected $appsecret;
    protected $data;

    public function __construct()
    {
        parent::__construct();
        $this->data = json_decode($_POST['data'], true);
        $msg = $this->checkSign($this->data);
        if ($msg !== true) {
            $this->returnError($msg);
            exit;
        }
    }

    public function error()
    {
        echo 'not find page';
    }

    //签名
    protected function checkSign($data)
    {
        if (abs(time() - $data['time']) > 600) {
            return 'time over';
        }
        $row = DB::table('app')->where('appid=?')->bindValues($data['appid'])->row();
        $this->appsecret = $row['appsecret'];
        $this->app_id = $row['id'];
        if (empty($this->appsecret)) {
            return 'check sign with appid error!';
        }
        if ($data['sign'] != $this->getSign($data)) {
            return 'check sign error';
        }
        return true;
    }

    protected function getSign($data)
    {
        if (isset($data['sign'])) {
            unset($data['sign']);
        }
        if (isset($data['data'])) {
            foreach ($data['data'] as $i => $v) {
                if (is_array($v)) {
                    ksort($data['data'][$i]);
                }
            }
        }
        ksort($data);
        $jsonStr = json_encode($data);
        $str = strtoupper(md5($jsonStr . $this->appsecret));
        return $str;
    }

    protected function getUserId($openid)
    {
        return DB::table('app_user')->where('app_id=? and openid=?')->bindValues(array($this->app_id, $openid))->value('user_id','int');
    }

    protected function returnSuccess($data = array())
    {
        $data['return_code'] = 'success';
        echo json_encode($data);
    }

    protected function returnError($msg)
    {
        $data = array(
            'return_code' => 'fail',
            'return_msg' => $msg
        );
        echo json_encode($data);
    }
}


