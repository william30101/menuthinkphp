<?php
@session_start();
require("../../inc/common.inc.php");
if(trim($_SESSION["mxwifishang"]["username"])=="" or trim($_SESSION["mxwifishang"]["userid"])==""){
   echo "<script>window.parent.location='/mxsoft/Login.php';</script>";
   exit();
}

/**
 * Function: ��ȡԶ��ͼƬ���������浽����
 * 
 * ��PHP��������ʾ���򣬽��鲻Ҫֱ����ʵ����Ŀ��ʹ�á�
 * �����ȷ��ֱ��ʹ�ñ�����ʹ��֮ǰ����ϸȷ����ذ�ȫ���á�
 * ȷ�����а��ļ�д�뱾�ط�������Ȩ��
 */
// Ȩ����֤


//�ļ�����Ŀ¼·��
$imgPath = '../../upload_files/'.date('Ym').'/';
//�ļ�����Ŀ¼URL
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