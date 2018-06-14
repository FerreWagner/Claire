<?php
namespace app\analysis\controller;

use app\analysis\Common;
use wxkxklmyt\Scws;

class Index extends Common
{
    //function: 1、提取生成词频前n个(var:词汇、数量、词重);
    //function: 1、提取生成词频前n个(var:词汇、数量),使用GD库生成标签云;
    public function index()
    {
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
    

}
