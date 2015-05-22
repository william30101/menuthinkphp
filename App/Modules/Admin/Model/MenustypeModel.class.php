<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-3-11
 * Time: 下午1:23
 */
class MenustypeModel extends CommonModel{
    public $_validate=array(
        array('name','require','分类标题填写'),
    );
}