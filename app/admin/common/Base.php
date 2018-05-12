<?php
namespace app\admin\common;

use think\Controller;
use think\Request;
use app\admin\common\Mail;
use app\admin\Common;
use QL\QueryList;


class Base extends Controller
{
    public function _initialize()
    {
        set_time_limit(0);
        //test querylist
//         $rules = ['img' => ['img', 'src']];
//         $img_d = QueryList::Query('http://www.shuaigetu.net/', $rules);
//         $img_d = $img_d->data;
//         $fake = [];
//         foreach ($img_d as $k => $v){
//             $url = 'http://www.shuaigetu.net'.$v['img'];
//             $this->getimg($url, 'sg');
//             ob_flush();
//         }
//         die;

        parent::_initialize();
        
        //detail login
        $request = Request::instance();
        $action  = $request->controller().'/'.$request->action();
        //permission detail
        if (session('user_data')['role'] == config('role.role_normal') && in_array($action, config('action'))){
            $this->error('Sorry,You are not allowed to do this.', 'index/welcome');
        }
        
        if ($request->module() == 'admin' && $request->controller() != 'Login'){    //except Login action,all admin function need validate
            $this->isLogin();
        }
        
        if ($request->module() == 'admin' && in_array($action, config('mail_action'))){
            //TODO
            $this->mailServe(config('mail.root'), Common::mailFeedback($action));    //返回消息体
        }
        
        
    }
    
    /**
     * 邮件服务
     */
    public function mailServe($email, $content)
    {
        if (Mail::isMail() == config('mail.close')) return true;
        
//         $user_email = session('user_data')['email'];

        $mail = new Mail();
        $mail->getXml('admin');
        $mail->init();
        $mail->content($content);
        $mail->replay($email);
        
        if (!$mail->send()){
            $this->error('Mail Server Error.');
        }
    
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
        $fp = @ fopen($filepath.'/'.$filename, 'a');
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

    
    protected function isLogin()
    {
        //use helper function to validate
        if (empty(\session('user_name'))){
            $this->error('Pls Login First.Dear.', 'admin/login/index');
        }
    }
    

    protected function alreadyLogin()
    {
        if (!empty(\session('user_name'))){
            $this->error('You Already Login.Dear', 'admin/index/index');
        }
    }
    
    
    /**
     * 计算当前月份有几天,返回days
     * @param unknown $year
     * @param unknown $mouth
     * @return string
     */
    protected function dateDetail($year, $mouth)
    {
        //两种方式：cal_days_in_month(CAL_GREGORIAN,1,2017);
        return date('t', strtotime(''.$year.'-'.$mouth.''));
    
    }
    
    
    /**
     * date()方式取当前月份,当0-9月时出现09，去除该显示方式
     * @param unknown $mouth
     * @return string
     */
    protected function mouthDetail($mouth)
    {
        if (substr($mouth, 0, 1) == 0){
            $mouth = substr($mouth, 1, 2);
        }
        return $mouth;
    }
    

}
