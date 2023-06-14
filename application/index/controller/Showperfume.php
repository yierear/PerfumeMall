<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Perfume;
use app\index\model\Shoplist;

class Showperfume extends Controller
{
    public function perfumedetail(){
        $perfumeID = input('get.perfumeID');
        $perfume=Perfume::get($perfumeID);
        $this->assign('perfume',$perfume);
        $shoplists=Shoplist::where("perfumeID='".$perfumeID."' and pjstar is not null")->select();
        $this->assign('shoplists',$shoplists);
        return $this->fetch('perfumedetail');
    }
    
    public function clsperfume(){
       $search="";
               
        if(!empty($_GET['pvalue'])){
            $pname = $_GET['pname'];
            $pvalue = $_GET['pvalue'];
            
            if($_GET['pname']==='pclass'){
                $search.="pclass like '%$pvalue%'";
            }
            else if($_GET['pname']==='pclass1'){
                $search.="pclass1 like '%$pvalue%'";
            }
            else if($_GET['pname']==='myclass'){
                $search.="myclass like '%$pvalue%'";
            }
        }            
        
        if(!empty($_GET['pvalue1'])){
            $pname = $_GET['pname'];
            $pvalue1 = $_GET['pvalue1'];
            $pvalue2 = $_GET['pvalue2'];
            
            if($_GET['pname']==='yourprice'){
                $search="yourprice between $pvalue1 and $pvalue2";
            } else if($_GET['pname']==='SelledNum') {
                $search="SelledNum between $pvalue1 and $pvalue2";
            }
        }
        
        
        $data = Db::table('perfume')->where($search)->order('SelledNum','desc')->paginate(15);
        $this->assign('result',$data);
        $page=$data->render();
        $this->assign('page',$page);
        return $this->fetch();
    }
}

