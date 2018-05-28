<?php
namespace app\index\controller;

use app\index\Common;

class Index extends Common
{
    public function index()
    {
        $result = db('article')->field('id, title, cate, see, thumb')->paginate(7);
        $this->view->assign([
            'result' => $result,
        ]);
        return $this->view->fetch('index');
    }

    public function single()
    {
        if (!empty(input('id'))){
            $data = db('article')->field('thumb, title')->find(input('id'));
            $this->view->assign([
                'data' => $data,
            ]);
            return $this->view->fetch('index/single-page');
        }else{
            return $this->view->fetch('index');
        }

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
