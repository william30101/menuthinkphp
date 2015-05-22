<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id: common.php 2601 2012-01-15 04:59:14Z liu21st $


//公共函数
function toDate($time, $format = 'Y-m-d H:i:s') {
	if (empty ( $time )) {
		return '';
	}
	$format = str_replace ( '#', ':', $format );
	return date ($format, $time );
}

function getStatus($status, $imageShow = true) {
	switch ($status) {
		case 0 :
			$showText = '禁用';
			$showImg = '<IMG SRC="__PUBLIC__/Images/locked.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="禁用">';
			break;
		case 2 :
			$showText = '待审';
			$showImg = '<IMG SRC="__PUBLIC__/Images/prected.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="待审">';
			break;
		case - 1 :
			$showText = '删除';
			$showImg = '<IMG SRC="__PUBLIC__/Images/del.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="删除">';
			break;
		case 1 :
		default :
			$showText = '正常';
			$showImg = '<IMG SRC="__PUBLIC__/Images/ok.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="正常">';

	}
	return ($imageShow === true) ?  $showImg  : $showText;

}

function showStatus($status, $id) {
	switch ($status) {
		case 0 :
			$info = '<a href="javascript:resume(' . $id . ')">恢复</a>';
			break;
		case 2 :
			$info = '<a href="javascript:pass(' . $id . ')">批准</a>';
			break;
		case 1 :
			$info = '<a href="javascript:forbid(' . $id . ')">禁用</a>';
			break;
		case - 1 :
			$info = '<a href="javascript:recycle(' . $id . ')">还原</a>';
			break;
	}
	return $info;
}

/**
 * author Tony
 * 2013-09-04
 *
 */
function get_node_nav_link($arr)
{
    $link_str='index.php?';
    if(is_array($arr) && count($arr)>0)
    {
        $link_str .="g=".$arr['g'].'&m='.$arr['m'].'&a='.$arr['a'].$arr['data'];
    }
    else
    {
        return false;
    }
    return $link_str;
}

/**
 * 图片上传
 * @param unknown $thumb
 * @param string $savePath
 * @return Ambigous <multitype:, multitype:unknown >
 * author Tony
 */
function upload($thumb,$savePath='./Public/Uploads/'){
	import('ORG.Net.UploadFile');
	$upload = new UploadFile();// 实例化上传类
	$upload->maxSize  = 3145728 ;// 设置附件上传大小
	$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	$upload->saveRule = uniqid;
	$upload->autoCheck = true; //是否自动检测附件，默认为自动检
	$upload->uploadReplace = true; //存在同名文件是否是覆盖
	$upload->thumbPath = ''; // 缩略图的保存路径，留空的话取文件上传目录本身
	$upload->thumbRemoveOrigin = 0; //生成缩略图后是否删除原图 1删除
	 
	 
	$upload->savePath =  './'.$savePath;// 设置附件上传目录
	$upload->thumb = $thumb;
	$upload->thumbMaxWidth = "50,200";
	$upload->thumbMaxHeight = "50,200";
	$upload->autoSub = true; //是否使用子目录保存上传文件
	$upload->subType = 'date'; // 子目录创建方式，默认为hash，可以设置为hash或者date
	$upload->dateFormat = 'Y-m-d'; //子目录方式为date的时候指定日期格式
	$upload->hashLevel = ''; //子目录保存的层次，默认为一层

	if(!$upload->upload()) {// 上传错误提示错误信息
		$this->error($upload->getErrorMsg());
	}else{// 上传成功 获取上传文件信息
		$info =  $upload->getUploadFileInfo();
		return $info;
	}
}

?>