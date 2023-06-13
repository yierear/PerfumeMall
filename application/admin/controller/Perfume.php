<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\index\model\Perfume as perfumeModel;

class Perfume extends Controller
{
    public function index(){
        if(empty(session('username')))
        {
            $this->error('请先登录','adminlogin/index');
        }
        $perfumes=perfumeModel::all();
        $this->assign('perfumes',$perfumes);
        return $this->fetch('perfumeindex');
    }
    public function perfumeadd(){
        return $this->fetch();
    }
    
    public function addperfume(Request $request){
        $perfumeID=$request->param('perfumeID');
        if(empty($perfumeID)){
            $this->error('请填写香水编号');
        }
        $perfume1=perfumeModel::get($perfumeID);
        if(!empty($perfume1)){
            $this->error('您填写香水编号已存在！');
        }
        $perfume=new perfumeModel();
        $perfume->perfumeID=$perfumeID;
        $perfume->pname=$request->param('pname');
        $perfume->myclass=$request->param('myclass');
        $perfume->pclass=$request->param('pclass');
        $perfume->pclass1=$request->param('pclass1');
        $perfume->qiandiao=$request->param('qiandiao');
        $perfume->zhongdiao=$request->param('zhongdiao');
        $perfume->houdiao=$request->param('houdiao');
        $perfume->shuoming=$request->param('shuoming');
        $perfume->price=$request->param('price');
        $perfume->yourprice=$request->param('yourprice');
        $perfume->tejia=$request->param('tejia');
        $perfume->SelledNum=0;
    
        $pictures=$request->file('pictures');
        if	(empty($pictures)){
            $this->error('请选择上传文件');
        }
        $info=$pictures->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
        $perfume->pictures=$info->getSaveName();
    
        $picturem=$request->file('picturem');
        if	(empty($picturem)){
            $this->error('请选择上传文件');
        }
        $info=$picturem->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
        $perfume->picturem=$info->getSaveName();
        $pictureb=$request->file('pictureb');
        if(empty($pictureb)){
            $this->error('请选择上传文件');
        }$info=$pictureb->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
        $perfume->pictureb=$info->getSaveName();
         
        $pictured=$request->file('pictured');
        if(!empty($pictured)){
            $info=$pictured->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
            $perfume->pictured=$info->getSaveName();
        }
        $pingpaipicture=$request->file('pingpaipicture');
        if(!empty($pingpaipicture)){
            $info=$pingpaipicture->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
            $perfume->pingpaipicture=$info->getSaveName();
        }
        $ylpicture=$request->file('ylpicture');
        if(!empty($ylpicture)){
            $info=$ylpicture->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
            $perfume->ylpicture=$info->getSaveName();
        }
        $perfume->save();
        $this->success('添加成功！','perfume/index');
    }
    
    public function perfumeDelete(){
        $perfume=perfumeModel::get(input('get.perfumeID'));
        $perfume->delete();
        $this->redirect('perfume/index');
    }
    
    public function perfumeupdate(){
        $perfume=perfumeModel::get(input('get.perfumeID'));
        $this->assign('perfume',$perfume);
        return $this->fetch();
    }
    
    public function updateperfume(Request $request){
        $perfume=perfumeModel::get(input('post.perfumeID'));
        $perfume->pname=$request->param('pname');
        $perfume->myclass=$request->param('myclass');
        $perfume->pclass=$request->param('pclass');
        $perfume->pclass1=$request->param('pclass1');
        $perfume->qiandiao=$request->param('qiandiao');
        $perfume->zhongdiao=$request->param('zhongdiao');
        $perfume->houdiao=$request->param('houdiao');
        $perfume->shuoming=$request->param('shuoming');
        $perfume->price=$request->param('price');
        $perfume->yourprice=$request->param('yourprice');
        $perfume->tejia=$request->param('tejia');
         
        $pictures=$request->file('pictures');
        if(!empty($pictures)){
            $info=$pictures->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
            $perfume->pictures=$info->getSaveName();
        }
    
        $picturem=$request->file('picturem');
        if(!empty($picturem)){
            $info=$picturem->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
            $perfume->picturem=$info->getSaveName();
        }
        $pictureb=$request->file('pictureb');
        if(!empty($pictureb)){
            $info=$pictureb->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
            $perfume->pictureb=$info->getSaveName();
        }
    
        $pictured=$request->file('pictured');
        if(!empty($pictured)){
            $info=$pictured->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
            $perfume->pictured=$info->getSaveName();
        }
    
        $pingpaipicture=$request->file('pingpaipicture');
        if(!empty($pingpaipicture)){
            $info=$pingpaipicture->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
            $perfume->pingpaipicture=$info->getSaveName();
        }
        $ylpicture=$request->file('ylpicture');
        if(!empty($ylpicture)){
            $info=$ylpicture->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
            $perfume->ylpicture=$info->getSaveName();
        }
        $perfume->save();
        $this->success('修改成功！','perfume/index');
    }
}

