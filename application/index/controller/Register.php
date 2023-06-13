<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Register extends Controller{
    
    public function register(){
        return view();
    }
    
    public function doRegister(){
        if (empty(input('post.email'))){
            $this->error('email不能为空');
        }
        if (empty(input('post.passw1'))){
            $this->error('password不能为空');
        }
        if (empty(input('post.passw2'))){
            $this->error('重复passwword不能为空');
        }
        if (input('post.passw1')!=input('post.passw2')){
            $this->error('两次密码输入不一致！');
        }
        
        $data = db('tb_member')->where('email', input('post.email'))->find();
        if (!empty($data)){
            $this->error('该用户已被注册！');
        }else {
            $result = Db::execute("insert into tb_member(email,password,jifen,ye) values('" . input('post.email') . "','" .md5(input('post.passw1')) . "',0,0)");
            dump($result);
            $this->redirect(url('index/index'));
        }
        
    }
}
?>
