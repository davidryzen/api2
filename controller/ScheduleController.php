<?php
/**
 * Created by PhpStorm.
 * User: daday
 * Date: 2017/11/2
 * Time: 15:24
 * 接受用户的请求，并判断用户的请求
 */

class ScheduleController{
    public function __construct($appid,$appkey,$filepath,$confirmUrl){
        //生成token
       /*$token= $this->getToken($appid,$appkey,$filepath);
       $data="token=$token";
        $result=$this->curlPost($confirmUrl,$data);
        if(!$result){
            $this-> returnMessage(null,"token验证失败或者失效",-10000);
        }*/


    }
//    public $parameter=array();
    //请求所有的行程
    public function getAllAction(){
        //做token密钥验证
        //根据id和zid获取某一条行程
        //每一页显示20条，得到要查询的页数
        $page=empty($_POST['page'])?1:$_POST['page'];
        //调用接口的方法
        /*//得到接口类实例对象
        $APIModelObj=new APIModel();
        //调用得到所有信息的方法
        $result=$APIModelObj-> getAllUserInfo("xingcheng",$page);*/
       /*$this-> parameter['page']=$page;
       $this-> parameter[]=",";
       $this-> parameter['mysqlTable']="xingcheng";
       $this->parameter=implode( $this->parameter);*/
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=xingcheng&function=getAllUserInfo&page=$page";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
//       var_dump($result);die;
        $result=json_decode($result,true);
//
        if(!isset($result)){
            $this-> returnMessage(null,"请求所有用户行程的数据出错",-213);
        }elseif(empty($result)){
            $this-> returnMessage(null,"当前页数没有数据",-302);
        }
        for($i=0;$i<count($result);++$i){
            $result[$i]['pic']=explode("||||||",$result[$i]['pic']);
        }
        $this-> returnMessage($result,"请求所有用户行程的数据success",200);
    }
    public function getOneAction(){

        if(empty($_POST['id'])||empty($_POST['zid'])){
            $this->returnMessage(null,"请携带参数id或zid",-204);
        }elseif(!is_numeric($_POST['id'])||!is_numeric($_POST['zid'])){
            $this->returnMessage(null,"参数id或zid必须是数字",-205);
        }
        //获取参数id
        $id=$_POST['id'];
        //获取参数zid
        $zid=$_POST['zid'];
        /*//得到接口类实例对象
        $APIModelObj=new APIModel();
        //调用得到所有信息的方法
        $result=$APIModelObj-> getOneUserInfo("xingcheng",$id,$zid);*/
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=xingcheng&function=getOneUserInfo&id=$id&zid=$zid";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
        $result=json_decode($result,true);
        if(empty($result)){
            $this->returnMessage(null,"请求某个用户行程的数据出错",-202);
        }
        for($i=0;$i<count($result);++$i){
            $result[$i]['pic']=explode("||||||",$result[$i]['pic']);
        }
        $this->returnMessage($result,"请求此用户行程的数据success",201);


    }
  //给某人和某部门添加一条行程
    public function addOneAction(){
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

    //根据某个人的xid删除某条行程描述
    public function deleteOneAction(){
        if(empty($_POST['xid'])){
            $this->returnMessage(null,"xid不能为空",-209);
        }
        //接受要删除的xid
        $xid=$_POST['xid'];
        /*//得到接口类实例对象
        $APIModelObj=new APIModel();
        //调用得到所有信息的方法
        $result= $APIModelObj-> deleteOne("xingcheng",$xid);*/
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=xingcheng&function=deleteOne&xid=$xid";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
        $result=json_decode($result,true);
        if(empty($result)){
            $this->returnMessage(null,"删除行程失败或者这条xid对应的行程不存在",-208);
        }
        $this->returnMessage($result,"删除行程成功",208);

    }

    //根据某个人的xid更新修改某条行程描述
    public function updateOneAction(){
        if(empty($_POST['xid'])){
            $this->returnMessage(null,"xid不能为空",-210);
        }

        //接受用户上传的文件
        $xid=$_POST['xid'];//用户id
        $pic=isset($_POST['pic'])?$_POST["pic"]:"";//上传的文件
        $titlepic=isset($_POST['img'])?$_POST["img"]:"";
        $title=isset($_POST['title'])?$_POST["title"]:"";
        $keyboard=isset($_POST['keyboard'])?$_POST["keyboard"]:"";
        $zt=isset($_POST['leixing'])?$_POST["leixing"]:"";
        $jiage=isset($_POST['jiage'])?$_POST["jiage"]:"";
        $px=isset($_POST['px'])?$_POST["px"]:"";
        $miaoshu=isset($_POST['miaoshu'])?$_POST["miaoshu"]:"";

//        $list=array('jiage'=>$jiage,'title'=>$title,"keyboard"=>$keyboard,"titlepic"=>$titlepic,'pic'=>$pic,'miaoshu'=>$miaoshu,"px"=>$px,"zt"=>$zt,"time"=>time());
       /* //得到接口类实例对象
        $APIModelObj=new APIModel();
        //调用得到所有信息的方法
        $result= $APIModelObj-> updateOne("xingcheng",$xid,$list);*/
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=xingcheng&function=updateOne&xid=$xid&jiage=$jiage&title=$title&keyboard=$keyboard&titlepic=$titlepic&pic=$pic&miaoshu=$miaoshu&px=$px&zt=$zt";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
        $result=json_decode($result,true);
        if(empty($result)){
           $this-> returnMessage(null,"修改行程失败,或者xid对应的行程不存在",-211);
        }
       $this-> returnMessage($result,"修改行程成功",211);

    }
  //根据xid获取一条详细的行程
    public function getOneScheduleAction(){
        if(empty($_POST['xid'])){
            $this->returnMessage(null,"请携带参数xid",-206);
        }elseif(!is_numeric($_POST['xid'])){
            $this->returnMessage(null,"参数xid必须是数字",-207);
        }
        //获取参数xid
        $xid=$_POST['xid'];
       /* //得到接口类实例对象
        $APIModelObj=new APIModel();
        $result=$APIModelObj-> getOneSchedule("xingcheng_data",$xid);*/
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=xingcheng_data&function=getOneSchedule&xid=$xid";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
        $result=json_decode($result,true);
        if(empty($result)){
            /*$output['data']=null;
            $output['info']="请求所有用户行程的数据出错";
            $output['code']="-202"
            exit(json_encode($output,JSON_UNESCAPED_UNICODE));;*/
            returnMessage(null,"请求某个用户行程安排的数据出错",-205);
        }
        $this->  returnMessage($result,"请求行程安排成功",203);
    }

    //给某一个xid增加一个详细的行程
    public function addOneScheduleAction(){
        if(empty($_POST['xid'])){
            $this->returnMessage(null,"请携带参数xid",-206);
        }elseif(!is_numeric($_POST['xid'])){
            $this->returnMessage(null,"参数xid必须是数字",-207);
        }
        //接受用户传递过来的信息
        $xid=$_POST['xid'];
        $vip=isset($_POST['vip'])?$_POST['vip']:"";
        $shijian=isset($_POST['shijian'])?$_POST['shijian']:"";
        $px=isset($_POST['px'])?$_POST['px']:"";
        $miaoshu=isset($_POST['miaoshu'])?$_POST['miaoshu']:"";
        $title=isset($_POST['title'])?$_POST['title']:"";
        $urlPath=isset($_POST['link'])?$_POST['link']:"";
        $context_1=isset($_POST['context_1'])?$_POST['context_1']:"";
        $context_2=isset($_POST['context_2'])?$_POST['context_2']:"";
        $context_3=isset($_POST['context_3'])?$_POST['context_3']:"";
        $context_4=isset($_POST['context_4'])?$_POST['context_4']:"";

        $context_5=isset($_POST['context_5'])?$_POST['context_5']:"";

        $context=array();

        //循环次数
        $length=count($context_1);

        $m=0;
        for($i=0;$i<$length;++$i){

            $context[$i]=array('shijian'=>urlencode($context_1[$i]),'name'=>urlencode($context_2[$i]),'gongneng'=>urlencode($context_3[$i]),'zt'=>urlencode($context_4[$i]));
            if($context_4[$i]==0){
//                    $context[]=array('zhi'=>"");
                $context[$i]["zhi"]="";
            }else{
//                    $context[]=array('zhi'=>$context_5[$m]);
                if($context_4[$i]==3){
                    $context[$i]["zhi"]=$context_5[$i];continue;
                }
                $context[$i]["zhi"]=$context_5[$m];
                ++$m;
            }
        }
        $context=json_encode($context);
        $context=urldecode($context);

//        $list=array('xid'=>$xid,'title'=>$title,"url"=>$urlPath,"vip"=>$vip,'shijian'=>$shijian,'miaoshu'=>$miaoshu,"px"=>$px,"context"=>$context);

        //得到接口类实例对象
//        $APIModelObj=new APIModel();
//        $result=$APIModelObj-> addOneSchedule("xingcheng_data",$list);
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=xingcheng_data&function=addOneSchedule& xid=$xid&title=$title&url=$urlPath&vip=$vip&shijian=$shijian&miaoshu=$miaoshu&px=$px&context=$context";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
        $result=json_decode($result,true);
        if(empty($result)){
           $this-> returnMessage(null,"添加此次详细行程失败",-210);
        }
       $this-> returnMessage($result,"添加此次详细行程成功",210);
    }

    //根据xcid删除某条详细的行程
    public function deleteOneScheduleAction(){
        if(empty($_POST['xcid'])){
            $this->returnMessage(null,"请携带参数xcid",-226);
        }elseif(!is_numeric($_POST['xcid'])){
            $this->returnMessage(null,"参数xcid必须是数字",-227);
        }
        //得到行程id用于删除
        $xcid=$_POST['xcid'];
      /*  //得到接口类实例对象
        $APIModelObj=new APIModel();
        $result=$APIModelObj->deleteOneschedule("xingcheng_data",$xcid);*/
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";
        $data="table=xingcheng_data&function=deleteOneschedule&xcid=$xcid";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
        $result=json_decode($result,true);
        if(empty($result)){
           $this-> returnMessage(null,"删除详细行程失败",-211);
        }
       $this-> returnMessage($result,"删除详细行程成功",211);
    }

    //根据xcid更新某条详细的行程
    public  function  updateOneScheduleAction(){
        if(empty($_POST['xcid'])){
            $this->returnMessage(null,"请携带参数xcid",-226);
        }elseif(!is_numeric($_POST['xcid'])){
            $this->returnMessage(null,"参数xcid必须是数字",-227);
        }
        $xcid=$_POST['xcid'];
        $xcid=$_GET['xcid'];

        $xid=$_POST['xid'];
        $vip=$_POST['vip'];
        $shijian=$_POST['shijian'];
        $px=$_POST['px'];
        $miaoshu=$_POST['miaoshu'];
        $title=$_POST['title'];
        $urlPath=$_POST['link'];
        $context_1=$_POST['context_1'];
        $context_2=$_POST['context_2'];
        $context_3=$_POST['context_3'];
        $context_4=$_POST['context_4'];

        $context_5=$_POST['context_5'];

        $context=array();

        //循环次数
        $length=count($context_1);

        $m=0;
        for($i=0;$i<$length;++$i){

            $context[$i]=array('shijian'=>urlencode($context_1[$i]),'name'=>urlencode($context_2[$i]),'gongneng'=>urlencode($context_3[$i]),'zt'=>urlencode($context_4[$i]));
            if($context_4[$i]==0){
//                    $context[]=array('zhi'=>"");
                $context[$i]["zhi"]="";
            }else{
//                    $context[]=array('zhi'=>$context_5[$m]);
                if($context_4[$i]==3){
                    $context[$i]["zhi"]=$context_5[$i];continue;
                }
                $context[$i]["zhi"]=$context_5[$m];
                ++$m;
            }
        }
        $context=json_encode($context);
        $context=urldecode($context);
//        $list=array('title'=>$title,"url"=>$url,"vip"=>$vip,'shijian'=>$shijian,'miaoshu'=>$miaoshu,"px"=>$px,"context"=>$context);

        /*//得到接口类实例对象
        $APIModelObj=new APIModel();
        $result=$APIModelObj->updateOneSchedule("xingcheng_data",$xcid,$list);*/
        //请求的地址
        $url="http://www.you.com/API/schedule2/model/start.php";

        $data="table=xingcheng_data&function=updateOneSchedule& xcid=$xcid&title=$title&url=$urlPath&vip=$vip&shijian=$shijian&miaoshu=$miaoshu&px=$px&context=$context";
        //使用post请求接口
        $result=$this->curlPost($url,$data);
        $result=json_decode($result,true);
        if(empty($result)){
            /*$output['data']=null;
            $output['info']="请求所有用户行程的数据出错";
            $output['code']="-202"
            exit(json_encode($output,JSON_UNESCAPED_UNICODE));;*/
            $this->returnMessage(null,"更新此次详细行程失败",-212);
        }
        $this->returnMessage($result,"更新此次详细行程成功",212);

    }

    /**
     * @param $url string
     * @param $data string
     * @return string|bool
     */
    public function curlPost($url,$data){
//        var_dump($url);var_dump($data);die;
        //创建一个curl句柄
        $curl=curl_init();

        //设置curl参数
        curl_setopt($curl,CURLOPT_URL,$url);//设置请求的地址
        //不要显示出来
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        //设置post请求，并传给数据
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        //不要ssl安全验证
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
        //发出请求得到相应的结果
        $result=curl_exec($curl);
//        var_dump($result);var_dump("helllo");die;
        if (!isset($result)){
           exit(curl_error($curl));
        }

        //关闭时间句柄
        curl_close($curl);
        //返回结果
        return $result;
    }


    /**
     * @param $appid
     * @param $appkey
     * @param $filepath
     * @return bool|string
     */
    //得到，新建，储存token
    public function getToken($appid,$appkey,$filepath){
        $Toke_life=7200;
        if(file_exists($filepath)&&filemtime($filepath)+$Toke_life>time()){//保存Token的文件存在，且没有过期
              return file_get_contents($filepath);//直接返回文件中的token
        }

        //创建一个curl句柄
        $curlObj=curl_init();
        //请求的url地址
        $url="https://www.mijiweb.com/qcloud/OAuth/";
        //post请求携带的参数
        $postParameter="appid=$appid&appkey=$appkey";

        //设置curl参数
        //设置curl参数之请求的URL
        curl_setopt($curlObj, CURLOPT_URL, $url);
        //不显示在屏幕上
        curl_setopt($curlObj,CURLOPT_RETURNTRANSFER,1);
        //模拟post请求
        curl_setopt($curlObj,CURLOPT_POST,1);
        curl_setopt($curlObj,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($curlObj,CURLOPT_SSL_VERIFYHOST,0);
        //请求携带的数据
        curl_setopt($curlObj,CURLOPT_POSTFIELDS,$postParameter);
        //执行最后请求操作
        $returndata=curl_exec($curlObj);
//        if(!isset($returndata)){
//            exit( curl_error($curlObj));
//        }
        //关闭curl句柄
        curl_close($curlObj);

        if(isset($returndata)){
            $token=json_decode($returndata)->token;
        //在本地保存一份Token
        $filePath="./Token.txt";
        $file=fopen($filePath,"w+b") or die("fail to open file");
        //向文件流中写入数据
        fwrite($file,$token);
        //关闭流
        fclose($file);

        //返回相应的数据
      return $token;
      }else{
           return "获取token失败";
        }

    }

    private  function decodeUnicode($str)
    {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
            create_function(
                '$matches',
                'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
            ),
            $str);
    }
    //返回成功的数据给用户的方法
    private  function returnMessage($data,$info,$code){
        /* $data=urlencode($data);
         $info=urlencode($info);
         $code=urlencode($code);*/
        /* $output['result']=urlencode($data);
         $output['msg']=urlencode("$info");
         $output['status']=urlencode("$code");*/

        $output['result']=$data;
        $output['msg']="$info";
        $output['status']="$code";
        // exit(json_encode($output,JSON_UNESCAPED_UNICODE));

        exit(self::decodeUnicode(json_encode($output)));
    }
}