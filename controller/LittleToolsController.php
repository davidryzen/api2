<?php
/**
 * Created by PhpStorm.
 * User: daday
 * Date: 2017/11/8
 * Time: 17:08
 */
class LittleToolsController extends TopController {

    //查询某个商户的所有的小工具
    public function showAllToolsAction(){
        if(!isset($_POST['id'])||empty($_POST['id'])){
            $this->returnMessage(null,"id不能为空",-209);
        }elseif(!is_numeric($_POST['id'])){
            $this->returnMessage(null,"参数id必须是数字",-205);
        }
        //接受商户id
        $id=$_POST['id'];
        //使用post请求接口
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=tool&function=showAllTools&id=$id";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
//        var_dump($result);var_dump($data);die;
        $result=json_decode($result,true);
        if(empty($result)){
            $this->returnMessage(null,"显示此商户所有的小工具失败",-217);
        }
        $this->returnMessage($result,"显示此商户所有的小工具成功",217);


    }

    //获取商户的信息
    public function getMerchantInfoAction(){
        //使用post请求接口
        //请求的url地址
        $url="https://www.mijiweb.com//qcloud/pu/chaxun/";
        $token=$this->getToken("10003","H1dFEDOrd3erkej-erfF30DRFDDASDW3");
        //post请求携带的参数
        $data="token=$token&action=list-small&zt=2";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
//        var_dump($result);var_dump($data);die;
        $result=json_decode($result,true);
        if(empty($result)){
            $this->returnMessage(null,"获取所有商户的信息失败",-216);
        }
        $this->returnMessage($result,"获取所有商户的信息成功",216);
    }
    //某个商户使用表单小工具添加一条表单数据
    public function addFromDataAction(){
        if(empty($_POST['id'])||empty($_POST['zid'])){
            $this->returnMessage(null,"id和zid不能为空"     ,-209);
        }elseif(!is_numeric($_POST['id'])||!is_numeric($_POST['zid'])){
            $this->returnMessage(null,"参数id或zid必须是数字",-205);
        }
        //接受用户上传的文件
        $id=isset($_POST['id'])?$_POST["id"]:"";//用户id
        $zid=isset($_POST['zid'])?$_POST["zid"]:"";//部门zid
        $pic=isset($_POST['pic'])?$_POST["pic"]:"";//上传的文件
        $titlepic=isset($_POST['img'])?$_POST["img"]:"";
//               var_dump($titlepic);die;
        $title=isset($_POST['title'])?$_POST["title"]:"";
        $keyboard=isset($_POST['keyboard'])?$_POST["keyboard"]:"";
        $zt=isset($_POST['leixing'])?$_POST["leixing"]:"";
        $jiage=isset($_POST['jiage'])?$_POST["jiage"]:"";
        $px=isset($_POST['px'])?$_POST["px"]:"";
        $miaoshu=isset($_POST['miaoshu'])?$_POST["miaoshu"]:"";
//        $list=array('id'=>$id,'zid'=>$zid,'jiage'=>$jiage,'title'=>$title,"keyboard"=>$keyboard,"titlepic"=>$titlepic,'pic'=>$pic,'miaoshu'=>$miaoshu,"px"=>$px,"zt"=>$zt,"time"=>time());


        //得到接口类实例对象
        /*$APIModelObj=new APIModel();
        //调用得到所有信息的方法
       $result= $APIModelObj-> addOne("xingcheng",$list);*/
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=xingcheng&function=addOne&id=$id&zid=$zid&jiage=$jiage&title=$title&keyboard=$keyboard&titlepic=$titlepic&pic=$pic&miaoshu=$miaoshu&px=$px&zt=$zt";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
        $result=json_decode($result,true);
        if(empty($result)){
            $this->returnMessage(null,"添加行程失败",-207);
        }
        $this->returnMessage($result,"添加行程成功",207);

    }
}