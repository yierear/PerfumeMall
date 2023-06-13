<?php
namespace app\admin\controller;

use think\Controller;

class Captcha extends Controller
{
    public  function login(){
        return $this->fetch();
    }
}

