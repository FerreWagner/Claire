<?php
namespace app\analysis\controller;

use app\analysis\Common;
use think\Request;

class Index extends Common
{
    public $font_dir   = 'F:'.DS.'xampp'.DS.'htdocs'.DS.'Claire'.DS.'public'.DS.'font'.DS.'';
    public $img_format = ['jpg', 'jpeg', 'png', 'gif'];
    
    /**
     * index page
     */
    public function index()
    {
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
            $this->formEmptyCheck($form);
            $this->urlFormatCheck($form['url']);
            
            switch ($form['ext'])
            {
                case 1:
                    $ext = 'jpg';
                    break;
                case 2:
                    $ext = 'png';
                    break;
                case 3:
                    $ext = 'gif';
                    break;
                default:
                    $ext = 'jpg';
                    break;
            }
            
            if ($form['font_size'] > 50) $form['font_size'] = 50;
            if ($form['font_size'] < 18) $form['font_size'] = 18;
            if ($form['pic_width'] > 1920 || $form['pic_width'] < 100) $form['pic_width']    = 600;
            if ($form['pic_height'] > 1920 || $form['pic_height'] < 100) $form['pic_height'] = 400;
            
            $time      = is_numeric($form['time']) ? $form['time'] : 40;
            $font_path = $this->font_dir.$form['font_style'];
            if (!file_exists($font_path)) $this->error('该字体不存在');
            if (!in_array($ext, $this->img_format)) $this->error('抱歉，不支持的图片格式');
            
            //分析
            $devid   = $this->analysisWeb($form['url'], $time);
            $words   = [];
            foreach ($devid as $value){
                $words[] = $value['word'];
            }
            
            //处理画布
            $this->picHandle($ext, $font_path, $form['font_size'], $form['pic_width'], $form['pic_height'], $form['bg_color'], $form['text_color'], $words);
            
            
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
    
    //function: 1、提取生成词频前n个(var:词汇),使用GD库生成标签云;TIPS:图片大小；图片背景；文字大小；文字颜色(不统一)；文字字体；文字间距
    public function picHandle($ext, $font_path, $font_size, $pic_width, $pic_height, $bg_color, $text_color, $words)
    {
        //颜色处理/文字间距 TODO
        $color_bg   = explode(',', $bg_color);
        $color_text = explode(',', $text_color);
//         halt($words);die;
        // 定义输出为图像类型
        header("content-type:image/$ext");
        
        // 创建定长宽画布
        $im = imagecreate($pic_width, $pic_height);
        
        // 背景
        imagecolorallocate($im, $color_bg[0], $color_bg[1], $color_bg[2]);
        // 文本颜色
        $text_color = imagecolorallocate($im, $color_text[0], $color_text[1], $color_text[2]);
        //imagestring 默认英文编码，只支持UTF-8
        //imagestring($im, 2, 0, 0, $motto, $text_color);
        
        //当代码文件为:
        //ANSI编码，需要转换
        //UTF-8编码，不需要转换
        //$motto = iconv("gb2312", "utf-8", $motto);
        //image resource,float size,float angle,int x,int y,int color,string fontfile,string text
        $string  = implode(" ", $words);
        $x_drift = 25;
        $y_drift = 50;
        $content = $this->autowrap($font_size, 0, $font_path, $string, $pic_width);
        
        //字体大小 x轴 y轴 分词间隙   TODO
        imageTTFText($im, $font_size, 0, $x_drift, $y_drift, $text_color, $font_path, $content);
        
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
    
    public function autowrap($fontsize, $angle, $fontface, $string, $width) {
    // 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
     $content = "";
    
     // 将字符串拆分成一个个单字 保存到数组 letter 中
     for ($i=0;$i<mb_strlen($string);$i++) {
      $letter[] = mb_substr($string, $i, 1);
     }
    
     foreach ($letter as $l) {
      $teststr = $content." ".$l;
      $testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
      // 判断拼接后的字符串是否超过预设的宽度
      if (($testbox[2] > $width) && ($content !== "")) {
       $content .= "\n";
      }
      $content .= $l;
     }
     return $content;
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
