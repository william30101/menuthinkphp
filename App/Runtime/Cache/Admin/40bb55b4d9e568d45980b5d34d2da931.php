<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=7" />
    <title><?php echo L('message_tips');?></title>
    <style type="text/css">
        <!--
        *{ padding:0; margin:0; font-size:12px}
        a:link,a:visited{text-decoration:none;color:#692700}
        a:hover,a:active{color:#ff6600;text-decoration: underline}
        .showMsg{border: 1px solid #DCA581; zoom:1; width:450px; height:172px;position:absolute;top:44%;left:50%;margin:-87px 0 0 -225px}
        .showMsg h5{background-image: url(<?php echo __IMG_ADMIN_PATH__?>/msg_img/msg.png);background-repeat: no-repeat; color:#fff; padding-left:35px; height:25px; line-height:26px;*line-height:28px; overflow:hidden; font-size:14px; text-align:left}
        .showMsg .content{ padding:46px 12px 10px 45px; font-size:14px; height:64px; text-align:left}
        .showMsg .bottom{ background:#D2A56A; margin: 0 1px 1px 1px;line-height:26px; *line-height:30px; height:26px; text-align:center}
        .showMsg .ok,.showMsg .guery{background: url(<?php echo __IMG_ADMIN_PATH__?>/msg_img/msg_bg.png) no-repeat 0px -560px;}
        .showMsg .guery{background-position: left -1230px;}
        -->
    </style>
</head>
<body>
<div class="showMsg" style="text-align:center">
    <h5>信息提示</h5>
    <div class="content guery" style="display:inline-block;display:-moz-inline-stack;zoom:1;*display:inline;max-width:330px"><?php echo $message?></div>
    <div class="bottom">
        <?php if($jumpUrl=='goback' || $jumpUrl=='') {?>
        <a href="javascript:history.back();" >[<?php echo '返回上一页';?>]</a>
        <?php } elseif($jumpUrl=="close") {?>
        <input type="button" name="close" value="<?php echo '关闭';?> " onClick="window.close();">
        <?php } elseif($jumpUrl=="blank") {?>

        <?php } elseif($jumpUrl) { ?>
        <a href="javascript:parent.window.location.href='<?php echo $jumpUrl?>';">点击登录</a>
        <script language="javascript">setTimeout("parent.window.location.href='<?php echo $jumpUrl?>';",<?php echo $waitSecond;?>);</script>
        <?php }?>
        <?php if($returnjs) { ?> <script style="text/javascript"><?php echo $returnjs;?></script><?php } ?>
        <!--parent.fancyboxClose(); -->
        <!--<?php if ($dialog):?><script style="text/javascript">window.top.right.location.reload();window.top.art.dialog({id:"<?php echo $dialog?>"}).close();</script><?php endif;?>-->
        <?php if ($dialog):?><script style="text/javascript">window.top.right.location.reload();parent.fancyboxClose();</script><?php endif;?>
    </div>
</div>
</body>
</html>