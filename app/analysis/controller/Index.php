<?php
namespace app\analysis\controller;

use app\analysis\Common;

class Index extends Common
{
    public function index()
    {
        phpinfo();
        $url = 'http://heater.fsociaty.com';
        $article = $this->getWebData($url);
        
        return $this->view->fetch('index');
    }
    

}
