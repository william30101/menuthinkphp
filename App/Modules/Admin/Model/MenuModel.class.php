<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-3-11
 * Time: 下午1:52
 */
class MenuModel extends CommonModel{
    public $_validate=array(
        array('name','','菜谱中已经有这个菜了',0,'unique',1),
        array('select','number','必须选择分类'),
        array('price','currency','价格必须是数字的格式'),
        array('unit','/^[\x{4e00}-\x{9fa5}]+$/u','单位请使用汉字'),
        array('content','require','内容不能空')
    );
}