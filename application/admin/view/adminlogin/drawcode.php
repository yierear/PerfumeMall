<?php
// 启动 session
session_start();
// 导入验证码生成的文件
include 'code.php';
// 生成验证码，并返回验证码的字符串，保存在session中用于判断输入的验证码是否正确
$code=createcode();
$_SESSION['code']=$code;
?>