<?php
/**
 * 个人信息类
 * @author Tony
 *
 */
class PersonalAction extends CommAction {

    /**
     * 首页
     * @author Tony
     */
    public function index(){
        $this->password();
    }


    /**
     * 修改密码
     * @author Tony
     */
    public function password(){
    	$admin_db = D('admin');
    	$method = $this->isPost();
    	$adminid = $this->adminid;
    	if($method){
    		$old_password= !empty($_REQUEST['old_password'])?trim($_POST['old_password']):'';
    		$new_password = !empty($_REQUEST['new_password'])?trim($_POST['new_password']):'';
    		$new_pwdconfirm =!empty($_REQUEST['new_pwdconfirm'])?trim($_POST['new_pwdconfirm']):'';
    		if($old_password=='' || $new_password=='')
    		{
    			$this->error('旧密码或新密码都不能为空','__APP_PATH__/index.php?g=Admin&m=Personal&=password');
    		}
    		if($new_pwdconfirm != $new_password)
    		{
    			$this->error('两次输入的新密码不一致','__APP_PATH__/index.php?g=Admin&m=Personal&=password');
    		}
    		$condition_old['id'] = $adminid;
    		$condition_old['password'] = md5Encrypt($old_password);
    		$admin_old_info = $admin_db->field('id')->where($condition_old)->find();
    		if(!$admin_old_info)
    		{
    			$this->error('旧密码不对，请重新输入','__APP_PATH__/index.php?g=Admin&m=Personal&=password');
    		}
    		$data['password'] = md5Encrypt($new_password);
    		$condition['id'] = $adminid;
    		if($admin_db->where($condition)->save($data)){
    			$this->success('修改密码成功','__APP_PATH__/index.php?g=Admin&m=Personal&=password');
    		}else{
    			$this->error('修改密码失败','__APP_PATH__/index.php?g=Admin&m=Personal&=password');
    		}
    	}else{
    		$condition['id'] = $adminid;
    		$info = $admin_db->field('id,adminuser,nickname,groupid')->where($condition)->find();
    		$this->assign('info',$info);
	        $this->display('password');
    	}
    }


    /**
     * 修改个人信息
     * @author Tony
     */
    public function info(){
    	$admin_db = D('admin');
    	$adminid = $this->adminid;
    	$method = $this->isPost();
    	if($method){
    		$data['email'] = trim($_POST['email']);
    		$data['phone'] = trim($_POST['phone']);
    		$data['tel'] = trim($_POST['tel']);
    		$data['nickname'] = trim($_POST['nickname']);
    		$condition['id'] = $adminid;
    		if($admin_db->where($condition)->save($data)){
    			$this->success('修改成功');
    		}else{
    			$this->error('修改失败');
    		}
    	}else{
    		$condition['id'] = $adminid;
    		$info = $admin_db->field('id,adminuser,email,nickname,groupid,phone,tel')->where($condition)->find();
    		$this->assign('info',$info);
	    	$this->display('info');
    	}
    }
}