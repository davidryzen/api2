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
                $GLOBALS['config'] = include  "config.php";
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
           }else{
               returnMessage(null,"请携带参数action的值不存在",-206);
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
            if(empty($result)){
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

       //返回成功的数据给用户的方法
      function returnMessage($data,$info,$code){
          $output['result']=$data;
          $output['ms']="$info";
          $output['status']="$code";
          exit(json_encode($output,JSON_UNESCAPED_UNICODE));
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