<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Cart extends Controller
{
    public function showcart(){
        if(empty(session('email'))){
            $this->error('请先登录！','login/index');
        }
//         $data=Db::table('vcart'->where('email',session('email'))->select());
        $data=db('vcart')->where('email',session('email'))->select();
//         $data=Db::execute("select cartID,cart.perfumeID,pictures,email,num,pname,price,yourprice
//                            from cart,perfume
//                            where perfume.perfumeID=cart.perfumeID and email='".session('email')."'");
        $this->assign('result',$data);
        return $this->fetch();
    }
    
    public function addCart(){
        $perfumeID = input('get.perfumeID');
        if(empty(session('email'))){
            $this->error('请先登录!','index/login/login');
        }if(empty($perfumeID)){
            $this->error('请选择商品!','index/index');
        }
        
        $data = db('cart')->where('email',session('email'))->where('perfumeID',$perfumeID)->find();
        if(empty($data)){
            $data=[
                'email'=>session('email'),
                'perfumeID'=>$perfumeID,
                'num'=>1
            ];
            $result=db('cart')->insert($data);
//             dump($result);
        }else{
            $result=db('cart')->where('email',session('email'))
            ->where('perfumeID',$perfumeID)
            ->setInc('num');
            dump($result);
        }
        $this->redirect ('cart/showcart');
    }
    
    public function clearcart(){
        $data=db('cart')->where('email',session('email'))->delete();
        $this->redirect('index/index');
    }
    
    public function deletecart(){
        $cartID=input('get.cartID');
        db('cart')->delete($cartID);
    }
    
    public function updatecart(){
        $cartID=input('get.cartID');
        $num=input('get.num');
        db('cart')->where('cartID',$cartID)->setField('num',$num);
    }
    
}

