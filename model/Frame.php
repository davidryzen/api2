<?php
/**
 * Created by PhpStorm.
 * User: daday
 * Date: 2017/11/6
 * Time: 9:59
 */
//接受用户的请求
//$userRequestData=$_POST
//var_dump($_SERVER);die;
class Frame{

    public static function run(){
        // echo "hello!!";
        self::init();
        //调用注册方法
        self::autoLoad();
        //调用路由方法
        self::index();
        // echo getcwd();
    }

    //初始化数据方法
    private static function init(){
        //载入配置文件
        $GLOBALS['config'] = include "/dl/API/schedule2/model/config.php";
        //引入数据库类
        include_once "/dl/API/schedule2/model/Model.class.php";
        include_once "/dl/API/schedule2/model/Mysql.class.php";
    }
    private static  function index(){
//得到的请求的方法
        $function=$_POST['function'];
        $token=$_POST['token'];
//得到请求的数据表
//$table=$_POST['mysqlTable'];
        $parameter=$_POST;
//得到接口对象
        $apiObj=new APIModel($token);
//调用方法,得到数据
        $result=$apiObj->$function($parameter);
//       返回数据
        echo $result;



}

  //自动加载类
//自定义自动注册类
 private static function autoload(){
    spl_autoload_register('self::load');
}

//实现控制器和模型的自动加载
 private static function load($className){
    if (substr($className,-10)=="Controller") {
        // var_dump(CUR_CONTROLLER_PATH.$className.".class.php");die;
        //载入控制器类
        include $className.".php";
    }else if(substr($className,-5)=="Model"){
        //载入模型类
        include "/dl/API/schedule2/model/".$className.".php";
    }else{
        //以后有需要添加
    }


}
    private static function decodeUnicode($str)
    {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
            create_function(
                '$matches',
                'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
            ),
            $str);
    }
    //返回成功的数据给用户的方法
    private static function returnMessage($data,$info,$code){
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