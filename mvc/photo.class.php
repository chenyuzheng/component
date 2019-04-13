<?php
if(!defined('COME')){
    echo '非法访问';
    exit;
}
class upload{
    public $size;
    public $type="image/jpeg;image/png;image/gif";
    public  $filename='file';
    private $data;
    private $fullpath;
    public $uproot="upload";
    private $time;
    public function __construct(){
        $this->size=1024*1024*7;
    }
    private function jieshou(){
        $this->data=$_FILES[$this->filename];
    }
    private function check(){
        if(is_uploaded_file($this->data['tmp_name'])){
            if($this->data['size']<$this->size && strrchr($this->type,$this->data['type'])){
                return true;
            }
            return false;
        }
    }
    private function dir(){
        $this->time=date('y-m-d');
        if(!is_dir($this->uproot.'/'.$this->time)){
            mkdir($this->uproot.'/'.$this->time,0777,true);
        }
        $now=time().mt_rand(0,10000).urlencode($this->data['name']);
        $this->fullpath=$this->uproot.'/'.$this->time.'/'.$now;

    }
    private function create(){
        move_uploaded_file($this->data['tmp_name'],$this->fullpath);
    }
    public function move(){
        $this->jieshou();
        if($this->check()){
            $this->dir();
            $this->create();
            echo $this->fullpath;
        }else{
            echo 'error';
        }
    }
}
