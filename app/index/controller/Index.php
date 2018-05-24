<?php
namespace app\index\controller;

use app\index\Common;

class Index extends Common
{
    public function index()
    {
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
    
    
}
