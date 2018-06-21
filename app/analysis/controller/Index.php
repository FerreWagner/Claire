<?php
namespace app\analysis\controller;

use app\analysis\Common;
use think\Request;

class Index extends Common
{
    /**
     * index page
     */
    public function index()
    {
        return $this->view->fetch('index');
        
        $this->pic();
        return $this->view->fetch('index');
    }
    
    /**
     * 方案1 TODO
     * @param Request $request
     * @return string
     */
    public function programme1(Request $request)
    {
    
        if ($request->isPost()){
            //表单 验证
            $form = $request->param();
            halt($form);
            $this->formEmptyCheck($form);
            
            //数据初始化
            $time = is_numeric($form['time']) ? $form['time'] : 40;
            $url  = $form['url'];
            $this->urlFormatCheck($url);
    
            //分析
            $devid   = $this->analysisWeb($url, $time);
            $count   = count($devid);
            $string  = '';
            foreach ($devid as $_k => $_v){
                for ($i = 0; $i < $count; $i ++){
                    $string .= $_v['word'].' ';
                }
                $count --;
            }
            if (empty($devid)) $this->error('该站点存在错误');
            return $this->view->fetch('program1-end', ['string' => $string]);
        }
    
        return $this->view->fetch('program1');
    }
    
    /**
     * 方案2
     * @param Request $request
     * @return string
     */
    public function programme2(Request $request)
    {
        if ($request->isPost()){
            //表单 验证
            $form = $request->param();
            $this->formEmptyCheck($form);
        
            $time = is_numeric($form['time']) ? $form['time'] : 40;   //初始化分词数
            $url  = $form['url'];
            $cate = $form['cate'];
            $this->urlFormatCheck($url);
            
            switch ($cate)
            {
                case 1:
                    $cate = '.txt';
                    break;
                case 2:
                    $cate = '.doc';
                    break;
                case 3:
                    $cate = '.xlsx';
                    break;
                default :
                    $cate = '.txt';
                    break;
            }
            
            //分析
            $devid    = $this->analysisWeb($url, $time);
            if (empty($devid)) $this->error('该站点存在错误');
            $txt_data = [['分词', '次数', '权重']];   //初始化
            
            foreach ($devid as $_k => $_v){
                $txt_data[] = [$_v['word'], $_v['times'], $_v['weight']];
            }
            //生成文档
            $this->dlfileftxt($txt_data, 'ferre_'.time(), $cate); //https://blog.csdn.net/oQiWei1/article/details/62432315
            return $this->view->fetch('program2-end');
        }
        
        return $this->view->fetch('program2');
    }
    
    
    /**
     * 方案3
     * @param Request $request
     * @return string
     */
    public function programme3(Request $request)
    {
        
        if ($request->isPost()){
            //表单 验证
            $form = $request->param();
            $this->formEmptyCheck($form);
            
            //数据初始化
            $time = is_numeric($form['time']) ? $form['time'] : 40;
            $url  = $form['url'];
            $this->urlFormatCheck($url);
            
            //分析
            $devid   = $this->analysisWeb($url, $time);
            $count   = count($devid);
            $string  = '';
            foreach ($devid as $_k => $_v){
                for ($i = 0; $i < $count; $i ++){
                    $string .= $_v['word'].' ';
                }
                $count --;
            }
            if (empty($devid)) $this->error('该站点存在错误');
            return $this->view->fetch('program3-end', ['string' => $string]);
        }
        
        return $this->view->fetch('program3');
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
    
    /**
     * 生成txt权重文档
     * @param array $data
     * @param string $filename
     */
    public function dlfileftxt($data = array(),$filename = "unknown", $cate) {
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-Disposition:attachment;filename=$filename.$cate");
        header("Expires:0");
        header("Cache-Control:must-revalidate,post-check=0,pre-check=0 ");
        header("Pragma:public");
        if (!empty($data)){
            foreach($data as $key=>$val){
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
                }
                $data[$key]=implode("\t\t", $data[$key]);
            }
            echo implode("\r\n",$data);
        }
        exit();
    }
    

}
