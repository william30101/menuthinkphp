<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-9-10
 * Time: 下午3:46
 * To change this template use File | Settings | File Templates.
 */
class UserData {//类定义开始
    public $name='';

    public function __construct()
    {

    }

    public function user_group()
    {

    }

    /**
     * author Tony
     * 2013-09-27
     * 得到用户状态的列表
     * @return array
     */
    public function user_type_list()
    {
        $user_type_list = array("1"=>'会员','2'=>"中介");
        return $user_type_list;
    }
    /**
     * author Tony
     * 2013-09-27
     * 得到一个状态的名字
     * @param $id
     * @return string
     */
    public function get_userstype_onename($id)
    {
        $get_list = $this->user_type_list();
        $return_arr = '';
        if(is_array($get_list) && count($get_list)>0)
        {
            foreach($get_list as $key=>$val)
            {
                if($id==$key)
                {
                    $return_arr = $val;
                    break;
                }
            }
        }
        unset($get_list);
        return $return_arr;
    }


    /**
     * author Tony
     * 2013-09-27
     * 得到用户状态的列表
     * @return array
     */
    public function user_status_list()
    {
        $user_status_list = array("-2"=>L("user_status-2"),"-1"=>L("user_status-1"), "0"=>L("user_status0"),"1"=>L("user_status1"));
        return $user_status_list;
    }

    /**
     * author Tony
     * 2013-09-27
     * 得到一个状态的名字
     * @param $id
     * @return string
     */
    public function get_userstatus_one($id)
    {
        $get_list = $this->user_status_list();
        $return_arr = '';
        if(is_array($get_list) && count($get_list)>0)
        {
            foreach($get_list as $key=>$val)
            {
                if($id==$key)
                {
                    $return_arr = $val;
                    break;
                }
            }
        }
        unset($get_list);
        return $return_arr;
    }
}