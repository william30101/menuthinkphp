<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-7-15
 * Time: 上午11:27
 * To change this template use File | Settings | File Templates.
 */



class Page_baidu{
    /**
     *int总页数
     **/
    protected $pageTotal;
    /**
     *int上一页
     **/
    protected $previous;
    /**
     *int下一页
     **/
    protected $next;
    /**
     *int中间页起始序号
     **/
    protected $startPage;
    /**
     *int中间页终止序号
     **/
    protected $endPage;
    /**
     *int记录总数
     **/
    protected $recorbTotal;
    /**
     *int每页显示记录数
     **/
    public  $pageSize;
    /**
     *int当前显示页
     **/
    protected $currentPage;
    /**
     *string基url地址
     **/
    protected $baseUri;

    public $dis_num;
    /**
     *@returnstring获取基url地址
     */
    public function getBaseUri(){
        return$this->baseUri;
    }

    /**
     *@returnint获取当前显示页
     */
    public function getCurrentPage(){
        return $this->currentPage;
    }

    /**
     *@returnint获取每页显示记录数
     */
    public function getPageSize(){
        return $this->pageSize;
    }

    /**
     *@returnint获取记录总数
     */
    public function getRecorbTotal(){
        return$this->recorbTotal;
    }

    /**
     *@paramstring$baseUri设置基url地址
     */
    public function setBaseUri($baseUri){
        $this->baseUri=$baseUri;
    }

    /**
     *@paramint$currentPage设置当前显示页
     */
    public function setCurrentPage($currentPage){
        $this->currentPage=$currentPage;
    }


    /**
     *@paramint$pageSize设置每页显示记录数
     */
    public function setPageSize($pageSize){
        $this->pageSize=$pageSize;
    }

    /**
     *@paramint$recorbTotal设置获取记录总数
     */
    public function setRecorbTotal($recorbTotal){
        $this->recorbTotal=$recorbTotal;
    }

    /**
     *构造函数
     **/
    public function __construct()
    {
        $this->pageTotal=0;
        $this->previous=0;
        $this->next=0;
        $this->startPage=0;
        $this->endPage=0;

        $this->pageSize=10;
        $this->currentPage=0;
    }

    /**
     *分页算法
     **/
    private function arithmetic(){
        if($this->currentPage<1)
            $this->currentPage=1;

        $this->pageTotal=floor($this->recorbTotal/$this->pageSize)+($this->recorbTotal%$this->pageSize>0?1:0);

        if($this->currentPage>1&&$this->currentPage>$this->pageTotal)
            header('location:'.$this->baseUri.'p='.$this->pageTotal);

        $this->next=$this->currentPage+1;
        $this->previous=$this->currentPage-1;

        $this->startPage=($this->currentPage+5)>$this->pageTotal?$this->pageTotal-10:$this->currentPage-5;
        $this->endPage=$this->currentPage<5?11:$this->currentPage+5;

        if($this->startPage<1)
            $this->startPage=1;

        if($this->pageTotal<$this->endPage)
            $this->endPage=$this->pageTotal;
    }

    /**
     *分页样式
     **/


    protected function pageStyle(){
       // $result="共".$this->pageTotal."页";
        $result='';
        if($this->currentPage!=$this->pageTotal){
            $result.="<li><a href=\"javascript:startRequest('get','".$this->baseUri."p=".$this->next."','', 'giftmall_ly');\">下一页</a></li>";
            // $result.="<a href=\"".$this->baseUri."page=$this->pageTotal\"><font style=\"font-family:webdings\">最后1页</font></a>";
        }else{
            // $result.="<font style=\"font-family:webdings\">最后1页</font> <font style=\"font-family:webdings\"></font>";
        }



        //for($i=$this->startPage;$i<=$this->endPage;$i++){
        for($i=$this->endPage;$i>=$this->startPage;$i--){
            if($this->currentPage==$i)
            {
                $result.='<li class="on"><a href="#">'.$i.'</a></li>';
            }
            else
            {
                $result.="<li><a href=\"javascript:startRequest('get','".$this->baseUri."p=".$i."','', 'giftmall_ly');\">".$i."</a></li>";
            }

        }
        if($this->currentPage>1)
        {
            $result.="<li><a href=\"javascript:startRequest('get','".$this->baseUri."p=".$this->previous."','', 'giftmall_ly');\"><上一页</a></li>";
        }
        else
        {
            //$result.="<font style=\"font-family:webdings\">第1页</font> <font style=\"font-family:webdings\"></font>";
        }

        return $result;
    }

    protected function pageStyle360(){
        // $result="共".$this->pageTotal."页";
        $result='<fieldset> <legend>分页</legend>';


        if($this->currentPage>1)
        {
            $result.='<a href="'.$this->baseUri.'"p/'.$this->previous.'" class="n"><上一页</a>';
        }

        else
        {
            $result.="<a href=\"javascript:void(0);\" class=\"prev page-blank\"><em>«上一页</em></a>";
        }


        for($i=$this->startPage;$i<=$this->endPage;$i++){
            if($this->currentPage==$i)
                $result.='<span class="cur"><em>'.$i.'</em></span>';
            else
                $result.='<a href="'.$this->baseUri.'p/'.$i.'"><em>'.$i.'</em></a>';
        }

        if($this->currentPage!=$this->pageTotal){

            $result.='<a href="'.$this->baseUri."p/".$this->next.'" class="next"><em>下一页»</em></a>';
        }else{
            $result.='<a href="javascript:void(0);" class="next page-blank"><em>下一页»</em></a>';
        }              
        return $result;
    }
    
    protected function pageStylewap(){
        // $result="共".$this->pageTotal."页";
        $result='';


        if($this->currentPage>1)
        {
            $result.='<a href="'.$this->baseUri.'"&p='.$this->previous.'">上一页</a>';
        }

        

       

        if($this->currentPage!=$this->pageTotal){

            $result.='<a href="'.$this->baseUri."&p=".$this->next.'" ><em>下一页</em></a>';
        }   
        
        $this->currentPage;
         $result.=' <b>'.$this->currentPage.'</b>/'.$this->dis_num;//13958         
        return $result;
    }
    
    /**
     *执行分页
     **/
    public function execute($type='baidu'){
        if($this->baseUri!=""&&$this->recorbTotal==0)
            return"";
        $this->arithmetic();
        if($type=='baidu')
        {
            $str =  $this->pageStyle();
        }
        elseif($type=='360')
        {
            $str =  $this->pageStyle360();
        }    
        elseif($type=='wap')
        {
            $str =  $this->pageStylewap();
        }
        return $str;
    }
}
?>