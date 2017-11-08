<?php
/**
 * Created by PhpStorm.
 * User: daday
 * Date: 2017/11/6
 * Time: 10:15
 * 中间程序请求的接口
 */
  class APIModel{
      public function __construct($token)
      {
        //验证token
          $url="https://www.mijiweb.com/qcloud/OAuth/";
          $data="action=jiaoyan&token=$token&xingwei=xingcheng";
         $this-> curlPost($url,$data);
      }

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
          $sql="select title,url,vip,shijian,miaoshu,context from $tableData where xid={$parameter['xid']}";
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
          $sql="select xid,title,keyboard,titlepic,pic,miaoshu from $tableData where id={$parameter['id']} and zid={$parameter['zid']}";
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