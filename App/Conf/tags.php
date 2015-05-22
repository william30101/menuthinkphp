<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-9-26
 * Time: 上午10:11
 * To change this template use File | Settings | File Templates.
 */
return array(//app运行之前，会加载这个配置文件，去调用CheckLang方法。
    'app_begin' => array(//因为项目中也可能用到语言行为,最好放在项目开始的地方
        'CheckLang',	//检测语言
    ),
);
?>