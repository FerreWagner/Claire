<?php
namespace app\index\controller;

use app\index\Common;

class Index extends Common
{
    public function index()
    {
//         echo $_SERVER['HTTP_REFERER'];die;
        set_time_limit(0);
        $result = db('article')->field('id, title, cate, see, pic')->select();
        $this->view->assign([
            'result' => $result,
        ]);
        foreach ($result as $_v){
            $this->getimg($_v['pic'], 'ferre');
            ob_flush();
            flush();
        }
        die;
        return $this->view->fetch('index');
    }
    
    
    public function service()
    {
        return $this->view->fetch('index/services');
    }
    public function about()
    {
        return $this->view->fetch('index/about');
    }
    public function contact()
    {
        return $this->view->fetch('index/contact');
    }
    
    
    public function getimg($url, $filepath) {
        if ($url == '') {
            return false;
        }
        $ext = strrchr($url, '.');
        //     echo $ext;die;
        if ($ext != '.gif' && $ext != '.jpg') {
            return false;
        }
        //判断路经是否存在
        !is_dir($filepath) ? mkdir($filepath) : null;
        //获得随机的图片名，并加上后辍名
        $filetime = time();
        $filename = date("Y-m-d-H-i-s", $filetime).'-'.rand(100,999).'.'.substr($url,-3,3);
        //读取图片
        $img = $this->fetch_url_page_contents($url);
        //指定打开的文件
        $fp = @fopen($filepath.'/'.$filename, 'a');
        //写入图片到指定的文本
        fwrite($fp, $img);
        fclose($fp);
        return '/'.$filepath.'/'.$filename;
    }
    
    public function fetch_url_page_contents($url){
        $ch = curl_init();
        if (is_numeric(strpos($url, 'https'))){
            //HTTPS crawl
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_REFERER, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        }else{
            curl_setopt ($ch, CURLOPT_URL, $url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_TIMEOUT, 1000);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    
}
