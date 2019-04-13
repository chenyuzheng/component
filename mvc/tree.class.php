<?php
class tree{
    public $str='';
    function getTree($pid,$step,$flag,$table,$db){
        $sql="select * from ".$table." where pid=".$pid;
        $result=$db->db->query($sql);
        $step+=1;
        $flags=str_repeat($flag,$step);
        $this->str.='<ul class="cates">';
        while($row=$result->fetch_assoc()){
            $cid=$row['cid'];
            $sqll="select * from ".$table." where pid=".$cid;
            $result1=$db->db->query($sqll);
            if($result1->num_rows>0){
                $this->str.='<li class="parent">'.$row["cname"].'</li>';
            }else{
                $this->str.='<li class="cateli"><a href="index.php?f=essay&a=addCon&cid='.$cid.'" target="right">'.$row["cname"].'</a></li>';
            }
            $this->getTree($cid,$step,$flag,$table,$db);
        };
        $this->str.='</ul>';
    }
}