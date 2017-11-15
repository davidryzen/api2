<?php
/**
 * Created by PhpStorm.
 * User: daday
 * Date: 2017/11/8
 * Time: 17:08
 */
class LittleToolsController extends TopController {

    //显示用户用小工具提交的数据
    public function showFormDataFromUserAction(){
        if(empty($_POST['gid'])||empty($_POST['page'])){
            $this->returnMessage(null,"page,gid不能为空",-249);
        }elseif(!is_numeric($_POST['gid'])||!is_numeric($_POST['page'])){
            $this->returnMessage(null,"page,gid必须是数字",-245);
        }
        $gid=$_POST['gid'];
        $page=$_POST['page'];
        //使用post请求接口
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=tool_biaodan&function=showFormDataFromUser&gid=$gid&page=$page";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
//        var_dump($result);var_dump($url);var_dump($data);die;
        $result=json_decode($result,true);
        if(!isset($result)){
            $this->returnMessage(null,"显示商户使用此from表单提交的数据失败",-488);
        }elseif(empty($result)){
            $this->returnMessage(null,"当前页数没有数据啦",-457);
        }
        $length=count($result);//查询到的记录数和循环的次数
        for($i=0;$i<$length;++$i){
            $result[$i]["time"]=date("Y-m-d H:i:s",$result[$i]["time"]);
        }
        $this->returnMessage($result,"显示商户使用此from表单提交的数据成功",457);
    }

   /* //查询某个商户的所有的小工具
    public function showAllToolsAction(){

        //使用post请求接口
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=tool&function=showAllTools";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
//        var_dump($result);var_dump($data);die;
        $result=json_decode($result,true);
        if(empty($result)){
            $this->returnMessage(null,"显示此商户所有的小工具失败",-217);
        }
        $this->returnMessage($result,"显示此商户所有的小工具成功",217);


    }*/

    //收集某一个商户id，提交的表单数据结构
    public function addFormFrameAction(){
        //参数
        $frameSelect=array();
        $canshu=array();
//        var_dump($_POST);die;
       //得到商户的id，和小工具的tid
        if(empty($_POST['id'])||empty($_POST['tid'])){
            $this->returnMessage(null,"id和tid不能为空"     ,-219);
        }elseif(!is_numeric($_POST['id'])||!is_numeric($_POST['tid'])){
            $this->returnMessage(null,"参数id或tid必须是数字",-215);
        }
       //搜集用户的信息
        $id=$_POST['id'];
        $tid=$_POST['tid'];
        $title=$_POST['title'];
        $startime=strtotime(($_POST['startime']));
//        var_dump($startime);var_dump(date("Y-m-d H:i:s",$startime)); die;
        $endtime=strtotime($_POST['endtime']);
        $zt=$_POST['zt'];//是否上线
        $miaoshu=$_POST["miaoshu"];
        $jiangpin_1=$_POST["jiangpin_1"];
        $jiangpin_2=$_POST["jiangpin_2"];
        $jiangpin_3=$_POST["jiangpin_3"];
        $length=count($jiangpin_1);//数组的长度，循环的次数
        for($i=0;$i<$length;++$i){
            $frameSelect[$i]=array("name"=>urlencode($jiangpin_1[$i]),"zt"=>$zt,"zhi"=>urlencode($jiangpin_2[$i]));
        }
        $canshu=urldecode(json_encode(array("info"=>$frameSelect)));
//        var_dump($canshu);
        //使用post请求接口
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=tool_log&function=addFormFrame&id=$id&tid=$tid&title=$title&starttime=$startime&endtime=$endtime&miaoshu=$miaoshu&canshu=$canshu&zt=$zt";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
//       var_dump($result);var_dump($data);die;
        $result=json_decode($result,true);
        if(empty($result)){
            $this->returnMessage(null,"添加此商户from表单失败",-227);
        }
        $this->returnMessage($result,"添加此商户from表单成功",227);

    }
    //根据商户id显示某个商户的使用中小工具的记录
    public function showUseingToolsByIdAction(){
        if(empty($_POST['id'])||empty($_POST['page'])){
            $this->returnMessage(null,"page,id不能为空",-249);
        }elseif(!is_numeric($_POST['id'])||!is_numeric($_POST['page'])){
            $this->returnMessage(null,"page,id必须是数字",-245);
        }
        $id=$_POST['id'];
        $page=$_POST['page'];
        //使用post请求接口
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=tool_log&function=showUseingToolsById&id=$id&page=$page";
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
            $this->returnMessage(null,"显示某个商户的使用中小工具的记录失败",-288);
        }elseif(empty($result)){
            $this->returnMessage(null,"您查询到的页面没有数据",-287);
        }
        $this->returnMessage($result,"显示某个商户的使用中小工具的记录成功",287);
    }
    //根据商户id显示某个商户的使用过期小工具的记录
    public function showExpiredToolsByIdAction(){
        if(empty($_POST['id'])||empty($_POST['page'])){
            $this->returnMessage(null,"page,id不能为空",-249);
        }elseif(!is_numeric($_POST['id'])||!is_numeric($_POST['page'])){
            $this->returnMessage(null,"page,id必须是数字",-245);
        }
        $id=$_POST['id'];
        $page=$_POST['page'];
        //使用post请求接口
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=tool_log&function=showExpiredToolsById&id=$id&page=$page";
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
            $this->returnMessage(null,"显示某个商户的过期小工具的记录失败",-298);
        }elseif(empty($result)){
            $this->returnMessage(null,"您查询到的页面没有数据",-297);
        }
        $this->returnMessage($result,"显示某个商户的过期小工具的记录成功",297);
    }
    //根据商户id显示某个商户的使用全部小工具的记录
    public function showAllToolsByIdAction(){
        if(empty($_POST['id'])||empty($_POST['page'])){
            $this->returnMessage(null,"page,id不能为空",-249);
        }elseif(!is_numeric($_POST['id'])||!is_numeric($_POST['page'])){
            $this->returnMessage(null,"page,id必须是数字",-245);
        }
        $id=$_POST['id'];
        $page=$_POST['page'];
        //使用post请求接口
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=tool_log&function=showAllToolsById&id=$id&page=$page";
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
            $this->returnMessage(null,"显示某个商户的使用小工具的记录失败",-278);
        }elseif(empty($result)){
            $this->returnMessage(null,"您查询到的页面没有数据",-277);
        }
        $this->returnMessage($result,"显示某个商户的使用小工具的记录成功",277);
    }
    //根据gid显示一个商户提交的表单数据结构
    public function showFormFrameAction(){
        if(empty($_POST['gid'])){
            $this->returnMessage(null,"gid不能为空不能为空",-249);
        }elseif(!is_numeric($_POST['gid'])){
            $this->returnMessage(null,"gid必须是数字",-245);
        }
        $gid=$_POST['gid'];
        //使用post请求接口
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=tool_log&function=showFormFrame&gid=$gid";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
//       var_dump($result);var_dump($data);die;
        $result=json_decode($result,true);
        if(!isset($result)){
            $this->returnMessage(null,"显示此商户from表单失败",-258);
        }elseif(empty($result)){
            $this->returnMessage(null,"当前页数没有数据啦",-257);
        }
        $length=count($result);//查询到的记录数和循环的次数
        for($i=0;$i<$length;++$i){
            $result[$i]["startime"]=date("Y-m-d H:i:s",$result[$i]["startime"]);
            $result[$i]["endtime"]=date("Y-m-d H:i:s",$result[$i]["endtime"]);
        }
        $this->returnMessage($result,"显示此商户from表单成功",257);

    }
    //更新某一个商户id提交的表单数据结构
    public function updateFormFrameAction(){
//       var_dump($_POST);die;
        //参数
        $frameSelect=array();
        $canshu=array();
//        var_dump($_POST);die;
       //得到商户的id，和小工具的tid
        if(empty($_POST['gid'])){
            $this->returnMessage(null,"gid不能为空",-229);
        }elseif(!is_numeric($_POST['gid'])){
            $this->returnMessage(null,"gid必须是数字",-225);
        }
       //搜集用户的信息
//        $id=$_POST['id'];
//        $tid=$_POST['tid'];
        $gid=$_POST['gid'];
        $title=$_POST['title'];
        $startime=strtotime(($_POST['startime']));
//        var_dump($startime);var_dump(date("Y-m-d H:i:s",$startime)); die;
        $endtime=strtotime($_POST['endtime']);
        $zt=$_POST['zt'];//是否上线
        $miaoshu=$_POST["miaoshu"];
        $jiangpin_1=$_POST["jiangpin_1"];
        $jiangpin_2=$_POST["jiangpin_2"];
        $jiangpin_3=$_POST["jiangpin_3"];
        $length=count($jiangpin_1);//数组的长度，循环的次数
        for($i=0;$i<$length;++$i){
            $frameSelect[$i]=array("name"=>urlencode($jiangpin_1[$i]),"zt"=>$zt,"zhi"=>urlencode($jiangpin_2[$i]));
        }
        $canshu=urldecode(json_encode(array("info"=>$frameSelect)));
//        var_dump($canshu);
        //使用post请求接口
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=tool_log&function=updateFormFrame&gid=$gid&title=$title&starttime=$startime&endtime=$endtime&miaoshu=$miaoshu&canshu=$canshu&zt=$zt";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
//       var_dump($result);var_dump($data);die;
        $result=json_decode($result,true);
        if(empty($result)){
            $this->returnMessage(null,"修改此商户from表单失败",-237);
        }
        $this->returnMessage($result,"修改此商户from表单成功",237);

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