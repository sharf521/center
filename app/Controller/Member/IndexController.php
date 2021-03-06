<?php
namespace App\Controller\Member;

use App\Helper;
use App\Model\App;
use App\Model\AppUser;
use App\Model\CarRent;
use App\Model\Rebate;
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

        //$convert_rate=Helper::getSystemParam('convert_rate');
        $rebate=(new Rebate())->select("sum(money) as money,sum(money_rebate) as money_rebate")->where("user_id=?")->bindValues($this->user_id)->first();
        $data['expectedIntegralReward']=(float)$rebate->money;
        $data['alreadyIntegralReward']=(float)$rebate->money_rebate;


        /*$data['carRents']=(new CarRent())->where("user_id=? and status=1")->bindValues($this->user_id)->orderBy('id desc')->get();*/

        $rebateList=(new Rebate())->where("user_id=?")->bindValues($this->user_id)->orderBy('id desc')->limit('0,15')->get();
        $data['rebateList']=$rebateList;
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
        $app_id=$request->get(2);
        $isGoWap=$request->get(3)=='wap'?true:false;
        $app=$app->findOrFail($app_id);

        $app_field=$app->subsite_field;
        $app_field_wap=$app->subsite_field.'_wap';
        $app_url=$this->site->$app_field;
        $app_url_wap=$this->site->$app_field_wap;
        
        $openid=$appUser->getOpenId($this->user_id,$app_id);
        if($openid==''){
            $openid=$appUser->create($this->user_id,$app_id);
        }
        $params=array(
            'openid'=>$openid,
            'time'=>time()
        );
        if($app_id==5){
            //商城
            if($isGoWap){
                $params['url']='/wap/user';
                $url=$app_url."/wap/index";
            }else{
                $params['url']='/user';
                $url=$app_url."/jump";
            }
        }elseif($app_id==8){  //一元云购
            if($isGoWap){
                $params['url']='/wap';
                $url=$app_url."/auth/result";
            }else{
                $params['url']='/user';
                $url=$app_url."/auth/result";
            }
        }elseif($app_id==9){  //pos代理
            if($isGoWap){
                $params['url']='/';
                $url=$app_url_wap."/jump";
            }else{
                $params['url']='/user';
                $url=$app_url."/jump";
            }
        }elseif ($app_id==10){
            if($isGoWap){
                $params['url']='/car';
                $url=$app_url_wap."/user/auth";
            }else{
                $params['url']='/member';
                $url=$app_url."/user/auth";
            }
        }
        $sign=$this->getSign($params,$app->appsecret);
        $url=$url."/?openid={$params['openid']}&url={$params['url']}&time={$params['time']}&sign={$sign}";
        //echo $url;
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

    public function app()
    {
        $site_id=$this->site->id;
        $data['site_id']=$site_id;

        $file_path = ROOT . "/public/data/app/";
        if (!is_dir($file_path)) {
            mkdir($file_path, 0777, true);
        }

        $iphone='http://'.$_SERVER['HTTP_HOST']."/data/app/iphone_{$site_id}.ipa";
        $android='http://'.$_SERVER['HTTP_HOST']."/data/app/android_{$site_id}.apk";
        \PHPQRCode\QRcode::png($android, $file_path."android_{$site_id}.png", 'L', 4, 2);
        \PHPQRCode\QRcode::png($iphone, $file_path."iphone_{$site_id}.png", 'L', 4, 2);

        $data['img_android']="/data/app/android_{$site_id}.png";
        $data['img_iphone']="/data/app/iphone_{$site_id}.png";
        $this->view('article',$data);
    }
}