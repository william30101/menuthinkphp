<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-9-4
 * Time: 上午11:15
 * To change this template use File | Settings | File Templates.
 */

// 会员参数处理
import('ORG.User.UserData');
// 会员参数验证
import('ORG.User.UserCheck');
// 后台用户模块
import('ORG.Configs.Configs');
class PublicAction extends CommAction{
    public function __construct(){
        parent::__construct();
    }

    /**
     * 系统管理员后台直接登录用户
     * author Tony
     */
	public function adminLoginUser(){
		$userid = $this->_get('userid');
		if($userid){
			$adminId = $_SESSION['userid'];
			$adminName = $_SESSION['username'];
			$adminRoleid = $_SESSION['roleid'];
			$Admin = D('admin');
			$adminInfo = $Admin->where('adminid='.$adminId)->find();
			if($adminInfo['adminuser'] != $adminName || $adminInfo['roleid'] != $adminRoleid){
				$this->error('管理系统用户不存在，请重新确认');
				exit();
			}
			$Configs_obj = new Configs();
			$config_system_safe = $Configs_obj->get_base_config('safe');
			
			//得到会员配置
			$user_config = $Configs_obj->get_user_config();
			
			$user_db = new Model('user');
			$authInfo=$user_db->where('userid='.$userid)->find();
			if($authInfo)
			{
				$valid=3600*24;
				//cookie('think_language',$langSet,3600);
				cookie('userid',$authInfo['userid'],$valid);
				cookie('groupid',$authInfo['groupid'],$valid);
				cookie('gender',$authInfo['gender'],$valid);
				cookie('usertype',$authInfo['usertype'],$valid);
				cookie('username',$authInfo['username'],$valid);
				//cookie('user_authcode','adminLoginUser',$valid);
                //user_authcode
				cookie('adminLoginUser',1,$valid);
				$this->success('登录成功！正在跳转到用户中心页面','/myhome.html');
			}
			else
			{
				$this->error('系统中不存在此用户，请重新确认请操作');
				exit();
			}
		}
	}

}