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
        //定义路径常量
        define("DS", DIRECTORY_SEPARATOR);
        define("ROOT",getcwd().DS);

        //载入配置文件
        $GLOBALS['config'] = include ROOT."model/config.php";

        //引入数据库类
        include_once ROOT."model/Model.class.php";
        include_once ROOT."model/Mysql.class.php";
    }
    private static  function index(){
//        var_dump($_POST);var_dump($_GET);die;
        $act_array=array("getAllAction","getOneAction","getOneScheduleAction","addOneAction","deleteOneAction","updateOneAction","addOneScheduleAction","deleteOneScheduleAction","updateOneScheduleAction","collectFormDataAction","showAllToolsAction","getMerchantInfoAction","addFormFrameAction","updateFormFrameAction","showFormFrameAction","showAllToolsByIdAction","showUseingToolsByIdAction","showExpiredToolsByIdAction","showFormDataFromUserAction","showAllTasksByztAction","addTaskAction","replyByfsAction","replyCommentAction","changeStateAction");

        $controller_array=array("ScheduleController","LittleToolsController","TaskController","AppraisalController");
        if(isset($_POST["act"])){
            $act=$_POST['act'].'Action';
        }else{
            if(isset($_GET['act'])){
                $act=$_GET['act'].'Action';
            }else{
                self::returnMessage(null,"你act参数没有设置",-231);
            }

        }
        if(isset($_POST["type"])&&!empty($_POST["type"])){//如果设置了type且不为空
            $type=$_POST['type'];
        }else{
            if(isset($_GET['type'])){
                $type=$_GET['type'];
            }else{
                self::returnMessage(null,"你type参数没有设置",-232);
            }
        }
   //用户请求的动作,也是请求的dispatche的方法
        if(!empty($act)){


            if(!in_array($act,$act_array)){

                self::returnMessage(null,"你act参数对应的方法不存在",-102);
            }
        }/*else{
            self::returnMessage(null,"没有传action参数",-101);
       } */

        if(!empty($type)){
//         var_dump($type);var_dump($act);die;
            $controller=$type.'Controller';
            if($type=="Schedule"){
                $xingwei="xingcheng";
            }elseif(($type=="LittleTools"&&$act=="showAllToolsAction")||($type=="LittleTools"&&$act=="getMerchantInfoAction")||($type=="LittleTools"&&$act=="addFormFrameAction")||($type=="LittleTools"&&$act=="updateFormFrameAction")||($type=="LittleTools"&&$act=="showFormFrameAction")||($type=="LittleTools"&&$act=="showAllToolsByIdAction")||($type=="LittleTools"&&$act=="showUseingToolsByIdAction")||($type=="LittleTools"&&$act=="showExpiredToolsByIdAction")||($type=="LittleTools"&&$act=="showFormDataFromUserAction")){
                $xingwei="tool";
            }elseif(($type=="Task"&&$act=="showAllTasksByztAction")||($type=="Task"&&$act=="addTaskAction")){
                $xingwei="renwu";
            }elseif(($type=="Appraisal"&&$act=="replyByfsAction")||($type=="Appraisal"&&$act=="replyCommentAction")||($type=="Appraisal"&&$act=="changeStateAction")){
                $xingwei="pinlun";
            }
            if(!in_array($controller,$controller_array)){

                self::returnMessage(null,"你type参数对应的方法不存在",-103);
            }
        }/*else{
            self::returnMessage(null,"没有传type参数",-104);
        }*/

//    var_dump($controller);die;
     //获得分发器类对象
        $dipatcheObj=new $controller("10003","H1dFEDOrd3erkej-erfF30DRFDDASDW3","./token.txt","http://www.you.com/API/schedule2/model/start.php",$xingwei);
//        var_dump($dipatcheObj);die;
        //调用相应的方法
        $dipatcheObj->$act();

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
        include ROOT."model/".$className.".php";
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