<?php
namespace app\index\controller;

use app\index\Common;

class Index extends Common
{
    public function index()
    {
        return $this->view->fetch('index');
    }
    
    
}
