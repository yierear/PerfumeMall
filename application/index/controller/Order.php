<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Customer;
use app\index\model\Member;
use app\index\model\Myorder;
use app\index\model\Shoplist;
use app\index\model\Perfume;
use app\index\model\Cart;
use app\index\model\Showorder;
use app\index\model\Showshoplist;
use think\Request;

class Order extends Controller
{
    public function order(){
        //1.判断是否登录
        if(empty(session('email'))){
            $this->error('请先登录!','login/login');
        }
        
        //2.查找登录用户所有收货人地址
        $data = Customer::where('email',session('email'))->select();
        $this->assign('Customers',$data);
        
        //3.查找登录用户信息
        $mem =Member::get(session('email'));
        $this->assign('member',$mem);
        
        //4.查找购物车
        $cartIDs=input('post.cartID/a');
        session('cartIDs',$cartIDs);
        $vcart=Db::table('vcart')->where('cartID','in',$cartIDs)->select();
        $this->assign('vcart',$vcart);
        
        return $this->fetch();
    }
    
    public function addOrder(){
        // 事务处理开始
        Db::transaction(function () {
            // (1)添加订单信息到myorder表
            // (1.1)新建myorder对象，并将获取的表单值绑定到对应的属性。
            $order = new Myorder();
            $order->email = session('email');
            $order->custID = input('post.custID');
            $order->shifu = input('post.total');
            $order->inputtime = date("Y-m-d H:i:s");
            $order->peisongday = input('post.date');
            $order->peisongtime = input('post.time');
            $order->message = input('post.message');
            $order->buy_name = input('post.buy_name');
            $order->pay_with = input('post.pay_with');
            $order->status = '未付款';
            $order->cltime = $order->inputtime;
            // (1.2)添加订单
            $order->save();
            // (1.3)获取新添加的订单编号
            $orderID = $order->orderID;
             echo $orderID;
            // （2）将购买的商品信息及数量添加到shoplist表
            // （2.1）根据选择的商品编号查看cart表
            $cartIDs = session('cartIDs');
            $carts = Cart::where('cartID', 'in', $cartIDs)->select();
            // (2.2)遍历carts
            foreach ($carts as $cart) {
                // (2.3)新建shoplist表对象$shoplist
                $shoplist = new Shoplist();
                // (2.4)绑定orderID、email、perfumeID、num属性属性
                $shoplist->orderID = $orderID;
                $shoplist->perfumeID = $cart->perfumeID;
                $shoplist->email = session('email');
                $shoplist->num = $cart->num;
                // (2.5) 添加到shoplist表
                $shoplist->save();
                // (3)根据购物车中的perfumeID查找perfume表，将其销售数量+num
                $perfume = perfume::get($cart->perfumeID);
                $perfume->SelledNum = $perfume->SelledNum + $cart->num;
                $perfume->save();
                // (4) 在购物车中删除对于的商品
                $cart->delete();
            }
        });
            return "success";
    }
    
    public function showorder(){
        if(empty(session('email'))){
            $this->error('请先登录','login/login');
        }
        
        $orders = Showorder::where('email', session('email'))->order('orderID desc')->paginate(3);
        $page=$orders->render();
        $this->assign('showorder',$orders);
        $this->assign('page',$page);
        $orderlists = array();
        foreach ($orders as $order) {
            $shoplistitems = array();
            foreach ($order->showshoplist as $shoplist) {
                if($order->orderID ==  $shoplist->orderID){
                    array_push($shoplistitems, $shoplist);
                }
            }
            array_push($orderlists, $shoplistitems);
        }
        $this->assign('orderlists',$orderlists);
        return $this->fetch();
    }
    
    public function pay(){
        $orderID = input('get.id');
        $order = Myorder::get($orderID);
        $order->status='已付款';
        $order->cltime = date("Y-m-d H:i:s");
        $order->save();
        $this->redirect('order/showorder');
    }
    
    public function delete()
    {
        // 事务处理开始
        Db::transaction(function () {
            $orderID = input('post.orderID');
            $shoplists = Shoplist::where('orderID', $orderID)->select();
            foreach ($shoplists as $shoplist) {
                $perfumeID = $shoplist->perfumeID;
                $num = $shoplist->num;
                $perfume = perfume::get($perfumeID);
                $perfume->SelledNum = $perfume->SelledNum - $num;
                $perfume->save();
                $shoplist->delete();
            }
            $order = Myorder::get($orderID);
            $order->delete();
        });
            return "success";
    }
    
    public function orderUpdate(){
        $orderID=input('get.orderID/d');
        $order=Myorder::get($orderID);
        $order->status='未评价';
        $order->cltime=date('Y-m-d H:i:s');
        $order->save();
        $this->redirect('order/showorder');
    }
    
    public function evaluate(){
        $orderID = input('get.orderID/d');
        $data = Db::table('showshoplist')->where('orderID', $orderID)->select();
        $this->assign('results', $data);
        return $this->fetch();
    }
    
    public function doEvaluate(Request $request){
        $orderID = input('post.orderID/d');
        $datas = Shoplist::where('orderID', $orderID)->select();
        foreach ($datas as $shoplist) {
            $SLID=$shoplist->SLID;
            $shoplist->email = session('email');
            $shoplist->pjstar = $request->param('pjstar'.$SLID);
            $shoplist->pjcontent = $request->param('pjcontent'.$SLID);
            $shoplist->pjip = $request->ip();
            $shoplist->pjtime = date('Y-m-d H:i:s');
            $shoplist->save();
        }
        $order = Myorder::get($shoplist->orderID);
        $order->status = '已评价';
        $order->cltime = date('Y-m-d H:i:s');
        $order->save();
        $this->redirect('order/showorder');
    }
}

