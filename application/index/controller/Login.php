<?php
namespace app\index\controller;

use think\Controller;

class Login extends Controller
{
    public function login(){
        return view();
    }
    
    
    public function dologin(){
        if (empty(input('post.email'))){
            $this->error('email不能为空');
        }
        
        $rs=db('tb_member')->where('email',input('post.email'))->find();
        if (empty($rs)){
            $this->error('用户名错误');
        }
        
        if ($rs['password']!=md5(input('post.passw'))){
            $this->error('密码错误');
        }
        if (empty(input('post.code'))){
            $this->error('请输入验证码');
        }
        
        $login=new Login();
        if (captcha_check('post.code')) {
            $this->error('验证码错误');
        }
        
        session('email',$rs['email']);
        $this->redirect(url('index/index'));
    }
    
    public function logOut(){
        session('email',null);
        $this->redirect(url('index/index'));
    }
}

