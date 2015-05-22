<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-9-10
 * Time: 下午3:46
 * To change this template use File | Settings | File Templates.
 */
class OnlineEditor {//类定义开始
    public $name='';
    public $style='';
    public $width='860';
    public $height='420';
    public $id='';
    public $content;
    public $type='ueditor';

    public function  init($attr,$content='',$type='ueditor')
    {
        $this->id			=	!empty($attr['id'])?$attr['id']: '_editor';
        $this->name   	=	!empty($attr['name'])?$attr['name']: 'content';
        $this->style   	    =	!empty($attr['style'])?$attr['style']:'';
        $this->width		=	!empty($attr['width'])?$attr['width']: '860px';
        $this->height     =	!empty($attr['height'])?$attr['height'] :'420px';
        $this->content    =  !empty($content)?$content :'';
        $this->type    =  $type;
    }
    public function  local_editor($attr,$content)
    {
        $type       =   $attr['type'] ;
        switch(strtoupper($type)) {
            case 'ueditor':
                $this->local_ueditor($content);
                break;
        }
    }

    /**
     * author Tony
     * 引用  11
     * @param $type
     * @return string
     */
    public function editor_quote()
    {
        switch($this->type) {
            case 'ueditor':
                $str='
            <script type="text/javascript" charset="utf-8">
                window.UEDITOR_HOME_URL = "__ROOT__/Public/plugins/ueditor/";
                </script>
                <script type="text/javascript" charset="utf-8" src="__ROOT__/Public/plugins/ueditor/ueditor.config.js"></script>
                <script type="text/javascript" charset="utf-8" src="__ROOT__/Public/plugins/ueditor/ueditor.all.js"></script>
                 ';
               break;
            default :

                break;
        }

        return $str;

    }
    public function editor_create()
    {

        switch($this->type) {
            case 'ueditor':
                $str='<script type="text/plain" id="'.$this->id.'" name="'.$this->name.'" style="width:'.$this->width.';height:'.$this->height.'">'.$this->content.'</script>';
                break;
            default :
                $str=  '<textarea id="'.$this->id.'" style="'.$this->style.'" name="'.$this->name.'" >'.$this->content.'</textarea>';
        }
        return $str;
    }
    public  function local_body($type)
    {
        switch($this->type) {
            case 'ueditor':
                $str=$this->local_ueditor();
                break;
            default :

        }
        return $str;
    }
    public function local_ueditor()
    {
        $str ='<script type="text/javascript">
        //实例化编辑器
        var options = {
            imageUrl:UEDITOR_HOME_URL + "../yunserver/yunImageUp.php",
            imagePath:"http://",

            scrawlUrl:UEDITOR_HOME_URL + "../yunserver/yunScrawlUp.php",
            scrawlPath:"http://",

            fileUrl:UEDITOR_HOME_URL + "../yunserver/yunFileUp.php",
            filePath:"http://",

            catcherUrl:UEDITOR_HOME_URL + "php/getRemoteImage.php",
            catcherPath:UEDITOR_HOME_URL + "php/",

            imageManagerUrl:UEDITOR_HOME_URL + "../yunserver/yunImgManage.php",
            imageManagerPath:"http://",

            snapscreenHost:\'ueditor.baidu.com\',
            snapscreenServerUrl:UEDITOR_HOME_URL + "../yunserver/yunSnapImgUp.php",
            snapscreenPath:"http://",

            wordImageUrl:UEDITOR_HOME_URL + "../yunserver/yunImageUp.php",
            wordImagePath:"http://", //

            getMovieUrl:UEDITOR_HOME_URL + "../yunserver/getMovie.php",

            lang:/^zh/.test(navigator.language || navigator.browserLanguage || navigator.userLanguage) ? \'zh-cn\' : \'en\',
            langPath:UEDITOR_HOME_URL + "lang/",

            webAppKey:"9HrmGf2ul4mlyK8ktO2Ziayd",
            initialFrameWidth:'.$this->width.',
            initialFrameHeight:'.$this->height.',
            focus:true
        };
        ';
        $str .="
        var ue = UE.getEditor('".$this->id."', options);
        var domUtils = UE.dom.domUtils;

        ue.addListener(\"ready\", function () {
            ue.focus(true);
        });
        </script>
   ";
        return $str;
    }
}