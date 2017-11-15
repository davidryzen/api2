<?php
/**
 * Created by PhpStorm.
 * User: daday
 * Date: 2017/11/15
 * Time: 14:02
 * 评论的搜集
 */
  class AppraisalController extends TopController {
      //改变审核状态
      public function  changeStateAction(){
          //获取plid评价id
          if(!isset($_GET['plid'])){
              $this->returnMessage(nulll,"请传递plid评价id",607);
          }
          $plid=$_GET['plid'];
          //使用post请求接口
          //请求的地址
          $url="http://www.you.com/API/schedule2/model/start.php";
          $data="table=pinlun&function=changeState&plid=$plid";
          //使用post请求接口
          $result=$this->curlPost($url,$data);

          $result=json_decode($result,true);
//
//        var_dump($result);var_dump($data);die;
          if(!isset($result)){
              $this->returnMessage(null,"改变审核状态失败",-678);
          }
          $this->returnMessage($result,"改变审核状态成功",677);

      }
      //回复评价
      public function replyCommentAction(){
//          /hezuo/cz.php?action=huifu&id='+id+'&zid='+zid+'&plid='+plid+'&hftext='+hftext+'&jsoncallback=?
          //搜集数据
          $id=$_GET["id"];
          $zid=$_GET["zid"];
          $plid=$_GET["plid"];
          $hftext=$_GET["hftext"];
          //使用post请求接口
          //请求的地址
          $url="http://www.you.com/API/schedule2/model/start.php";
          $data="table=pinlun&function=replyComment&id=$id&zid=$zid&plid=$plid&hftext=$hftext";
          //使用post请求接口
          $result=$this->curlPost($url,$data);

          $result=json_decode($result,true);
//
//        var_dump($result);var_dump($data);die;
          if(!isset($result)){
              $this->returnMessage(null,"回复评价失败",-628);
          }
          $this->returnMessage($result,"回复评价成功",627);

      }
      //根据fs和部门zid,回复不同的内容
      public function replyByfsAction(){
          if(empty($_GET['zid'])||empty($_GET['page'])||empty($_GET['fs'])){
              $this->returnMessage(null,"page,zid,fs不能为空",-249);
          }elseif(!is_numeric($_GET['zid'])||!is_numeric($_POST['page'])){
              $this->returnMessage(null,"page,zid,fs必须是数字",-245);
          }
          //接受部门zid
          $fs=$_GET['fs'];
          $zid=$_GET['zid'];
          $page=$_GET['page'];
          //使用post请求接口
          //请求的地址
          $url="http://www.you.com/API/schedule2/model/start.php";
          $data="table=renwu&function=replyByfs&fs=$fs&page=$page&zid=$zid";
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
  }