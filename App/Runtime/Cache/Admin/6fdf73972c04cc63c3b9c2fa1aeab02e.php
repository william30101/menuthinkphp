<?php if (!defined('THINK_PATH')) exit();?>﻿<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理登录</title>
<style type="text/css">
<!--
body {margin:0px;padding:0px;
	background-image: url(__IMG_ADMIN_PATH__/bg.gif);
	background-repeat: repeat-x;
}
-->
</style></head>

<body>
<form onSubmit="return CheckForm();" method="post" action="index.php?g=Admin&m=Login&a=index&dosubmit=1" name="Login">
<table width="100%"  height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="561" style="background:url(__IMG_ADMIN_PATH__/lbg.gif)"><table width="940" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td height="238" style="background:url(__IMG_ADMIN_PATH__/login01.jpg)">&nbsp;</td>
          </tr>
          <tr>
            <td height="190"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="253" height="190" style="background:url(__IMG_ADMIN_PATH__/login02.jpg) right 0 no-repeat">&nbsp;</td>
                <td width="430" valign="top" style="background:url(__IMG_ADMIN_PATH__/login03.jpg)"><table width="320" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="40" height="55"><img src="__IMG_ADMIN_PATH__/user.gif" width="30" height="30"></td>
                    <td width="85" height="50">用&nbsp;&nbsp;&nbsp;户：</td>
                    <td width="229" height="50"><input type="text" class="w296" id="username" name="username" style="width:190px; height:32px; line-height:34px; background:url(__IMG_ADMIN_PATH__/inputbg.gif) repeat-x; border:solid 1px #d1d1d1; font-size:9pt; font-family:Verdana, Geneva, sans-serif;"></td>
                  </tr>
                  <tr>
                    <td height="55"><img src="__IMG_ADMIN_PATH__/password.gif" width="28" height="32"></td>
                    <td height="50">密&nbsp;&nbsp;&nbsp;码：</td>
                    <td height="50"><input type="password" id="password" name="password" style="width:190px; height:32px; line-height:34px; background:url(__IMG_ADMIN_PATH__/inputbg.gif) repeat-x; border:solid 1px #d1d1d1; font-size:9pt; "></td>
                  </tr>
				  <?php if($_SESSION['errors'] > 2){?>
          <tr>
		  	<td height="55">
            <td height="50">验证码：</td>
                    <td width="100" height="50"><input type="text" id="CheckCode" name="CheckCode" style="width:80px; height:32px; line-height:34px; background:url(__IMG_ADMIN_PATH__/inputbg.gif) repeat-x; border:solid 1px #d1d1d1; font-size:9pt; font-family:Verdana, Geneva, sans-serif;"><img alt="看不清，换一张" id="imageField" name="imageField" style="margin-left:10px;" onclick='this.src=this.src+"&"+Math.random()' src='__APP_PATH__/index.php?g=Admin&m=Login&a=checkcode&code_len=4&font_size=20&width=130&height=50&font_color=&background=&'+Math.random();">
					<a href="javascript:void(0);" onClick="document.getElementById('imageField').src='__APP_PATH__/index.php?g=Admin&m=Login&a=checkcode&code_len=4&font_size=20&width=130&height=50&font_color=&background=&'+Math.random();">换一张</a></td>
          </tr>
		  <?php }else{ ?>
		  <input type="hidden" id="CheckCode" name="CheckCode" value="1">
		  <?php } ?>
                  <tr>
                    <td height="40">&nbsp;</td>
                    <td height="40">&nbsp;</td>
                    <td height="60"><input type="image" src="__IMG_ADMIN_PATH__/login.gif" name="imageField" width="95" height="34"></td>
                  </tr>
                </table></td>
                <td width="257" style="background:url(__IMG_ADMIN_PATH__/login04.jpg) 0 0 no-repeat;" >&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="133" style="background:url(__IMG_ADMIN_PATH__/login05.jpg)">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</form>
<script language="javascript">
//表单验证	added by Tony
function CheckForm()
{
	if(document.getElementById('username').value=="")
	{
		alert("请输入用户名！");
		document.getElementById('username').focus();
		return false;
	}
	if(document.getElementById('password').value == "")
	{
		alert("请输入密码！");
		document.getElementById('password').focus();
		return false;
	}
/*
	if (document.getElementById('CheckCode').value==""){
       alert ("请输入您的验证码！");
       document.getElementById('CheckCode').focus();
       return(false);*/
    }
}
</script>
</body>
</html>