<?php 
class Cache_create {
	public function __construct() {

	}
    /**
     * author Tony
     * 2013-09-25
     * 生成敏感词缓存
     */
    public function set_cache_ipbanned($fileName='ipbanned',$data=array())
    {
        $ipbanned_db = new Model('ipbanned');
        if(!is_array($data) || count($data)<=0)
        {
            $data = $ipbanned_db->field('ipbannedid,ip')->ORDER('ipbannedid ASC')->select();
        }
        unset($data,$ipbanned_db);
        setcache($fileName,$data,'setting');
    }

    /**
     * author Tony
     * 2013-09-25
     * 生成敏感词缓存
     */
    public function set_cache_badword($fileName='badword',$data=array())
    {
        $badword_db = new Model('badword');
        if(!is_array($data) || count($data)<=0)
        {
            $data = $badword_db->field('badid,badword,level,replaceword,listorder')->ORDER('badid ASC')->select();
        }
        unset($data,$badword_db);
        setcache($fileName,$data,'setting');
    }
    /**
     * author Tony
     * 2013-09-25
     * 系统缓存批量生成
     */
    public function set_cache_system($data=array())
    {
        $options_db = new Model('options');
        if(!is_array($data) || count($data)<=0)
        {
            $list_data = $options_db->field('id,optionname,optionvalue,optiondesc')->where($condition_list)->ORDER('id ASC')->select();
            if(is_array($list_data) && count($list_data)>0)
            {
                foreach($list_data as $key=>$val)
                {
                    $optionvalue = unserialize($val['optionvalue']);
                    if(is_array($optionvalue) && count($optionvalue)>0)
                    {
                        $val['optionvalue']=$optionvalue;
                    }
                    unset($optionvalue);
                    setcache($val['optionname'],$val,'setting');
                }
            }
        }
        unset($data,$options_db);
    }


    /**
     * author Tony
     * 2013-09-25
     * 增加会用组缓存
     */
    public function set_cache_usergroup($fileName='usergroup',$data=array())
    {
        $usergroup_db = new Model('user_group');
        if(!is_array($data) || count($data)<=0)
        {
            $list_data = $usergroup_db->field('groupid,groupname,orders,showimg,maleimg,femaleimg,intro,regpoints,regmoney,loginpoints
            ,issystem,commersetting,msgsetting,viewsetting,photosetting,friendsetting,publishsetting,feesetting')->ORDER('groupid ASC')->select();

            if(is_array($list_data) && count($list_data)>0)
            {
                foreach($list_data as $key=>$val)
                {
                    if($val['commersetting']!='')
                    {
                        $val['commersetting'] = unserialize($val['commersetting']);
                    }

                    if($val['msgsetting']!='')
                    {
                        $val['msgsetting'] = unserialize($val['msgsetting']);
                    }
                    if($val['viewsetting']!='')
                    {
                        $val['viewsetting'] = unserialize($val['viewsetting']);
                    }
                    if($val['photosetting']!='')
                    {
                        $val['photosetting'] = unserialize($val['photosetting']);
                    }
                    if($val['friendsetting']!='')
                    {
                        $val['friendsetting'] = unserialize($val['friendsetting']);
                    }
                    if($val['publishsetting']!='')
                    {
                        $val['publishsetting'] = unserialize($val['publishsetting']);
                    }
                    if($val['feesetting']!='')
                    {
                        $val['feesetting'] = unserialize($val['feesetting']);
                    }
                    $data[$val['groupid']]=$val;
                }
            }
        }
        setcache($fileName,$data,'setting');
        unset($data,$usergroup_db);
    }

    /**
     * luinfa
     * 2013-09-13
     * 生成地区荤菜缓存
     */
    public function set_cache_area($fileName='area',$data=array())
    {
        $area_db = new Model('area');
        if(!is_array($data) || count($data)<=0)
        {
            $data = $area_db->field('areaid,rootid,depth,areaname,is_child,is_two')->where($condition_list)->ORDER('orders ASC')->select();
            $data = $this->get_area_depth_order_list($data,0,0,0);
        }
        setcache($fileName,$data,'setting');


        //$condition_shandong['rootid'] = array(array('in','10124000,10124003,10124001,10124004,10124014,10124007,10124009,10124008,10124015,10124011,10124002,10124016,10124068,10124013,10124006,10124005,10124018,10124012'),array('areaid',10124000), 'or') ;

        $condition_shandong ="rootid IN(10124000,10124003,10124001,10124004,10124014,10124007,10124009,10124008,10124015,10124011,10124002,10124016,10124068,10124013,10124006,10124005,10124018,10124012) or areaid='10124000'";

        //    array('in','10124000,10124003,10124001,10124004,10124014,10124007,10124009,10124008,10124015,10124011,10124002,10124016,10124068,10124013,10124006,10124005,10124018,10124012');
        $data_shandong = $area_db->field('areaid,rootid,depth,areaname,is_child,is_two')->where($condition_shandong)->ORDER('orders ASC')->select();
        $data_shandong = $this->get_area_depth_order_list($data_shandong,10124000,1,0);
        setcache('shandong',$data_shandong,'setting');

        $condition_jiaxiang['depth'] = array('in','0,1');
        $data = $area_db->field('areaid,rootid,depth,areaname,is_child,is_two')->where($condition_jiaxiang)->ORDER('orders ASC')->select();
        $data = $this->get_area_depth_order_list($data,0,0,0);
        setcache('jiaxiang',$data,'setting');



        unset($data,$area_db);
    }


    /**
     * author Tony
     * 2013-0912
     * 得到地区列表，父子处理好的
     */
    public function get_area_depth_order_list($area_list,$rootid=0,$depth=0,$is_tree=0)
    {
        $array_tree_list=array();
        $areaid = 0;
        foreach($area_list as $key =>$val)
        {
            if($val['depth']==$depth && $val['rootid']==$rootid)
            {
                $depth_str ='';
                $is_sun =$val['is_child'];
                if($is_tree>0 && $val['depth']>0)
                {
                    for($i=0;$i<=$val['depth'];$i++)
                    {
                        $depth_str .='&nbsp;&nbsp;';
                    }
                    //$depth_str .='├';
                    $depth_str .='|';


                    for($i=0;$i<=$val['depth'];$i++)
                    {
                        $depth_str .='-';
                    }
                }
                $val['areaname'] = $depth_str.$val['areaname'];
                if($is_sun==0)
                {
                    $val['is_child']=0;
                }
                else
                {
                    $val['is_child']=1;
                }
                $array_tree_list[$val['areaid']]=$val;

                if($is_sun==1)
                {
                    $_tem_array = $this->get_area_depth_order_list($area_list,$val['areaid'],($val['depth']+1),$is_tree);
                    if(is_array($_tem_array) && count($_tem_array)>0)
                    {
                        foreach($_tem_array as $skey=>$sval)
                        {
                            $array_tree_list[$sval['areaid']]=$sval;
                        }
                    }
                    unset($_tem_array);
                }
            }
        }
        unset($area_list);
        return $array_tree_list;
    }

    /**
     * author Tony
     * 2013-09-22
     * 将参数生成缓存
     */
    public function set_cache_loveparamter($data=array())
    {
        $love_paramter_db = new Model('love_paramter');
        $fileName = 'loveparamter';
        if(!is_array($data) || count($data)<=0)
        {
            $data = array();
            $list_data = $love_paramter_db->field('ptid,ptname,ptvalue,ptdec,orders,pttype,issystem')->where(array('flag'=>1))->ORDER('orders ASC')->select();
            if(is_array($list_data) && count($list_data)>0)
            {
                foreach($list_data as $key=>$val)
                {
                    $data[$val['ptid']]['ptid']=$val['ptid'];
                    $data[$val['ptid']]['ptname']=$val['ptname'];
                    $data[$val['ptid']]['ptdec']=$val['ptdec'];
                    $data[$val['ptid']]['orders']=$val['orders'];
                    $data[$val['ptid']]['pttype']=$val['pttype'];
                    $data[$val['ptid']]['issystem']=$val['issystem'];
                    if($val['ptvalue']!='')
                    {
                        $ptvalue_array=array();
                        $ptvalue = explode ( "|",$val['ptvalue']);
                        // 删除空数组
                        $ptvalue = array_filter($ptvalue);
                        if(is_array($ptvalue) && count($ptvalue)>0)
                        {
                            foreach($ptvalue as $pvkey=>$pvval)
                            {
                                $ptvalue_sun = explode ("#",$pvval);
                                if(is_array($ptvalue_sun) && count($ptvalue_sun)>0)
                                {
                                    $_tem_array['id'] = trim($ptvalue_sun[0]);
                                    $_tem_array['name'] = trim($ptvalue_sun[1]);
                                    $ptvalue_array[]=$_tem_array;
                                    unset($_tem_array);
                                }
                                unset($ptvalue_sun);
                            }
                        }
                        $data[$val['ptid']]['ptvalue']=$ptvalue_array;
                        unset($ptvalue_array);
                    }
                }
            }
        }
        setcache($fileName,$data,'setting');
        unset($data,$love_paramter_db);
    }
    /**
     * author Tony
     * 2013-09-25
     * 增加会用组缓存
     */
    public function set_cache_lovesort($fileName='lovesort',$data=array())
    {
        $lovesort_db = new Model('love_sort');
        if(!is_array($data) || count($data)<=0)
        {
            $list_data = $lovesort_db->field('*')->ORDER('orders ASC')->select();

            if(is_array($list_data) && count($list_data)>0)
            {
                foreach($list_data as $key=>$val)
                {
                    $data[$val['sortid']]=$val;
                }
            }
        }
        setcache($fileName,$data,'setting');
        unset($data,$usergroup_db);
    }

}
?>