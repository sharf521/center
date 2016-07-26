<?php
namespace App\Controller;

use App\Model\User;
use System\Lib\Request;

class RegisterController extends Controller
{
    public function index(Request $request, User $user)
    {
        if ($_POST) {
            $request->checkToken();
            $data = array(
                'username' => $request->post('username'),
                'password' => $request->post('password')
            );
            $result = $user->login($data);
            if ($result === true) {
                redirect('member/');
            } else {
                $error = $result;
            }
            redirect()->back()->with('error', $error);
        } else {
            $this->view('register');
        }
    }
}