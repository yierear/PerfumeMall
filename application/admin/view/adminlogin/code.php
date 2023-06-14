<?php
function create($count = 4)
{
    // 设置可显示字符的集合
    $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ123456789'; // 随机因子
    // 用于保存生成的验证码
    $code = ''; 
    // 生成随机的4个字符，当作验证码
    $len = strlen($charset) - 1;
    for ($i = 0; $i < $count; ++$i) {
        $code .= $charset[mt_rand(0, $len)];
    }
    return $code;
}
// 开始画验证码图片
function show($code, $x = 100, $y = 40)
{
    // 创建图像资源
    $im = imagecreate($x, $y);
    // 随机生成背景颜色
    imagecolorallocate($im, mt_rand(50, 200), mt_rand(0, 155), mt_rand(0, 155));
    // 设置验证码文本的颜色和字体
    $fontcolor = imagecolorallocate($im, 255, 255, 255);
    $fontfile = './static/fonts/COURE.FON';
    // 在图像中绘制验证码
    for ($i = 0, $len = strlen($code); $i < $len; ++$i) {
        imagettftext($im, // 图像资源
            20, // 字符尺寸
            mt_rand(0, 20) - mt_rand(0, 25), // 随机设置字符倾斜角度
            10 + $i * 22, mt_rand(20, 40), // 随机设置字符坐标
            $fontcolor, // 字符颜色
            realpath($fontfile), // 字符样式
            $code[$i]// 字符内容
        );
    }
    // 添加8个干扰线
    for ($i = 0; $i < 8; ++$i) {
        // 随机生成干扰线颜色
        $linecolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        // 随机生成干扰线
        imageline($im, mt_rand(0, $x), 0, mt_rand(0, $x), $y, $linecolor);
    }
    // 添加250个噪点
    for ($i = 0; $i < 250; ++$i) {
        // 随机生成噪点位置
        imagesetpixel($im, mt_rand(0, $x), mt_rand(0, $y), $fontcolor);
    }
    // 设置消息头
    header('Content-Type: image/png');
    // 输出图片
    ob_clean();
    imagepng($im);
    // 释放图像资源
    imagedestroy($im);
}
function createcode()
{
    $code = create();
    $_SESSION['code'] = $code;
    show($code);
    return $code;
}