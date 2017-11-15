<?php
/**
 * Created by PhpStorm.
 * User: daday
 * Date: 2017/11/10
 * Time: 11:02
 */
class TopController{
    public function __construct($appid,$appkey,$filepath,$confirmUrl,$xingwei){
        //生成token
        $token= $this->getToken($appid,$appkey,$filepath);
        $data="function=confirmToken&token=$token&url=https://www.mijiweb.com/qcloud/OAuth/&xingwei=$xingwei";

        $result=$this->curlPost($confirmUrl,$data);
//        var_dump($data);die($result);die;
        $result=json_decode($result)->msg;

        if($result!="success"){
            $this-> returnMessage(null,"token验证失败或者失效",-10000);
        }



    }

    //得到，新建，储存token
    public function getToken($appid,$appkey,$filepath="./Token.txt"){

        $Toke_life=7200;
//      var_dump(date("H:i:s",filemtime($filepath)));die;
        if(file_exists($filepath)&&filemtime($filepath)>time()-$Toke_life){//保存Token的文件存在，且没有过期

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
    protected  function returnMessage($data,$info,$code){
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
}