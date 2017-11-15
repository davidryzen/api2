<?php
/**
 * Created by PhpStorm.
 * User: daday   发布任务，执行任务等
 * Date: 2017/11/15
 * Time: 10:45
 */
class TaskController extends TopController{
    //根据zid显示所有的任务
    public function  showAllTasksByztAction(){
          if(isset($_GET['zt'])){
              $zt=$_GET['zt'];
          }else{
              $zt=0;
          }
        if(empty($_GET['zid'])||empty($_GET['page'])){
            $this->returnMessage(null,"page,zid不能为空",-249);
        }elseif(!is_numeric($_GET['zid'])||!is_numeric($_POST['page'])){
            $this->returnMessage(null,"page,zid必须是数字",-245);
        }
        //接受部门zid
        $zid=$_GET['zid'];
        $page=$_POST['page'];
        //使用post请求接口
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=renwu&function=showAllTasksByzt&zid=$zid&page=$page&zt=$zt";
        //使用post请求接口
        $result=$this->curlPost($url,$data);

        $result=json_decode($result,true);
//        var_dump($result);die;
        $length=count($result);//查询到的记录数和循环的次数
        for($i=0;$i<$length;++$i){
            $result[$i]["startime"]=date("Y-m-d H:i:s",$result[$i]["startime"]);
            $result[$i]["endtime"]=date("Y-m-d H:i:s",$result[$i]["endtime"]);
        }
//        var_dump($result);var_dump($data);die;
        if(!isset($result)){
            $this->returnMessage(null,"显示某个部门的任务失败",-578);
        }elseif(empty($result)){
            $this->returnMessage(null,"您查询到的页面没有数据",-577);
        }
        $this->returnMessage($result,"显示某个部门的任务成功",577);

    }
    //新增任务
    public function addTaskAction(){
        /*userid='+userid+'&id='+id+'&zid='+zid+'&leixing='+leixing+'&kaimen='+kaimen+'&fangjian='+fangjian+'&shijian='+shijian+'&context='+context+'*/
//        var_dump($_GET);die;
        if(empty($_GET['zid'])){
            $this->returnMessage(null,"id和zid不能为空"     ,-209);
        }elseif(!is_numeric($_GET['zid'])){
            $this->returnMessage(null,"参数id或zid必须是数字",-205);
        }
        //接受用户上传的文件
        $zid=$_GET['zid'];//部门zid
        $userid=isset($_POST['userid'])?$_POST["userid"]:"";
        $id=isset($_POST['id'])?$_POST["id"]:"";
        $leixing=isset($_POST['leixing'])?$_POST["leixing"]:"";//上传的文件
        $kaimen=isset($_POST['kaimen'])?$_POST["kaimen"]:"";
        $fangjian=isset($_POST['fangjian'])?$_POST["fangjian"]:"";
        $shijian=isset($_POST['shijian'])?$_POST["shijian"]:"";
        $context=isset($_POST['context'])?$_POST["context"]:"";

        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=renwu&function=addTask&id=$id&zid=$zid&userid=$userid&leixing=$leixing&kaimen=$kaimen&fangjian=$fangjian&shijian=$shijian&context=$context";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
        $result=json_decode($result,true);
        if(empty($result)){
            $this->returnMessage(null,"添加行程失败",-207);
        }
        $this->returnMessage($result,"添加行程成功",207);
    }
}