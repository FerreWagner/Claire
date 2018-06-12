<?php
namespace app\analysis\controller;

use app\analysis\Common;

class Index extends Common
{
    public function index()
    {
        return $this->view->fetch('index');
    }

}
