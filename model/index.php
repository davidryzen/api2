<?php
/**
 * Created by PhpStorm.
 * User: daday
 * Date: 2017/10/26
 * Time: 11:37
 */
//phpinfo();
       header("content-type:text/html;charset=utf-8");
//            error_reporting(0);


                //载入配置文件
                $GLOBALS['config'] = include "config.php";
                 //引入数据库类
                include_once "Model.class.php";
                include_once "Mysql.class.php";


        //
            //给用户返回数据
            $output=array();
            $tableData="";
          //获取用户的token
//           $userToken=$_GET['token']?$_GET['token']:"";
           //获取自己的token
            $myToken=getToken("10003","H1dFEDOrd3erkej-erfF30DRFDDASDW3");
//            exit($myToken);
         //通过id和zid可以吧确定一条行程

            //请求的方法
         if(!isset($_GET['action'])){
             /*$output['data']=null;
             $output['info']="请传入请求的方法action参数";
             $output['code']="-203";
             exit(json_encode($output,JSON_UNESCAPED_UNICODE));*/
             returnMessage(null,"请传入请求的方法action参数",-203);
         }
          $action=strtolower($_GET['action']) ;

           if($action=="all"){//得到所有的用户行程
               getAllUserInfo("xingcheng");

           }elseif($action=="one"){//具体获取某一个用户的行程
               if(!isset($_GET['id'])||!isset($_GET['zid'])){
                   returnMessage(null,"请携带参数id或zid",-204);
               }
               //获取参数id
               $id=$_GET['id'];
               //获取参数zid
               $zid=$_GET['zid'];
              $result= getOneUserInfo("xingcheng",$id,$zid);
           }elseif($action=="oneschedule"){//通过xid获取行程安排
               if(!isset($_GET['xid'])){
                   returnMessage(null,"请携带参数xid",-205);
               }
               //获取参数xid
               $xid=$_GET['xid'];
               getOneSchedule("xingcheng_data",$xid);
           }elseif($action=="upload"){
               addSchedule("xingcheng");
           }elseif($action=="deleteSchedule"){
               $xid=$_GET['xid'];
               deleteSchedule("xingcheng",$xid);
           }elseif($action=="updateschedule"){

               updateSchedule("xingcheng",$xid);
           }elseif($action=="onescheduledetail"){
//               var_dump($_POST);die;
               onescheduledetail("xingcheng_data");
           }elseif($action=="deleteonescheduledetail"){
               deleteonescheduledetail("xingcheng_data");
           }elseif($action=="updateonescheduledetail"){
               //得到行程id用于更新
               $xcid=$_POST['xcid'];
               updateonescheduledetail("xingcheng_data",$xcid);
           }else{
               returnMessage(null,"携带参数action的值". $action."不存在",-206);
           }
            //更新某条详细行程
           function updateonescheduledetail($table,$xcid){
               $xid=$_POST['xid'];
               $vip=$_POST['vip'];
               $shijian=$_POST['shijian'];
               $px=$_POST['px'];
               $miaoshu=$_POST['miaoshu'];
               $title=$_POST['title'];
               $url=$_POST['link'];
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
               //得到model对象
               $modelObj=new Model($table);
               $list=array('title'=>$title,"url"=>$url,"vip"=>$vip,'shijian'=>$shijian,'miaoshu'=>$miaoshu,"px"=>$px,"context"=>$context);
               $where="xcid=".$xcid;
               $result= $modelObj-> update($list,$where);
               if(empty($result)){
                   /*$output['data']=null;
                   $output['info']="请求所有用户行程的数据出错";
                   $output['code']="-202"
                   exit(json_encode($output,JSON_UNESCAPED_UNICODE));;*/
                   returnMessage(null,"更新此次详细行程失败",-212);
               }
               returnMessage($result,"更新此次详细行程成功",212);
           }
           //删除某条详细行程
           function deleteonescheduledetail($table){
               //得到行程id用于删除
                $xcid=$_POST['xcid'];
               //得到model对象
               $modelObj=new Model($table);
               //删除行程
               $result= $modelObj->delete($xcid);
               if(empty($result)){
                   /*$output['data']=null;
                   $output['info']="请求所有用户行程的数据出错";
                   $output['code']="-202"
                   exit(json_encode($output,JSON_UNESCAPED_UNICODE));;*/
                   returnMessage(null,"删除详细行程失败",-211);
               }
               returnMessage($result,"删除详细行程成功",211);

           }
         //添加一条行程的详细信息
        function onescheduledetail($table){
             //接受用户传递过来的信息
            $action=$_POST['action'];
//            $id=$_POST['id'];
//            $zid=$_POST['zid'];
            $xid=$_POST['xid'];
            $vip=$_POST['vip'];
            $shijian=$_POST['shijian'];
            $px=$_POST['px'];
            $miaoshu=$_POST['miaoshu'];
            $title=$_POST['title'];
            $url=$_POST['link'];
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
            //得到model对象
            $modelObj=new Model($table);
            $list=array('xid'=>$xid,'title'=>$title,"url"=>$url,"vip"=>$vip,'shijian'=>$shijian,'miaoshu'=>$miaoshu,"px"=>$px,"context"=>$context);
            $result= $modelObj-> insert($list);
            if(empty($result)){
                /*$output['data']=null;
                $output['info']="请求所有用户行程的数据出错";
                $output['code']="-202"
                exit(json_encode($output,JSON_UNESCAPED_UNICODE));;*/
                returnMessage(null,"添加此次详细行程失败",-210);
            }
            returnMessage($result,"添加此次详细行程成功",210);

        }
           //修改行程
         function updateSchedule($table){
             //接受用户上传的文件
             $xid=$_POST['xid'];//用户id

             $pic=$_POST['pic'];//上传的文件
             $titlepic=$_POST['img'];
//               var_dump($titlepic);die;
             $title=$_POST['title'];
             $keyboard=$_POST['keyboard'];
             $zt=$_POST['leixing'];
             $jiage=$_POST['jiage'];
             $px=$_POST['px'];
             $miaoshu=$_POST['miaoshu'];
             //得到model对象
             $modelObj=new Model($table);
             $list=array('jiage'=>$jiage,'title'=>$title,"keyboard"=>$keyboard,"titlepic"=>$titlepic,'pic'=>$pic,'miaoshu'=>$miaoshu,"px"=>$px,"zt"=>$zt,"time"=>time());
             $where="xid=".$xid;
             $result= $modelObj-> update($list,$where);
             if(empty($result)){
                 /*$output['data']=null;
                 $output['info']="请求所有用户行程的数据出错";
                 $output['code']="-202"
                 exit(json_encode($output,JSON_UNESCAPED_UNICODE));;*/
                 returnMessage(null,"修改行程失败",-209);
             }
             returnMessage($result,"修改行程成功",209);
         }
           //删除行程
         function deleteSchedule($table,$xid){
             //得到model对象
             $modelObj=new Model($table);
             //删除行程
            $result= $modelObj->delete($xid);
             if(empty($result)){
                 /*$output['data']=null;
                 $output['info']="请求所有用户行程的数据出错";
                 $output['code']="-202"
                 exit(json_encode($output,JSON_UNESCAPED_UNICODE));;*/
                 returnMessage(null,"删除行程失败",-208);
             }
             returnMessage($result,"删除行程成功",208);
         }
          //添加行程
        function addSchedule($table){
           //接受用户上传的文件
//                var_dump($_POST);
                //动作
               $action=$_POST['action'];
               //接受用户上传的文件
               $id=$_POST['id'];//用户id
               $zid=$_POST['zid'];//部门zid
               $pic=$_POST['pic'];//上传的文件
               $titlepic=$_POST['img'];
//               var_dump($titlepic);die;
               $title=$_POST['title'];
               $keyboard=$_POST['keyboard'];
               $zt=$_POST['leixing'];
               $jiage=$_POST['jiage'];
               $px=$_POST['px'];
               $miaoshu=$_POST['miaoshu'];
            //得到model对象
            $modelObj=new Model($table);
            $list=array('id'=>$id,'zid'=>$zid,'jiage'=>$jiage,'title'=>$title,"keyboard"=>$keyboard,"titlepic"=>$titlepic,'pic'=>$pic,'miaoshu'=>$miaoshu,"px"=>$px,"zt"=>$zt,"time"=>time());
           $result= $modelObj-> insert($list);
            if(empty($result)){
                /*$output['data']=null;
                $output['info']="请求所有用户行程的数据出错";
                $output['code']="-202"
                exit(json_encode($output,JSON_UNESCAPED_UNICODE));;*/
                returnMessage(null,"添加行程失败",-207);
            }
            returnMessage($result,"添加行程成功",207);
        }
         //通过xid获取行程安排的方法
        function getOneSchedule($table,$xid){
            //得到model对象
            $modelObj=new Model($table);
            $tableData= $modelObj->table;
            //编写sql语句
            $sql="select title,url,vip,shijian,miaoshu,context from $tableData where xid=$xid";
           $result= $modelObj->db->getAll($sql);
            if(empty($result)){
                /*$output['data']=null;
                $output['info']="请求所有用户行程的数据出错";
                $output['code']="-202"
                exit(json_encode($output,JSON_UNESCAPED_UNICODE));;*/
                returnMessage(null,"请求某个用户行程安排的数据出错",-205);
            }

                $result['pic']=explode("||||||",$result['pic']);
//                var_dump($result[$i]['pic']);

            returnMessage($result,"请求行程安排成功",203);
        }
        //获取所某个用户数据
        function getOneUserInfo($table,$id,$zid){
            //得到model对象
            $modelObj=new Model($table);
            $tableData= $modelObj->table;
        //               exit($tableData);
            //编写sql语句
            $sql="select xid,title,keyboard,titlepic,pic,miaoshu from $tableData where id=$id and zid=$zid";
        //               exit($sql);
            //调用得到所有数据的方法
            $result= $modelObj->db->getAll($sql);
            if(empty($result)){
                /*$output['data']=null;
                $output['info']="请求所有用户行程的数据出错";
                $output['code']="-202"
                exit(json_encode($output,JSON_UNESCAPED_UNICODE));;*/
                returnMessage(null,"请求某个用户行程的数据出错",-202);
            }

            //查询成功
           /* $output['data']=$result;
            $output['info']="请求此用户行程的数据success";
            $output['code']="201";
            exit(json_encode($output,JSON_UNESCAPED_UNICODE));*/
            for($i=0;$i<count($result);++$i){
             /*  if(strpos($result[$i]['pic'],"||||||")){

               }*/
                $result[$i]['pic']=explode("||||||",$result[$i]['pic']);
//                var_dump($result[$i]['pic']);
            }
            returnMessage($result,"请求此用户行程的数据success",201);

        }

       //获取所有的用户数据
        function getAllUserInfo($table){
            //得到model对象
            $modelObj=new Model($table);
            $tableData= $modelObj->table;
//               exit($tableData);
            //编写sql语句
            $sql="select xid,title,keyboard,titlepic,pic,miaoshu from $tableData";
//               exit($sql);
            //调用得到所有数据的方法
            $result= $modelObj->db->getAll($sql);
            if(!isset($result)){
                /*$output['data']=null;
                $output['info']="请求所有用户行程的数据出错";
                $output['code']="-202"
                exit(json_encode($output,JSON_UNESCAPED_UNICODE));;*/
                returnMessage(null,"请求所有用户行程的数据出错",-202);
            }
//             $picArray=$result['pic'];
//            var_dump(count($result));die;

            for($i=0;$i<count($result);++$i){
             /*  if(strpos($result[$i]['pic'],"||||||")){

               }*/
                $result[$i]['pic']=explode("||||||",$result[$i]['pic']);
//                var_dump($result[$i]['pic']);
            }
//            die;
            //查询成功
           /* $output['data']=$result;
            $output['info']="请求所有用户行程的数据success";
            $output['code']="200";
            exit(json_encode($output,JSON_UNESCAPED_UNICODE));*/
            returnMessage($result,"请求所有用户行程的数据success",200);

        }
         function decodeUnicode($str)
          {
              return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
                  create_function(
                      '$matches',
                      'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
                  ),
                  $str);
          }

       //返回成功的数据给用户的方法
      function returnMessage($data,$info,$code){
       /* $data=urlencode($data);
        $info=urlencode($info);
        $code=urlencode($code);*/
          $output['result']=$data;
          $output['msg']="$info";
          $output['status']="$code";
          // exit(json_encode($output,JSON_UNESCAPED_UNICODE));

          exit(decodeUnicode(json_encode($output)));
      }
/*//如果用户格式和数据输入错误的方法
      function returnUserError($data,$info,$code){
          $output['data']=$data;
          $output['info']="$info";
          $output['code']="$code";
          exit(json_encode($output,JSON_UNESCAPED_UNICODE));
      }*/
            //获取token
       function getToken($appid,$appkey){
           //创建一个curl句柄
           $curlObj=curl_init();
           //请求的url地址
           $url="https://www.mijiweb.com/qcloud/OAuth/";
           //post请求携带的参数
           $postParameter="appid=10003&appkey=H1dFEDOrd3erkej-erfF30DRFDDASDW3";

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
             if(!isset($returndata)){
                exit( curl_error($curlObj));
             }
             //关闭curl句柄
             curl_close($curlObj);
           //返回相应的数据
             return $returndata;


       }