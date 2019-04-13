<?php
/* 基类*/
if(!defined('COME')){
    echo '非法访问';
    exit;
}
class main{
    function __construct(){
        $sobj=new Smarty();
        $sobj->setTemplateDir('template');
        $sobj->setCompileDir('compile');
        $this->sobj=$sobj;
    }
}