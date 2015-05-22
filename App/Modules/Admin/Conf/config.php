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
    'SESSION_AUTO_START'        =>  true,
    'USER_AUTH_KEY'             =>  'authId',	// 用户认证SESSION标记
    'ADMIN_AUTH_KEY'			=>  'administrator',
    'AUTH_PWD_ENCODER'          =>  'md5',	// 用户认证密码加密方式
    'DB_LIKE_FIELDS'            =>  'title|remark',

    'USER_AUTH_GATEWAY' =>'Public/login',// 默认认证网关
    'NOT_AUTH_MODULE'   =>'Public',	// 默认无需认证模块
    'REQUIRE_AUTH_MODULE'       =>'',		// 默认需要认证模块
    'NOT_AUTH_ACTION'           =>'',		// 默认无需认证操作
    'REQUIRE_AUTH_ACTION'       =>'',		// 默认需要认证操作
    'GUEST_AUTH_ON'             =>false,    // 是否开启游客授权访问
    'GUEST_AUTH_ID'             =>0,        // 游客的用户ID
    //'DB_LIKE_FIELDS'            =>'title|remark',
    'RBAC_ROLE_TABLE'           =>'role',
    'RBAC_USER_TABLE'           =>'admin',
    'RBAC_ACCESS_TABLE'         =>'access',
    'RBAC_NODE_TABLE'           =>'node',
    'PRE'                        =>'mx_',
    'EMPTY_MODULE'		=>	'Department,Role,Broadband,Broadband_cat,Passport,Parameters,Log,',	//允许通过EmptyAction处理的模块，注意以,结尾
    'EMPTY_ACTION'		=>	'index,add,edit,save,delete,refer,view,operate,',//允许通过EmptyAction处理的Action，注意以,结尾

    'APP_AUTOLOAD_PATH'=>'@.TagLib',
    'TMPL_ACTION_ERROR' => 'Public:error',      //默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => 'Public:success',  //默认成功跳转对应的模板文件
    'TMPL_ACTION_ERRORLOGIN' => 'Public:errorLogin',  //默认成功跳转对应的模板文件
    'VAR_PAGE' => 'p',
    'TMPL_PARSE_STRING'=>array(
        '__PUBLIC__'=>__ROOT__.'/'.APP_NAME.'/Tpl/Public',
        '__UPLOADS__'=>__ROOT__.'/Uploads',

        '__PUBLIC_PATH__' =>__ROOT__.'/Public', //CDN JS

        '__JS_PATH__' => __ROOT__.'/Public/js', //CDN JS
        '__CSS_PATH__' => __ROOT__.'/Public/css', //CDN CSS

        '__JS_ADMIN_PATH__' => __ROOT__.'/Public/Admin/js', //CDN JS
        '__CSS_ADMIN_PATH__' =>__ROOT__.'/Public/Admin/css', //CDN CSS
        '__IMG_ADMIN_PATH__' => __ROOT__.'/Public/Admin/images', //CDN img

        '__IMG_PATH__' => __ROOT__.'statics/images', //CDN img
        '__APP_PATH__' => __ROOT__,//动态域名配置地址
    ),
);
$all_config = array_merge($comm_config,$home_config);

return $all_config;