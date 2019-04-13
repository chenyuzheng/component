<?php
//路由
if(!defined('COME')){
    echo '非法访问';
    exit;
}
class route{
    //调度   通过地址栏   静态比较快，只能在类里调用  调用：self::变量
    private static $m;   //文件夹
    private static $f;   //文件
    private static $a;   //方法

    private function getInfo(){
        self::$m=isset($_REQUEST['m'])&&!empty($_REQUEST['m'])?$_REQUEST['m']:'index';
        self::$f=isset($_REQUEST['f'])&&!empty($_REQUEST['f'])?$_REQUEST['f']:'index';
        self::$a=isset($_REQUEST['a'])&&!empty($_REQUEST['a'])?$_REQUEST['a']:'init';
    }

    public function set(){
        //先获取参数信息
        $this->getInfo();
        $murl=MOD_PATH.'/'.self::$m;
        if(is_dir($murl)){
            $furl=MOD_PATH.'/'.self::$m.'/'.self::$f.'.class.php';
            if(is_file($furl)){
                include_once $furl;
                if(class_exists(self::$f)){
                    $lei=new self::$f();
                    $method=self::$a;
                    if(method_exists($lei,$method)){
                        $lei->$method();
                    }else{
                        echo self::$a.'无此方法';
                    }
                }else{
                    echo self::$f.'无此类';
                }
            }else{
                echo $furl.'文件没找到';
            }
        }else{
            echo $murl.'文件夹没找到';
        }
    }
}