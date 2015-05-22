<?php
@session_start();
require("../../inc/common.inc.php");
if(trim($_SESSION["mxwifishang"]["username"])=="" or trim($_SESSION["mxwifishang"]["userid"])==""){
   echo "<script>window.parent.location='/mxsoft/Login.php';</script>";
   exit();
}

/**
 * Function: 获取远程图片并把它保存到本地
 * 
 * 本PHP程序是演示程序，建议不要直接在实际项目中使用。
 * 如果您确定直接使用本程序，使用之前请仔细确认相关安全设置。
 * 确定您有把文件写入本地服务器的权限
 */
// 权限认证


//文件保存目录路径
$imgPath = '../../upload_files/'.date('Ym').'/';
//文件保存目录URL
$imgUrl = str_replace('Public/Js/kindeditor/php/','','/upload_files/'.date('Ym').'/');
//alert(str_replace('Public/Js/kindeditor/php/','',dirname($_SERVER['PHP_SELF']) . '/Uploads/'));
if((isset($_POST['str']))&&(!empty($_POST['str'])))
{
//$body = stripslashes($saveremoteimg);
$body=$_POST['str'];
$img_array = array();
preg_match_all("/(src|SRC)=[\"|'| ]{0,}(http:\/\/(.*)\.(gif|jpg|jpeg|bmp|png))/isU",$body,$img_array);
$img_array = array_unique($img_array[2]);
set_time_limit(0);
$milliSecond = date("dHis") . '_';
if(!is_dir($imgPath)) @mkdir($imgPath,0777);
foreach($img_array as $key =>$value)
{
	$value = trim($value);
	$get_file = @file_get_contents($value);
	$rndFileName = $imgPath.$milliSecond.$key.'.'.substr($value,-3,3);
	$fileurl = $imgUrl.$milliSecond.$key.'.'.substr($value,-3,3);
	if($get_file)
	{
		$fp = @fopen($rndFileName,'w');
		@fwrite($fp,$get_file);
		@fclose($fp);
	}
	$body = ereg_replace($value,$fileurl,$body);
}
//$body = addslashes($body);
echo $body;
}
else
{
	//echo 'no post';
}

?>