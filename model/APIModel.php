<?php
/**
 * Created by PhpStorm.
 * User: daday
 * Date: 2017/11/6
 * Time: 10:15
 * 中间程序请求的接口
 */
  class APIModel{
             //验证token
      public function confirmToken($parameter){
          $token=$parameter['token'];
          $url=$parameter['url'];
          $xingwei=$parameter['xingwei'];
          $data="action=jiaoyan&token=$token&xingwei=$xingwei";

          $result=$this->curlPost($url,$data);
         echo $result;

      }
      //评论
      //审核状态的改变
      public function changeState($parameter){
          //得到model对象
          $modelObj=new Model($parameter['table']);
          $table=$modelObj->table;
          //先得到这个评价
          $sql="select zt from $table WHERE plid=".$parameter['plid'];
          $result=$modelObj->db->getAll($sql);
          if($result[0]['zt']=1){
              $list=array("zt"=>0);
              $where="plid=".$parameter['plid'];
              $result= $modelObj->update($list,$where);
          }else{
              $list=array("zt"=>1);
              $where="plid=".$parameter['plid'];
              $result= $modelObj->update($list,$where);
          }

          return json_encode($result);
      }
      //回复评论
      public function replyComment($parameter){
//          table=pinlun&function=replyComment&id=$id&zid=$zid&plid=$plid&hftext=$hftext
          //得到model对象
          $modelObj=new Model($parameter['table']);
          $list=array('id'=>$parameter['id'],"zid"=>$parameter['zid'],'hftext'=>$parameter['hftext'],"hftime"=>time());
          $where="plid=".$parameter['plid'];
          $result= $modelObj->update($list,$where);
          return json_encode($result);

      }
      public function replyByfs($parameter){//通过部门zid和fs获取评论
          //得到model对象
          $modelObj=new Model($parameter['table']);
//          $where="id=".$parameter['id'];
          $tableData= $modelObj->table;
            if($parameter['zid']==0){//查找全部的评论
                $where="";
            }else{
                $where="where zid={$parameter['zid']}";
            }


          //起始位置
          $start=($parameter['page']-1)*20;
          //每一页显示的条数
          $pageNum=20;

          //拼接sql语句
          $sql="select * from $tableData where zid={$parameter['zid']} $where limit $start,$pageNum";
          //调用Mysql类的方法，查询所有的数据
          $result=$modelObj->db->getAll($sql);
          //循环判断有没有回复
          $replied=array();//存放回复的数据
          $repling=array();//存放未回复的数据
          $length=count($result);
          for($i=0;$i<$length;++$i){
              if($result[$i]['hftime']==null){
                  $repling[$i]=$result[$i];
              }else{
                  $replied[$i]=$result[$i];
              }
          }
          if($parameter['fs']==0){
              //返回数据
              return json_encode($result);
          }elseif($parameter['fs']==1){
              //返回数据
              return json_encode($replied);
          }else{
              //返回数据
              return json_encode($repling);
          }

      }

      //任务
       //显示部门的任务
      public function showAllTasksByzt($parameter){
          //得到model对象
          $modelObj=new Model($parameter['table']);
//          $where="id=".$parameter['id'];
          $tableData= $modelObj->table;
           if($parameter['zt']!=0){
               $where="and zt=".$parameter['zt'];
           }else{
               $where="";
           }
          //起始位置
          $start=($parameter['page']-1)*20;
          //每一页显示的条数
          $pageNum=20;

          //拼接sql语句
          $sql="select * from $tableData where zid={$parameter['zid']} $where limit $start,$pageNum";
          //调用Mysql类的方法，查询所有的数据
          $result=$modelObj->db->getAll($sql);
          //返回数据
          return json_encode($result);
      }

      //给某个部门添加任务
      public function addTask($parameter){
//          $data="table=renwu&function=addTask&id=$id&zid=$zid&userid=$userid&leixing=$leixing&kaimen=$kaimen&fangjian=$fangjian&shijian=$shijian&context=$context";
          //得到model对象
          $modelObj=new Model($parameter['table']);
          $list=array('id'=>$parameter['id'],'userid'=>$parameter['userid'],"leixing"=>$parameter['leixing'],"kaimen"=>$parameter['kaimen'],'fangjian'=>$parameter['fangjian'],'shijian'=>$parameter['shijian'],"context"=>$parameter['context'],"zt"=>$parameter['zt'],"startime"=>time(),"zt"=>1);
          $result= $modelObj-> insert($list);
          return json_encode($result);
      }




       //小工具
      //获取某个商户的所有的小工具
      public function showAllTools($parameter){
          //得到model对象
          $modelObj=new Model($parameter['table']);
//          $where="id=".$parameter['id'];
          $tableData= $modelObj->table;
          //拼借sql语句
//          $sql="select tid,action,title,guize,px from $tableData where id={$parameter['id']}";
          $sql="select tid,action,title,guize,px from $tableData where id={$parameter['id']}";
          //调用Mysql类的方法，查询所有的数据
          $result=$modelObj->db->getAll($sql);
          //返回数据
          return json_encode($result);
      }

      //收集某一个商户id，提交的表单数据结构
      public function addFormFrame($parameter){
          //得到model对象
          $modelObj=new Model($parameter['table']);
          $list=array('id'=>$parameter['id'],'title'=>$parameter['title'],"tid"=>$parameter['tid'],"miaoshu"=>$parameter['miaoshu'],'startime'=>$parameter['starttime'],'endtime'=>$parameter['endtime'],"canshu"=>$parameter['canshu'],"zt"=>$parameter['zt']);
          $result= $modelObj-> insert($list);
          return json_encode($result);
     }
     //根据gid显示一个formFrame
      public function showFormFrame($parameter){
          //得到model对象
          $modelObj=new Model($parameter['table']);
          $tableData= $modelObj->table;
          //拼接sql语句
          $sql="select tid,id,title,startime,endtime,miaoshu,canshu from $tableData where gid={$parameter['gid']}";
          //调用Mysql类的方法，查询所有的数据
          $result=$modelObj->db->getAll($sql);
//          var_dump($result);die;
          //返回数据
          echo json_encode($result);
      }

      //显示某个商户的使用中小工具的记录
       public  function showUseingToolsById($parameter){
           $modelObj=new Model($parameter['table']);
           $tableData= $modelObj->table;
           //起始位置
           $start=($parameter['page']-1)*20;
           //每一页显示的条数
           $pageNum=20;

           //拼接sql语句
           $sql="select * from $tableData where id={$parameter['id']} and zt=1 limit $start,$pageNum";
           //调用Mysql类的方法，查询所有的数据
           $result=$modelObj->db->getAll($sql);
           //返回数据
           echo json_encode($result);       }
           //显示某个商户的使用过期小工具的记录
       public  function showExpiredToolsById($parameter){
           $modelObj=new Model($parameter['table']);
           $tableData= $modelObj->table;
           //起始位置
           $start=($parameter['page']-1)*20;
           //每一页显示的条数
           $pageNum=20;

           //拼接sql语句
           $sql="select * from $tableData where id={$parameter['id']} and zt=0 limit $start,$pageNum";
           //调用Mysql类的方法，查询所有的数据
           $result=$modelObj->db->getAll($sql);
           //返回数据
           echo json_encode($result);       }
      //显示某个商户的使用全部小工具的记录
      public function showAllToolsById($parameter){
          $modelObj=new Model($parameter['table']);
          $tableData= $modelObj->table;
          //起始位置
          $start=($parameter['page']-1)*20;
          //每一页显示的条数
          $pageNum=20;

          //拼接sql语句
          $sql="select * from $tableData where id={$parameter['id']} limit $start,$pageNum";
          //调用Mysql类的方法，查询所有的数据
          $result=$modelObj->db->getAll($sql);
          //返回数据
          echo json_encode($result);
      }

      // 显示用户用小工具提交的数据
      public function showFormDataFromUser($parameter){
          $modelObj=new Model($parameter['table']);
          $tableData= $modelObj->table;
          //起始位置
          $start=($parameter['page']-1)*20;
          //每一页显示的条数
          $pageNum=20;
          //拼接sql语句
          $sql="select * from $tableData where gid={$parameter['gid']} limit $start,$pageNum";

          //调用Mysql类的方法，查询所有的数据
          $result=$modelObj->db->getAll($sql);
//          var_dump($result);die;
          //返回数据
          echo json_encode($result);

      }
     //根据gid更新某一个商户id提交的表单数据结构updateFormFrame
      public function updateFormFrame($parameter){
          //得到model对象
          $modelObj=new Model($parameter['table']);
          $list=array('title'=>$parameter['title'],"miaoshu"=>$parameter['miaoshu'],'startime'=>$parameter['starttime'],'endtime'=>$parameter['endtime'],"canshu"=>$parameter['canshu'],"zt"=>$parameter['zt']);
          $where="gid=".$parameter['gid'];
          $result= $modelObj->update($list,$where);
          return json_encode($result);
     }
       /*//显示要跟新的详细行程
      public function showOneSchedul($parameter){

              //得到model对象
              $modelObj=new Model($parameter['table']);
              $tableData= $modelObj->table;
              //拼接sql语句
              $sql="select * from $tableData where xcid={$parameter['xcid']}";
              //调用Mysql类的方法，查询所有的数据
              $result=$modelObj->db->getAll($sql);
//          var_dump($result);die;
              //返回数据
              echo json_encode($result);

      }*/
      //更新某条详细行程
     public function updateOneSchedule($parameter){

          //得到model对象
          $modelObj=new Model($parameter['table']);

          $where="xcid=".$parameter['xcid'];
         $list=array('title'=>$parameter['title'],"url"=>$parameter['url'],"vip"=>$parameter['vip'],'shijian'=>$parameter['shijian'],'miaoshu'=>$parameter['miaoshu'],"px"=>$parameter['px'],"context"=>$parameter['context']);
         $result= $modelObj-> update($list,$where);
         return json_encode($result);

      }
      //删除某条详细行程
     public function deleteOneschedule($parameter){

          //得到model对象
          $modelObj=new Model($parameter['table']);
          //删除行程
          $result= $modelObj->delete($parameter['xcid']);
          return json_encode($result);


      }
      //添加一条行程的详细信息
     public function addOneSchedule($parameter){

          //得到model对象
          $modelObj=new Model($parameter['table']);
          $list= $list=array('xid'=>$parameter['xid'],'title'=>$parameter['title'],"url"=>$parameter['url'],"vip"=>$parameter['vip'],'shijian'=>$parameter['shijian'],'miaoshu'=>$parameter['miaoshu'],"px"=>$parameter['px'],"context"=>$parameter['context']);
          $result= $modelObj-> insert($list);
          return json_encode($result);


      }

      //显示要修改的简介行程的数据
      //根据gid显示一个formFrame
      public function showOne($parameter){
          //得到model对象
          $modelObj=new Model($parameter['table']);
          $tableData= $modelObj->table;
          //拼接sql语句
          $sql="select * from $tableData where xid={$parameter['xid']}";
          //调用Mysql类的方法，查询所有的数据
          $result=$modelObj->db->getAll($sql);
//          var_dump($result);die;
          //返回数据
          echo json_encode($result);
      }
      //修改行程
     public function updateOne($parameter){

          //得到model对象
          $modelObj=new Model($parameter['table']);
         $list=array('jiage'=>$parameter['jiage'],'title'=>$parameter['title'],"keyboard"=>$parameter['keyboard'],"titlepic"=>$parameter['titlepic'],'pic'=>$parameter['pic'],'miaoshu'=>$parameter['miaoshu'],"px"=>$parameter['px'],"zt"=>$parameter['zt'],"time"=>time());
          $where="xid=".$parameter['xid'];
          $result= $modelObj-> update($list,$where);
          return json_encode($result);
      }
      //删除行程
     public function deleteOne($parameter){
          //得到model对象
          $modelObj=new Model($parameter['table']);
          //删除行程
          $result= $modelObj->delete($parameter['xid']);
          return json_encode($result);

      }
      //添加行程
     public function addOne($parameter){
          //得到model对象
          $modelObj=new Model($parameter['table']);
          $list=array('id'=>$parameter['id'],'zid'=>$parameter['zid'],'jiage'=>$parameter['jiage'],'title'=>$parameter['title'],"keyboard"=>$parameter['keyboard'],"titlepic"=>$parameter['titlepic'],'pic'=>$parameter['pic'],'miaoshu'=>$parameter['miaoshu'],"px"=>$parameter['px'],"zt"=>$parameter['zt'],"time"=>time());
          $result= $modelObj-> insert($list);
          return json_encode($result);

      }
      //通过xid获取行程安排的方法
     public function getOneSchedule($parameter){
          //得到model对象
          $modelObj=new Model($parameter['table']);
          $tableData= $modelObj->table;
          //编写sql语句
          $sql="select * from $tableData where xid={$parameter['xid']}";
          $result= $modelObj->db->getAll($sql);
          return json_encode($result);

      }
      //获取所某个用户数据
     public function getOneUserInfo($parameter){
          //得到model对象
          $modelObj=new Model($parameter['table']);
          $tableData= $modelObj->table;
          //               exit($tableData);
          //编写sql语句
          $sql="select * from $tableData where id={$parameter['id']} and zid={$parameter['zid']}";
          //               exit($sql);
          //调用得到所有数据的方法
          $result= $modelObj->db->getAll($sql);
          return json_encode($result);


      }

      //获取所有的用户数据
     public function getAllUserInfo($parameter){
          //得到model对象
          $modelObj=new Model($parameter['table']);
          $tableData= $modelObj->table;
//               exit($tableData);
         //起始位置
         $start=($parameter['page']-1)*20;
         //每一页显示的条数
         $pageNum=20;
          //编写sql语句
          $sql="select xid,title,keyboard,titlepic,pic,miaoshu from $tableData limit $start,$pageNum";
//               exit($sql);
          //调用得到所有数据的方法
          $result= $modelObj->db->getAll($sql);
          return json_encode($result);


      }
     public function decodeUnicode($str)
      {
          return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
              create_function(
                  '$matches',
                  'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
              ),
              $str);
      }

      //返回成功的数据给用户的方法
     public function returnMessage($data,$info,$code){
          /* $data=urlencode($data);
           $info=urlencode($info);
           $code=urlencode($code);*/
          $output['result']=$data;
          $output['msg']="$info";
          $output['status']="$code";
          // exit(json_encode($output,JSON_UNESCAPED_UNICODE));

          exit($this->decodeUnicode(json_encode($output)));
      }
      public function curlPost($url,$data){

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

  }