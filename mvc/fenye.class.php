<?php
if(!defined('COME')){
    echo '非法访问';
    exit;
}
class fenye{
    public $num=3;
    public $total;
    public $pageNum;
    public $limit;
    function show(){
        $this->pageNum=ceil($this->total/$this->num);
        if(!defined('HTTP')){
            define('HTTP',strtolower(strchr($_SERVER['SERVER_PROTOCOL'],'/',true)));

            define('HOST',$_SERVER['HTTP_HOST']);

            define('APP_URL',substr($_SERVER['SCRIPT_NAME'],0,strrpos($_SERVER['SCRIPT_NAME'],'/')));

        }
        define('PAGE_URL',HTTP.'://'.HOST.$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING']);
        $oriURL=PAGE_URL;
        if(!strrpos($oriURL,'page=')){
            $oriURL.='&page=0';
        }
        $fullURL=substr($oriURL,0,strrpos($oriURL,'&page'));
        $page=substr($oriURL,strrpos($oriURL,'=')+1);
        $str='';
        $str.="<a href='{$fullURL}&page=0'>[首页]</a>";
        $up=$page-1<0?0:$page-1;
        $str.="<a href='{$fullURL}&page={$up}'>[上一页]</a>";
        $start=$page-3<0?0:$page-3;
        $pageNum=$this->pageNum;
        $end=$page+6>$pageNum-1?$pageNum-1:$page+6;
        for($i=$start;$i<=$end;$i++){
            $num=$i+1;
            if($i==$page){
                $style="style='color:red'";
            }else{
                $style="style='color:black'";
            }
            $str.="<a href='{$fullURL}&page={$i}' {$style}>[{$num}]</a>";
        }
        $next=$page+1>$pageNum-1?$pageNum-1:$page+1;
        $str.="<a href='{$fullURL}&page={$next}'>[下一页]</a>";
        $last=$pageNum-1;
        $str.="<a href='{$fullURL}&page={$last}'>[尾页]</a>";
        $this->limit=$page*$this->num.', '.$this->num;
        return $str;
    }
}