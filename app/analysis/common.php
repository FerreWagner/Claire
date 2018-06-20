<?php
namespace app\analysis;

use think\Controller;
use QL\QueryList;
use wxkxklmyt\Scws;

//公共方法类
class Common extends Controller
{
    /**
     * 返回website文本信息
     * @param unknown $url
     * @param array $rule
     */
    public function getWebData($url, $rule = ['title' => ['', 'text'],])
    {
        $ql       = QueryList::html($this->fetch_url_page_contents($url))->rules($rule);
        $res      = $ql->query()->getData();
        $res_data = ($res->all())[0]['title'];
        $ql->destruct();    //释放资源
        
        $detail_data = str_replace(array("\t\r\n", "\t", "\r", "\n"), "", $res_data);
        return $detail_data;
    }
    
    /**
     * 构造CURL
     * @param unknown $url
     * @return mixed
     */
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
    
    /**
     * 得到分词数据
     * @param unknown $url
     * @param unknown $time
     * @return string
     */
    public function analysisWeb($url, $time)
    {
        $article = $this->getWebData($url);
        $scws = new Scws();
        return $scws->scws($article, $time, true);
    }
    
    /**
     * 表单去空/去空
     * @param unknown $form
     */
    public function formEmptyCheck(&$form)
    {
        //arr
        if (is_array($form)){
            foreach ($form as $key => $val){
                empty($val) ? $this->error('表单数据未填写完整,请重新填写') : $form[$key] = trim($val);
            }
            return;
        }
        //str
        empty($form) ? $this->error('表单数据未填写完整,请重新填写') : $form = trim($form);
    }
    
}