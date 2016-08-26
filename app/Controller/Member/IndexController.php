<?php
namespace App\Controller\Member;

use App\Model\App;
use App\Model\AppUser;
use App\Model\User;
use System\Lib\DB;
use System\Lib\Request;

class IndexController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['account'] = DB::table('account')->where('user_id=?')->bindValues($this->user_id)->row();
        $data['title_herder']='帐户中心';
        $this->view('manage', $data);
    }

    public function logout(User $user)
    {
        $user->logout();
        redirect('/login');
        exit;
    }

    //http://www.yuantuwang.com/wap/index/?openid=69504699757a452ff4fd03260609431&url=/wap/user&time=1470386943&sign=D8056A48C067364E38A251297087587B
    public function goApp(Request $request,App $app,AppUser $appUser)
    {
        $id=$request->get(2);
        $app=$app->findOrFail($id);

        $openid=$appUser->getOpenId($this->user_id,$id);
        if($openid==''){
            $openid=$appUser->create($this->user_id,$id);
        }
        $params=array(
            'openid'=>$openid,
            'url'=>'/wap/user',
            'time'=>time()
        );
        $sign=$this->getSign($params,$app->appsecret);
        $url=$app->domain."/wap/index/?openid={$params['openid']}&url={$params['url']}&time={$params['time']}&sign={$sign}";
        redirect($url);
    }

    private function getSign($data,$appsecret)
    {
        if(isset($data['sign'])){
            unset($data['sign']);
        }
        ksort($data);
        $jsonStr = json_encode($data);
        $str = strtoupper(md5($jsonStr.$appsecret));
        return $str;
    }
}