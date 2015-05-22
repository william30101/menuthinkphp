<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-9-10
 * Time: 下午3:46
 * To change this template use File | Settings | File Templates.
 */
class LoveParamter {
    public $paramter='';

    /**
     * author Tony
     * 2013-09-25
     * 构造函数
     */
    public function  __construct()
    {
        $this->paramter = getcache('loveparamter','setting');


    }

    public function between_list($start=1,$end=100,$type='')
    {
        $arr = array();
        $str='';
        $str_top='';
        $str_end='';
        $is_show=0;
        switch($type)
        {
            /*case 'age':
                $str='岁';
                $str_top=$str.'以下';
                $str_end=$str.'以上';
                break;
            case 'height':
                $str='cm';
                $str_top=$str.'以下';
                $str_end=$str.'以上';
                break;*/
            case 'weight':
                $str='公斤';
                $str_top=$str.'以下';
                $str_end=$str.'以上';
                $is_show=1;
                break;
            default;
                break;
        }
        if($is_show==1)
        {
            $arr[$start-1] = $start.$str_top;
        }

        for($i=$start;$i<=$end;$i++)
        {
            $arr[$i]=$i.$str;
        }
        if($is_show==1)
        {
            $arr[$end+1] = $end.$str_end;
        }

        return $arr;
    }
    /**
     * author Tony
     * 2013-09-27
     * 得到个参数的所有数据
     */
    public function get_paramter_list($keyname)
    {
        $pval_list=array();
        if($keyname=='')
        {

            return false;
        }
        $get_user_info = getcache('user_config','setting');
        switch($keyname)
        {
            case 'gender':
                $pval_list =array('0'=>'不限','1'=>'男','2'=>'女');
                break;
            case 'age':
                if($get_user_info['startage']<=0)
                {
                    $get_user_info['startage']=18;
                }
                if($get_user_info['endage']<=0)
                {
                    $get_user_info['endage']=65;
                }
                $start_age=$get_user_info['startage'];
                $end_age=$get_user_info['endage'];
                $pval_list = $this->between_list($start_age,$end_age);
                break;
            case 'height':

                if($get_user_info['startheight']<=0)
                {
                    $get_user_info['startheight']=150;
                }
                if($get_user_info['endheight']<=0)
                {
                    $get_user_info['endheight']=195;
                }
                $pval_list = $this->between_list($get_user_info['startheight'],$get_user_info['endheight']);
                break;
            case 'ageyear':
                if($get_user_info['startage']<=0)
                {
                    $get_user_info['startage']=18;
                }
                if($get_user_info['endage']<=0)
                {
                    $get_user_info['endage']=65;
                }
                $start_age=(intval(date('Y',time()))-$get_user_info['startage']);
                $end_age=(intval(date('Y',time()))-$get_user_info['endage']);
                $pval_list = $this->between_list($end_age,$start_age);
                break;
            case 'agemonth':
                $start_month=1;
                $end_month=12;
                $pval_list = $this->between_list($start_month,$end_month);
                break;
            case 'ageday':
                $start_month=1;
                $end_month=31;
                $pval_list = $this->between_list($start_month,$end_month);
                break;
            case 'lovesort':
                $loversort_list = getcache('lovesort','setting');
                $arr_tem=array();
                if(is_array($loversort_list) && count($loversort_list)>0)
                {
                    foreach($loversort_list as $key =>$val)
                    {
                        if($val['sortname']!='')
                        {
                            $arr_tem[$val['sortid']]['id']=$val['sortid'];
                            $arr_tem[$val['sortid']]['name']=$val['sortname'];
                        }
                        //$arr_tem[$val['sortid']] = $val['sortname'];
                    }
                }
                $pval_list= $this->get_pval_list($arr_tem);
                unset($arr_tem,$loversort_list);
                break;
            case 'weight':
                if($get_user_info['startweight']<=0)
                {
                    $get_user_info['startweight']=40;
                }
                if($get_user_info['endweight']<=0)
                {
                    $get_user_info['endweight']=100;
                }

                $pval_list = $this->between_list( $get_user_info['startweight'],$get_user_info['endweight'],'weight');
                break;
            default:
                if(is_array($this->paramter) && count($this->paramter)>0)
                {
                    foreach($this->paramter as  $key =>$val)
                    {
                        if($keyname==$val['ptname'] && is_array($val['ptvalue']) && count($val['ptvalue'])>0)
                        {
                            $pval_list= $this->get_pval_list($val['ptvalue']);
                            break;
                        }

                    }
                }
                break;
        }
        unset($get_user_info);
        return $pval_list;
    }

    /**
     * author Tony
     * 2013-09-27
     * 将参数变成一维数组
     * @return array
     */
    public function get_pval_list($arr)
    {
        $pval_arr=array();
        if(is_array($arr) && count($arr)>0)
        {
            foreach($arr as  $key =>$val)
            {
                $pval_arr[$val['id']]=$val['name'];
            }
        }
        return $pval_arr;
    }
    /**
     * author Tony
     * 2013-09-25
     */
    public function  get_key_pval($keyname,$id)
    {
        $pval_arr=array();
        if(is_array($this->paramter) && count($this->paramter)>0)
        {
            foreach($this->paramter as  $key =>$val)
            {
                if($keyname==$val['ptname'] && is_array($val['ptvalue']) && count($val['ptvalue'])>0)
                {
                    $pval_arr = $this->get_pval($val['ptvalue'],$id);
                    break;
                }

            }
        }
        return $pval_arr;
    }

    public function get_pval($array_list,$id)
    {
        $pval_arr=array();
        if(is_array($array_list) && count($array_list)>0)
        {
            foreach($array_list as  $key =>$val)
            {
                if($id==$val['id'])
                {
                    $pval_arr=$val;
                    break;
                }
            }
        }
        unset($array_list,$id);
        return $pval_arr;
    }

    /**
     * 通过键名和键值获取返回信息
     * author Tony
     */
    public function  get_key_pval4name($keyname,$id)
    {
    	$pval_name='';
    	if(is_array($this->paramter) && count($this->paramter)>0)
    	{
    		foreach($this->paramter as  $key =>$val)
    		{
    			if($keyname==$val['ptname'] && is_array($val['ptvalue']) && count($val['ptvalue'])>0)
    			{
    				//如果存有多数据，进行解剖，获取信息 added by Tony start
    				if(strpos($id,',')){
    					$idVal = explode(',',$id);
    					$temp = '';
  					    foreach($idVal as $k => $v){
				    		$temp .= $this->get_pval4name($val['ptvalue'],$v).',';
				    	}
	  					$pval_name = trim($temp,',');
	  				
    			    }else{//如果存有多数据，进行解剖，获取信息 added by Tony end
    					$pval_name = $this->get_pval4name($val['ptvalue'],$id);
    			    }
    				break;
    			}else{
    				$pval_name = $id;
    			}
    
    		}
    	}
    	return $pval_name;
    }
    /**
     * 通过键名和键值获取返回信息
     * author Tony
     */
    public function get_pval4name($array_list,$id)
    {
    	$pval_name='';
    	if(is_array($array_list) && count($array_list)>0)
    	{
    		foreach($array_list as  $key =>$val)
    		{	
    			if($id==$val['id'])
    			{
    				$pval_name=$val['name'];
    				break;
    			}else{
    				if($id){
    					$pval_name = $id;
    				}else{
    					$pval_name = '';
    				}
    			}
    		}
    	}
    	unset($array_list,$id);
    	return $pval_name;
    }

    
    /**
     * author Tony
     * 2013-09-25
     */
    public function  get_key_pval_arr($keyname,$id)
    {
        $pval_arr=array();
        if(is_array($this->paramter) && count($this->paramter)>0)
        {
            foreach($this->paramter as  $key =>$val)
            {
                if($keyname==$val['ptname'] && is_array($val['ptvalue']) && count($val['ptvalue'])>0)
                {
                    $ids  =  explode (",", $id );
                    if(is_array($ids) && count($ids)>0)
                    {
                        foreach($ids as  $ikey =>$ival)
                        {
                            $pval_arr[] = $this->get_pval($val['ptvalue'],$ival);
                        }
                    }
                    break;
                }

            }
        }
        return $pval_arr;
    }
    /**
     * author Tony
     * 2013-09-25
     * 析构函数
     */
    public function __destruct()
    {
        unset($this->paramter);
    }

}