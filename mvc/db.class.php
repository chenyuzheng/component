<?php
//数据库
if(!defined('COME')){
    echo '非法访问';
    exit;
}
class db{
    private $host;
    private $port;
    private $username;
    private $passwd;
    private $dbname;
    public $db;
    //初始化
    public function __construct($table=''){
        global $configs;
        $this->table=$table;
        $this->host=isset($configs['database']['host'])?$configs['database']['host']:'localhost';
        $this->port=isset($configs['database']['port'])?$configs['database']['port']:3306;
        $this->username=isset($configs['database']['username'])?$configs['database']['username']:'root';
        $this->passwd=isset($configs['database']['passwd'])?$configs['database']['passwd']:'';
        $this->dbname=isset($configs['database']['dbname'])?$configs['database']['dbname']:'extra';
        //查询的内容
        $this->option['flag']='*';
        $this->option['where']=$this->option['order']=$this->option['limit']='';
        $this->mysql();
    }
    //连接数据库
    public function mysql(){
        $this->db=new mysqli($this->host,$this->username,$this->passwd,$this->dbname,$this->port);
        //数据库出错
        if(mysqli_connect_error()){
            echo '数据库连接出错';
            exit;
        }
        $this->db->query('set names utf8');
    }
    //设置表名
    public function setTable($table){
        $this->table=$table;
    }
    //设置查询信息
    public function flag($flag){
        $this->option['flag']=$flag;
        return $this;
    }
    public function where($flag){
        $this->option['where']=' where '.$flag;
        return $this;
    }
    public function order($flag){
        $this->option['order']=' order by '.$flag;
        return $this;
    }
    public function limit($flag){
        $this->option['limit']=' limit '.$flag;
        return $this;
    }
    //查询多条数据
    public function select($sql=''){
        $sql=!empty($sql)?$sql:"select ".$this->option['flag']." from ".$this->table.$this->option['where'].$this->option['order'].$this->option['limit'];
        $result=$this->db->query($sql);
        $arr=$result->fetch_all(MYSQLI_ASSOC);   //MYSQLI_ASSOC:关联数组  MYSQLI_NUM:数字数组  MYSQLI_BOTH：两者兼有
        return $arr;
    }
    //查询单条数据
    public function findOne($sql=''){
        $sql=!empty($sql)?$sql:"select ".$this->option['flag']." from ".$this->table.$this->option['where'].$this->option['order'].$this->option['limit'];
//        echo $sql;
//        exit;
        $result=$this->db->query($sql);
        $arr=$result->fetch_assoc();   //MYSQLI_ASSOC:关联数组  MYSQLI_NUM:数字数组  MYSQLI_BOTH：两者兼有
        return $arr;
    }
    //执行自定义的sql,也可以关联查询
    public function exec($sql){
        $result=$this->db->query($sql);
        return $result;
    }
    //插入
    function insert($arr){
        $attr='';
        $val='';
        foreach ($arr as $key=>$v){
            $attr.=$key.',';
            $val.=$v.',';
        }
        $attr=substr($attr,0,-1);
        $val=substr($val,0,-1);
        $sql="insert into ".$this->table.'('.$attr.')'.' values('.$val.')';
        $this->db->query($sql);
        return $this->db->affected_rows;
    }
    //更新
    function update($str){
        $sql="update ".$this->table.' set '.$str.' '.$this->option['where'];
        $this->db->query($sql);
        return $this->db->affected_rows;
    }
    //删除
    function delete(){
        $sql="delete from ".$this->table.$this->option['where'];
        $this->db->query($sql);
        return $this->db->affected_rows;
    }
}
