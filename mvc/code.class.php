<?php
//验证码
if(!defined('COME')){
    echo '非法访问';
    exit;
}
class code{
    public $width=200;
    public $height=40;
    public $type='png';
    public $code='23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
    public $codeLen=4;
    public $codeUrl;
    private $image;
    public $current='';
    public $size=array('min'=>15,'max'=>30);
    public $angle=array('min'=>-15,'max'=>15);
    public $lineNum=10;   //线的数量
    public $pixNum=50;   //杂点的数量

    //创建画布
    private function createGd(){
        $this->image=imagecreatetruecolor($this->width,$this->height);
        imagefill($this->image,0,0,$this->color());
    }
    //创建内容
    private function createCon(){
        $this->text();
        for($i=0;$i<$this->codeLen;$i++){
            $size=mt_rand($this->size['min'],$this->size['max']);
            $angle=mt_rand($this->angle['min'],$this->angle['max']);
            $x=$i*($this->width/$this->codeLen)+mt_rand(-10,10);
            if($i==0){
                $x=$x<0?0:$x;
            }
            $box=imagettfbbox($size,$angle,$this->codeUrl,$this->current[$i]);
            $height=$box[1]-$box[5];
            $y=$box[1]-$box[5]+mt_rand(-10,20);
            $y=$y<$height?$height:$y;
            imagettftext($this->image,$size,$angle,$x,$y,$this->color('smile'),$this->codeUrl,$this->current[$i]);
        }
    }
    //创建文字
    private function text(){
        $str='';
        for($i=0;$i<$this->codeLen;$i++){
            $num=mt_rand(0,strlen($this->code)-1);
            $str.=$this->code[$num];
        }
        $this->current=$str;
        return $str;
    }
    //创建线条
    private function createLine(){
        for($i=0;$i<$this->lineNum;$i++){
            imageline($this->image,mt_rand(0,$this->width/2),mt_rand(0,$this->height),mt_rand($this->width/2,$this->width),mt_rand(0,$this->height),$this->color('back'));
        }
    }
    //创建点
    private function createPix(){
        for($i=0;$i<$this->pixNum;$i++){
            imagesetpixel($this->image,mt_rand(0,$this->width),mt_rand(0,$this->height),$this->color('jshd'));
        }
    }
    //颜色
    private function color($type='back'){
        $r=$type=='back'?mt_rand(0,120):mt_rand(125,255);
        $g=$type=='back'?mt_rand(0,120):mt_rand(125,255);
        $b=$type=='back'?mt_rand(0,120):mt_rand(125,255);
        return imagecolorallocate($this->image,$r,$g,$b);
    }
    //创建并输出图像
    public function output(){
        header('content-type:image/'.$this->type);
        $this->createGd();
        $this->createCon();
        $this->createLine();
        $this->createPix();
        $type='image'.$this->type;
        $_SESSION['code']=strtolower($this->current);
        $type($this->image);
        imagedestroy($this->image);
    }
}