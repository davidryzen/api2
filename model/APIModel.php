<?php
/**
 * Created by PhpStorm.
 * User: daday
 * Date: 2017/11/6
 * Time: 10:15
 * 中间程序请求的接口
 */
  class APIModel{
      //更新某条详细行程
     public function updateOneSchedule($table,$xcid,$list){

          //得到model对象
          $modelObj=new Model($table);

          $where="xcid=".$xcid;
         $result= $modelObj-> update($list,$where);
         return json_encode($result);

      }
      //删除某条详细行程
     public function deleteOneschedule($table,$xcid){

          //得到model对象
          $modelObj=new Model($table);
          //删除行程
          $result= $modelObj->delete($xcid);
          return json_encode($result);


      }
      //添加一条行程的详细信息
     public function oneSchedule($table,$list){

          //得到model对象
          $modelObj=new Model($table);

          $result= $modelObj-> insert($list);
          return json_encode($result);


      }
      //修改行程
     public function updateOne($table,$xid,$list){

          //得到model对象
          $modelObj=new Model($table);

          $where="xid=".$xid;
          $result= $modelObj-> update($list,$where);
          return json_encode($result);
      }
      //删除行程
     public function deleteOne($table,$xid){
          //得到model对象
          $modelObj=new Model($table);
          //删除行程
          $result= $modelObj->delete($xid);
          return json_encode($result);

      }
      //添加行程
     public function addOne($table,$list){
          //得到model对象
          $modelObj=new Model($table);
          /*$list=array('id'=>$id,'zid'=>$zid,'jiage'=>$jiage,'title'=>$title,"keyboard"=>$keyboard,"titlepic"=>$titlepic,'pic'=>$pic,'miaoshu'=>$miaoshu,"px"=>$px,"zt"=>$zt,"time"=>time());*/
          $result= $modelObj-> insert($list);
          return json_encode($result);

      }
      //通过xid获取行程安排的方法
     public function getOneSchedule($table,$xid){
          //得到model对象
          $modelObj=new Model($table);
          $tableData= $modelObj->table;
          //编写sql语句
          $sql="select title,url,vip,shijian,miaoshu,context from $tableData where xid=$xid";
          $result= $modelObj->db->getAll($sql);
          return json_encode($result);

      }
      //获取所某个用户数据
     public function getOneUserInfo($table,$id,$zid){
          //得到model对象
          $modelObj=new Model($table);
          $tableData= $modelObj->table;
          //               exit($tableData);
          //编写sql语句
          $sql="select xid,title,keyboard,titlepic,pic,miaoshu from $tableData where id=$id and zid=$zid";
          //               exit($sql);
          //调用得到所有数据的方法
          $result= $modelObj->db->getAll($sql);
          return json_encode($result);


      }

      //获取所有的用户数据
     public function getAllUserInfo($table,$page){
          //得到model对象
          $modelObj=new Model($table);
          $tableData= $modelObj->table;
//               exit($tableData);
         //起始位置
         $start=($page-1)*20;
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
      /*//如果用户格式和数据输入错误的方法
           public function returnUserError($data,$info,$code){
                $output['data']=$data;
                $output['info']="$info";
                $output['code']="$code";
                exit(json_encode($output,JSON_UNESCAPED_UNICODE));
            }*/
      //获取token

  }