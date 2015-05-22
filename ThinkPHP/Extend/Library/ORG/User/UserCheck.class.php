<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-9-10
 * Time: 下午3:46
 * To change this template use File | Settings | File Templates.
 */
import('ORG.Configs.Configs');
class UserCheck {//类定义开始
    public $username='';
    public $email='';
    public $password='';
    public $gender='';
    public $groupid='';
    public $usertype='';
    public $nickname='';
    public $mobile='';
    public $qq='';


    public function __construct()
    {

    }

    /**
     * author Tony
     * 2013-09-27
     * 验证用户名是合格
     */
    public function chech_uname()
    {
        //得到会员配置
        $Configs_obj = new Configs();
        $user_config = $Configs_obj->get_user_config();
        $is_error=0;
        if($this->username=='')
        {
            $is_error=1;
            $info='登录帐号不能为空！';
            return $this->out_chech_info($is_error,$info);
        }
        if(!preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/",$this->username))
        {
            $is_error=1;
            $info='登录帐号格式不对！';
            return $this->out_chech_info($is_error,$info);
        }
        $user_db = new Model('user');
        $condition_user['username'] = $this->username;
        $user_count = $user_db->where($condition_user)->count();
        if($user_count>0)
        {
            $is_error=1;
            $info='系统中已经存在此帐号了！';
            return $this->out_chech_info($is_error,$info);
        }
        unset($user_db,$user_count,$condition_user);
        return $this->out_chech_info($is_error,'验证通过！');
        /*
        if($this->username=='')
        {
            $is_error=1;
            $info='用户名不能为空！';
        }
        if(strripos('-'.$user_config['lockusers'].'-',$this->username))
        {
            $is_error=1;
            $info='此用户已被网站禁止，请选择其他用户名！';
            return $this->out_chech_info($is_error,$info);
        }
        if(!preg_match('/^[a-zA-Z0-9_]{3,20}$/i',$this->username))
        {
            $is_error=1;
            $info='用户名只能由：小写字母、数字、下划线组成！';
            return $this->out_chech_info($is_error,$info);
        }
        if(strlen($this->username)<3 || strlen($this->username)>20)
        {
            $is_error=1;
            $info='用户名长度最小6位，最长20位！';
            return $this->out_chech_info($is_error,$info);
        }
        $user_db = new Model('user');
        $condition_user['username'] = $this->username;
        $user_count = $user_db->where($condition_user)->count();
        if($user_count>0)
        {
            $is_error=1;
            $info='系统中已经存在此用户！';
            return $this->out_chech_info($is_error,$info);
        }
        unset($user_db,$user_count,$condition_user);
        return $this->out_chech_info($is_error,'验证通过！');
        */

    }

    /**
     * author Tony
     * 2013-09-27
     * 验证用户名是合格
     */
    public function chech_email()
    {
        $is_error=0;
        if($this->email=='')
        {
            $is_error=1;
            $info='电子邮箱不能为空！';
            return $this->out_chech_info($is_error,$info);
        }
        if(!preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/",$this->email))
        {
            $is_error=1;
            $info='电子邮箱格式不对！';
            return $this->out_chech_info($is_error,$info);
        }
        $user_db = new Model('user');
        $condition_user['email'] = $this->email;
        $user_count = $user_db->where($condition_user)->count();
        if($user_count>0)
        {
            $is_error=1;
            $info='系统中已经存在此邮箱了！';
            return $this->out_chech_info($is_error,$info);
        }
        unset($user_db,$user_count,$condition_user);
        return $this->out_chech_info($is_error,'验证通过！');

    }

    /**
     * author Tony
     * 2013-09-27
     * 验证密码
     */
    public function  chech_password()
    {
        $info='验证通过!';
        if($this->password!='')
        {
            if(strlen($this->password)<5 || strlen($this->password)>16)
            {
                $is_error=1;
                $info='密码长度，最小6位、最大16位';
            }
        }
        else
        {
            $is_error=1;
            $info='密码长度，最小6位、最大16位';
        }
        return $this->out_chech_info($is_error,$info);
    }


    /**
     * author Tony
     * 2013-09-27
     * 验证确认密码
     */
    public function  chech_password2()
    {
        $is_error=0;
        $info='验证通过!';
        $get_chech_info = $this->chech_password();
        if($this->password2=='')
        {
            $is_error=1;
            $info='确认密码不能为空';
        }
        else
        {
            if(strlen($this->password2)<5 || strlen($this->password2)>16)
            {
                $is_error=1;
                $info='确认密码长度，最小6位、最大16位';
            }
            else
            {
                if($this->password2!=$this->password)
                {
                    $is_error=1;
                    $info='两次输入的密码不同';
                }
            }
        }
        return $this->out_chech_info($is_error,$info);
    }

    /**
     * author Tony
     * 2013-09-27
     * 验证性别
     * @return array
     */
    public function  chechk_gender()
    {
        if(!in_array($this->gender,array(1,2)))
        {
            $is_error=1;
            $info='请选择性别';
        }
        return $this->out_chech_info($is_error,$info);
    }

    /**
     * author Tony
     * 2013-09-27
     * 验证性别
     * @return array
     */
    public function  chechk_usergroup()
    {
        $is_error=1;
        $info='不存此用户组';
        $get_usergroup_list = getcache('usergroup','setting');
        if(is_array($get_usergroup_list) && count($get_usergroup_list)>0)
        {
            foreach($get_usergroup_list as $key=>$val)
            {
                if($this->groupid==$val['groupid'])
                {
                    $is_error=0;
                    $info='';
                    break;
                }
            }
        }
        return $this->out_chech_info($is_error,$info);
    }


    /**
     * author Tony
     * 2013-09-27
     * 验证用户类型
     * @return array
     */
    public function  chechk_usertype()
    {
        if(!in_array($this->usertype,array(1,2)))
        {
            $is_error=1;
            $info='请选择用户类型';
        }
        return $this->out_chech_info($is_error,$info);
    }

    /**
     * author Tony
     * 2013-09-27
     * 验证用户昵称
     * @return array
     */
    public function  chechk_nickname()
    {
        if($this->nickname=='')
        {
            $is_error=1;
            $info='用户昵称不能为空！';
        }
        if(strlen($this->nickname)<=2 || strlen($this->nickname)>32)
        {
            $is_error=1;
            $info='昵称的不能为空，最小长度不能小于2，最大不能大于32';
        }
        return $this->out_chech_info($is_error,$info);
    }
    //mobile
    /**
     * author Tony
     * 2013-09-27
     * 验证用户名是合格
     */
    public function chech_mobile()
    {
        $is_error=0;
        if($this->mobile=='')
        {
            $is_error=1;
            $info='手机不能为空！';
        }
        if(!preg_match('/^[0-9]+$/u',$this->mobile))
        {
            $is_error=1;
            $info='手机只能由：数字组成！';
            return $this->out_chech_info($is_error,$info);
        }
        if(strlen($this->mobile)<6 || strlen($this->mobile)>20)
        {
            $is_error=1;
            $info='手机长度最小6位，最长20位！';
            return $this->out_chech_info($is_error,$info);
        }
        $user_db = new Model('user');
        $condition_user['mobile'] = $this->mobile;
        $user_count = $user_db->where($condition_user)->count();
        if($user_count>0)
        {
            $is_error=1;
            $info='系统中已经存在此手机号！';
            return $this->out_chech_info($is_error,$info);
        }
        unset($user_db,$user_count,$condition_user);
        return $this->out_chech_info($is_error,'验证通过！');

    }


    /**
     * author Tony
     * 2013-09-27
     * 输出验证信息
     * @param $is_error
     * @param $info
     */
    public function out_chech_info($is_error,$info='验证通过！')
    {
        if($info=='')
        {
            $info='验证通过！';
        }
        $out_info=array();
        if(!$is_error)
        {
            $out_info['info']=$info;
            $out_info['status']='y';

            //echo  '{"info":"验证通过！","status":"y"}';
        }
        else
        {
            $out_info['info']=$info;
            $out_info['status']='n';
            //echo  '{"info":"'.$info.'","status":"n"}';
        }
        return $out_info;
    }

    /**
     * author Tony
     * 2013-09-27
     * 验证用户名是合格
     */
    public function chech_qq()
    {
        $is_error=0;
        if($this->qq=='')
        {
            $is_error=1;
            $info='QQ不能为空！';
        }
        if(!preg_match('/^[0-9]+$/u',$this->qq))
        {
            $is_error=1;
            $info='QQ只能由：数字组成！';
            return $this->out_chech_info($is_error,$info);
        }
        if(strlen($this->qq)<6 || strlen($this->qq)>20)
        {
            $is_error=1;
            $info='QQ长度最小6位，最长20位！';
            return $this->out_chech_info($is_error,$info);
        }
        $partner_attr_db = new Model('partner_attr');
        $condition_user['qq'] = $this->qq;
        $user_count = $partner_attr_db->where($condition_user)->count();
        if($user_count>0)
        {
            $is_error=1;
            $info='系统中已经存在此QQ号！';
            return $this->out_chech_info($is_error,$info);
        }
        unset($partner_attr_db,$user_count,$condition_user);
        return $this->out_chech_info($is_error,'验证通过！');

    }


}