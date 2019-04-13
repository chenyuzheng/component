<?php
defined("COME") or exit('非法');
class smarty{
    public $temURL;
    public $comURL;
    public $arr=array();
    public $cache=false;
    public $cacheURL;

    public function setTemplateURL($dirname='template'){
        $this->temURL=$dirname;
        $tem=APP_PATH.'/'.$this->temURL;
        $this->temURL=$tem;
        if(!is_dir($tem)){
            mkdir($tem);
        }
    }

    public function setCompileURL($dirname='compile'){
        $this->comURL=$dirname;
        $tem=APP_PATH.'/'.$this->comURL;
        $this->comURL=$tem;
        if(!is_dir($tem)){
            mkdir($tem);
        }
    }

    public function setCacheURL($dirname='cache'){
        $this->cacheURL=$dirname;
        $tem=APP_PATH.'/'.$this->cacheURL;
        $this->cacheURL=$tem;
        if(!is_dir($tem)){
            mkdir($tem);
        }
    }

    public function assign($index,$val){
        $this->arr[$index]=$val;
    }

    public function display($url){
        $md5URL=md5($url);
        $temFullPath=$this->temURL.'/'.$url;
        $str=file_get_contents($temFullPath);
        $newstr=preg_replace('/\{([^\}\s]+)\}/','<?php echo $this->arr["$1"]?>',$str);
        $cacheURL=$this->cacheURL.'/'.$md5URL.'.html';
        if(is_file($cacheURL)){
            include $cacheURL;
        }else {
            $comFullPath = $this->comURL . '/' . $md5URL . '.php';
            file_put_contents($comFullPath, $newstr);
            ob_start();
            include $comFullPath;
            if($this->cache) {
                $cachestr = ob_get_contents();
                file_put_contents($cacheURL, $cachestr);
            }
        }
    }
}