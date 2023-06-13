<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Customer as CustomerModel;

class Customer
{
    public function addcustomer(){
        $sname=input ("post.addName");
        $mobile=input("post.addPhone");
        $address=input("post.address");
        $customer=new CustomerModel();
        $customer->email=session('email');
        $customer->sname=$sname;
        $customer->mobile=$mobile;
        $customer->address=$address;
        $data=CustomerModel::where("email", session('email'))->select();
        if($data!=null){
            $customer->cdefault="0";
        }else{
            $customer->cdefault="1";
        }
        $customer->save();
        return $customer;
    }
    
    public function editCustomer(){
        $sname=input ("post.addName");
        $mobile=input("post.addPhone");
        $address=input("post.address");
        $custID=input("post.custID");
        
        $cust=CustomerModel::get($custID);
        if(!empty($sname)){
            $cust->sname=$sname;
        }
        if(!empty($mobile)){
            $cust->mobile=$mobile;
        }
        if(!empty($address)){
            $cust->address=$address;
        }
        try {
            $cust->save();
            return "success";
        }catch (Exception $e){
            return "";
        }
    }
    
    public function deleteCustomer(){
        $custID=input("post.custID");
        $customer=CustomerModel::get($custID);
        if($customer){
            $customer->delete();
            return "success";
        }
    }
    
    public function setDefault(){
        $oldcustID=input("post.originalID/d");
        $newcustID=input("post.custID/d");
        $oldCustomer=CustomerModel::get($oldcustID);
        $newCustomer=CustomerModel::get($newcustID);
        try {
            $oldCustomer->cdefault="0";
            $oldCustomer->save();
            $newCustomer->cdefault="1";
            $newCustomer->save();
            return "success";
        }catch (Exception $e){
            return "";
        }
    }
}

