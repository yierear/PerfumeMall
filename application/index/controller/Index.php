<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{ 
    public function index()
    {
        $pclass=Db::table('perfume')->distinct('true')->field('pclass')->select();
        $this->assign('pclasses',$pclass);
        
        $pname=input('post.pname');
        $fcls=input('post.fcls');
        $minprice=input('post.minprice');
        $maxprice=input('post.maxprice');
        
        if (empty($maxprice)){
            $maxprice=PHP_INT_MAX;
        }        
        if (empty($minprice)){
            $minprice=0;
        }
                
        $searchstr="yourprice between $minprice and $maxprice";
        if (!empty($pname)){
            $searchstr.=" and pname like '%$pname%'";
        }
        
        if($_POST){
            $fcls1 = $_POST["pclass"];
            $searchstr.=" and pclass like '%$fcls1%'";
        }
                                
        $data=Db::table('perfume')->where($searchstr)->order('SelledNum desc')->select();
        $this->assign('perfumes',$data);
        return $this->fetch();
        
    }
    
    
    public function showperfume(){      
        $searchstr="";
               
        if($_GET){
            $pname = $_GET['pname'];
            $pvalue = $_GET['pvalue'];
            if($pname==='pclass'){
                $searchstr="pclass like '%$pvalue%'";
            }else if($pname==='pclass'){
                
            }
        }            
        
        $data = Db::table('perfume')->where($searchstr)->order('SelledNum','desc')->paginate(5);
        $this->assign('result',$data);
        $page=$data->render();
        $this->assign('page',$page);
        return $this->fetch();
    }
}