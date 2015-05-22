<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-9-10
 * Time: 下午3:46
 * To change this template use File | Settings | File Templates.
 */
class PartnerData {//类定义开始
    public $name='';

    public function __construct()
    {

    }

    public function infoflag_list()
    {
        $infoflag_list =array('0'=>'待审核','1'=>'通过审核');
        return $infoflag_list;
    }

    /**
     * author Tony
     * 2013-10-06
     * 信息类型
     */
    public function infotype_list()
    {
        $infotype_list =array('1'=>'会员','2'=>'中介');
        return $infotype_list;
    }
    /**
     * author Tony
     * 2013-09-27
     * 得到征友状态的列表
     * @return array
     */
    public function lovestatus_list()
    {
        $user_type_list[1] =array('id'=>'1','name'=>'征友进行中','remark'=>'如果30天内有登录则资料能够被搜出，并且可以收到异性应征信件');
        $user_type_list[2] =array('id'=>'2','name'=>'正在约会中','remark'=>'资料不再被搜出');
        $user_type_list[3] =array('id'=>'3','name'=>'找到意中人','remark'=>'资料不再被搜出');
        return $user_type_list;
    }

    /**
     * author Tony
     * 2013-10-07
     * 返回状态的一维数组
     * @return array
     */
    public function lovestatus_array()
    {
        $list = $this->lovestatus_list();
        $re_array=array();
        if(is_array($list) && count($list)>0)
        {
            foreach($list as $key =>$val)
            {
                $re_array[$val['id']]=$val['name'];
            }
        }
        return $re_array;
    }
    /**
     * author Tony
     * 2013-09-27
     * 得到一个状态的名字
     * @param $id
     * @return string
     */
    public function get_lovestatus_name($id)
    {
        $get_list = $this->lovestatus_list();
        $return_name = '';
        if(is_array($get_list) && count($get_list)>0)
        {
            foreach($get_list as $key=>$val)
            {
                if($id==$key)
                {
                    $return_name = $val['name'];
                    break;
                }
            }
        }
        unset($get_list);
        return $return_name;
    }

    /**
     * author Tony
     * 2013-10-07
     * 统计计算认证的星级
     */
    public function legalize_star_count($lagalize_array=array())
    {
        $return_star=0;
        if(is_array($lagalize_array) && count($lagalize_array)>0)
        {
            foreach($lagalize_array as $key =>$val)
            {
                if(in_array($key,array('idnumberrz','videorz','heightrz','marryrz','incomerz','educationrz','houserz','carrz')) && $val==1)
                {
                    $return_star =$return_star+1;
                }
            }
        }
        return $return_star;
    }
}