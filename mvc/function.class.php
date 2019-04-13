<?php
if(!defined('COME')){
    echo '非法访问';
    exit;
}
function P($param){
    return $_POST[$param];
}
function G($param){
    return $_GET[$param];
}
function sql($param){
    return addslashes(htmlspecialchars($param));
}
function tree($pid=0,&$arr){
    $db=new db();
    $sql="select * from category where pid=".$pid;
    $result=$db->db->query($sql);
    $arr=array();
    $i=0;
    while($row=$result->fetch_assoc()){
        $arr[$i]['id']=$row['cid'];
        $arr[$i]['text']=$row['cname'];
        tree($row["cid"],$arr[$i]['children']);
        $i++;
    }
}