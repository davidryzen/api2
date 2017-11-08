<?php
/**
 * Created by PhpStorm.
 * User: daday
 * Date: 2017/11/6
 * Time: 10:20
 */
//设置字符集
header("content-type:text/html;charset=utf-8");
//载入核心控制器类
include_once '/dl/API/schedule2/model/Frame.php';
//禁用所有的错误报告
//error_reporting(0);


//运行程序
Frame::run();