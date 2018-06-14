<?php
namespace app\analysis\controller;

use app\analysis\Common;
use wxkxklmyt\Scws;

class Index extends Common
{
    //function: 1、提取生成词频前n个(var:词汇、数量、词重);
    public function index()
    {
        $this->pic();
        
        //表单 验证
        $url  = 'http://heater.fsociaty.com';
        $time = is_numeric(20) ? 20 : 40;
        if (!filter_var($url, FILTER_VALIDATE_URL)) $this->error('不是标准的地址');
        
        
        
        //输出
        $article = $this->getWebData($url);
        $scws = new Scws();
        $devid = $scws->scws($article, $time, true);
        
        halt($devid);
        return $this->view->fetch('index');
    }
    
    //function: 1、提取生成词频前n个(var:词汇),使用GD库生成标签云;TIPS:图片大小；图片背景；文字大小；文字颜色(不统一)；文字字体；文字间距
    public function pic()
    {
        //表单数据提取
        $img_format = ['jpg', 'jpeg', 'png', 'gif'];
        $ext        = 'jpg';
        $font_size  = 50;    //最大的font size,最小的为18
        $font_sty   = "C:\Users\Administrator\Downloads\Austie Bost Versailles.ttf";
        
        //表单验证 TODO
        if (!in_array($ext, $img_format)) $this->error('抱歉，不支持的图片格式');
        
        //颜色处理/文字间距 TODO
        
        // 定义输出为图像类型
        header("content-type:image/$ext");
        
        // 创建画布,默认600x400
        $im = imagecreate(600, 400);
        // 背景,默认白色
        imagecolorallocate($im, 255, 255, 255);
        
        // 文本颜色
        $text_color = imagecolorallocate($im, 233, 14, 91);
        $motto = "asd";
        //imagestring 默认英文编码，只支持UTF-8
        //imagestring($im, 2, 0, 0, $motto, $text_color);
        
        //当代码文件为:
        //ANSI编码，需要转换
        //UTF-8编码，不需要转换
        //$motto = iconv("gb2312", "utf-8", $motto);
        //image resource,float size,float angle,int x,int y,int color,string fontfile,string text
        imageTTFText($im, 18, 0, 80, 100, $text_color, $font_sty, $motto);
        imageTTFText($im, 40, 0, 10, 140, $text_color, $font_sty, 'I LOVE ALEXA');
        
        switch ($ext)
        {
            case 'jpg' || 'jpeg':
                imagejpeg($im);
            case 'png':
                imagepng($im);
            case 'gif':
                imagegif($im);
            default:
                imagejpeg($im);
        }
        
        imagedestroy($im);die;
    }
    

}
