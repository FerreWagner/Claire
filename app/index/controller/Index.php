<?php
namespace app\index\controller;

use app\index\Common;
use think\Request;

class Index extends Common
{
    public function _initialize()
    {
        parent::_initialize();
        $cate   = db('category')->field('id, catename')->order('sort', 'desc')->select();
        $sys    = db('system')->find(1);
        
        $this->view->assign([
            'cate'   => $cate,
            'sys'    => $sys,
        ]);
    }
    
    public function index()
    {
        $result = db('article')->field('id, title, see, thumb')->order('time', 'desc')->group('title')->paginate(10);
        if (input('cateid')){
            $result = db('article')->field('id, title, see, thumb')->order('time', 'desc')->group('title')->where('cate', input('cateid'))->paginate(10);
        }
        
        $this->view->assign([
            'result' => $result,
        ]);
        
        return $this->view->fetch('index');
    }
    
    public function search(Request $request)
    {
        if ($request->isPost()){
            $keyword = $request->param('search');
            $result  = db('article')->field('id, title, thumb, see')->group('title', 'desc')->where('title', 'like', '%'.$keyword.'%')->paginate(10);
            return $this->view->fetch('index/index', ['result' => $result]);
        }
    }

    /**
     * 单页处理
     * @return string
     */
    public function single()
    {
        $id = input('id');
        if (!is_numeric($id) || $id <= 0) $id = 1;
        db('article')->where('id', $id)->setInc('see');

        if (!empty($id)){
            $data = db('article')->field('id, thumb, title, time')->find($id);
            if (empty($data)) $data = db('article')->field('id, thumb, title, time')->find(1);
            
            //页面初始化
            $next = db('article')->field('id')->find($id + 1);
            $prev = db('article')->field('id')->find($id - 1);
            
            if (empty($next)) $next = $data;
            if (empty($prev)) $prev = $data;
            
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
