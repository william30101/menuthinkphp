<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

$comm_config=require SYSTEM_PATH.APP_PATH.'/Conf/config.php';
$home_config =  array(
    'TMPL_ACTION_ERROR' => 'Public:error',      //默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => 'Public:success',  //默认成功跳转对应的模板文件
	'URL_MODEL'=>0,
    'URL_HTML_SUFFIX'       => '',
);
$all_config = array_merge($comm_config,$home_config);
return $all_config;