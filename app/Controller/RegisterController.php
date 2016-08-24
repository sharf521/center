<?php
namespace App\Controller;

use App\Model\User;
use System\Lib\Request;

class RegisterController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(Request $request, User $user)
    {
        if ($_POST) {
            $request->checkToken();
            $data = array(
                'username' => $request->post('username'),
                'email'=>$request->post('email'),
                'password' => $request->post('password'),
                'sure_password'=>$request->post('sure_password'),
                'invite_user'=>$request->post('invite_user')
            );
            $result = $user->register($data);
            if ($result === true) {
                redirect('member/');
            } else {
                $error = $result;
            }
            redirect()->back()->with('error', $error);
        } else {
            $data['title_herder']='新用户注册';
            $this->view('register',$data);
        }
    }
    
    public function checkUserName(Request $request,User $user)
    {
        $return=$user->checkUserName($request->get('username'));
        if($return===true){
            echo 'true';
        }else{
            echo 'false';
        }
    }
    
    public function checkInviteUser(Request $request,User $user)
    {
        $invite_arr=$user->checkInvetUser($request->get('invite_user'),$request->get('app_id'));
        if($invite_arr['status']===true){
            echo 'true';
        }else{
            echo 'false';
        }
    }
    
    public function checkEmail(Request $request,User $user)
    {
        $return=$user->checkEmail($request->get('email'));
        if($return===true){
            echo 'true';
        }else{
            echo 'false';
        }
    }
    

}