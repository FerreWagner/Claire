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

    /**
     * 单页处理
     * @return string
     */
    public function single()
    {
        $id = input('id');
        if (!is_numeric($id) || $id <= 0) $id = 1;
        
        if (!empty($id)){
            $data = db('article')->field('id, thumb, title')->find($id);
            if (empty($data)) $data = db('article')->field('id, thumb, title')->find(1);
            
            $next = db('article')->field('id')->find($id + 1);
            $prev = db('article')->field('id')->find($id - 1);
            
            if (empty($next)) $next = $data;
            if (empty($prev)) $prev = $data;
//             halt($prev);
            $this->view->assign([
                'data' => $data,
                'next' => $next,
                'prev' => $prev,
            ]);
            return $this->view->fetch('index/single-page');
        }else{
            return $this->view->fetch('index');
        }

    }
    
    
    public function contact()
    {
        return $this->view->fetch('index/contact');
    }

}
