<?php
namespace app\index\controller;

use think\Controller;
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
        
    }
}

