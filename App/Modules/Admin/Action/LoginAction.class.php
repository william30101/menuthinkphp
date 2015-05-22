<?php
class LoginAction extends Action {
    public function index(){
    	if(session('?adminuser') && session('adminid')){
    		redirect(U('Index/index'));
    		exit();
    	}
    	if($this->isGet()){
    		$this->display('login');
    		exit();
    	}
    	else if($this->isPost()){
    		$errors = session('errors');
    		if(!$errors){
    			session('errors',0);
    		}
		    if($errors > 2) {	//如果为post请求并且错误尝试次数小于4,则无需验证；否则需要对验证码进行验证
		    	$CheckCode = $this->_post('CheckCode');
		    /*	if(!$CheckCode) {
		    		$this->error('请输入验证码！','__APP_PATH__/index.php?g=Admin&m=Login&a=index');
		    		exit();
		    	}else{
		    		$verify = session('verify');
		    		$md5CheckCode = md5($CheckCode);
		    		if($verify != $md5CheckCode){
		    			$this->error('验证码错误，请重新输入！','__APP_PATH__/index.php?g=Admin&m=Login&a=index');
		    			exit();
		    		}
		    	}*/
		    }
		    
                session('errors',session('errors')+1);
		    	$adminuser=$this->_post('username');
		    	$password=htmlentities($this->_post('password'));
                //生成认证条件
                $map            =   array();
                // 支持使用绑定帐号登录
                $map['adminuser']	= $adminuser;
                import ( '@.ORG.Util.RBAC' );
                $authInfo = RBAC::authenticate($map,'admin');
                //使用用户名、密码和状态的方式进行认证
                if(false === $authInfo) {
                    $this->error('帐号不存在或已禁用！','__APP_PATH__/index.php?g=Admin&m=Login&a=index');
                }
                else
                {
                    if($authInfo['password'] != md5Encrypt($password)) {
                        $this->error('密码错误！','__APP_PATH__/index.php?g=Admin&m=Login&a=index');
                    }
                    if($authInfo['roleid']!=1){
                        $this->error('该账户已经被禁用！','__APP_PATH__/index.php?g=Admin&m=Login&a=index');
                    }
                    $_SESSION[C('USER_AUTH_KEY')]	=	$authInfo['id'];
                    $logincount = $authInfo['logincount']+1;
                    $admin_db = D('admin');
                    $upAdminData['lasttime'] = $authInfo['logintime'];
                    $upAdminData['logintime'] = time();
                    $upAdminData['loginip'] = get_client_ip();
                    $upAdminData['logincount'] = array('exp','logincount+1');
                    $admin_db->where('id='.$authInfo['id'])->save($upAdminData);
                    session('adminuser',$authInfo['adminuser']);
                    session('groupid',$authInfo['groupid']);
                    session('nickname',$authInfo['nickname']);
                    session('logincount',$logincount);
                    if($authInfo['id'])
                    {
                        session('adminid',$authInfo['id']);
                    }
                    if($authInfo['roleid'])
                    {
                        session('roleid',$authInfo['roleid']);
                    }
                    //存入日志表	added by Tony
					logs($authInfo['id']);
                    $this->msg='success!';
                    $this->success('登陆成功',U('Index/index'));
                    exit();
                }
                $this->adminuser=$adminuser;
		}
    }

    /**
     * 退出登录
     * @author Tony
     */
    public function logout(){
		session('adminuser',null);
		session('adminid',null);
		session('groupid',null);
		session('nickname',null);
		session('logincount',null);
		session('roleid',null);
		session('errors',null);
        $this->Index();
    }

    /**
     * 获取请求表中数据（请求下单，请求结账）
     * @author Tony
     */
    function getSettle(){
    	$settle_db = D('settle');
    	$list = $settle_db->order('createtime desc')->limit(5)->select();
    	$html ='';
    	foreach($list as $k => $v){
    		$html .='<LI>';
    		$html .='<a href="/index.php?g=Admin&m=Index&a=tempTabDetail&t_id='.$v['t_id'].'">'.$v['num'].'号桌位请求';
    		if($v['type'] == 2){
    			$html .= '<font color="green">结账</font>';
    		}else{
    			$html .= '<font color="red">下单</font></a>';
    		}
    		if($v['floorname']){
    			$html .= '('.$v['floorname']."&nbsp;".')';
    		}
    		$html .= '</LI>';
    	}
    	$this->ajaxReturn($html);
    }
    
    
    /**
     * author Tony
     * 2013-09-02
     * 生在验证码
     */
    public function checkcode()
    {

//     	print_r($_SESSION);die;
        //导入验证码类
        import("ORG.Util.Image");
        echo Image::buildImageVerify();
    }
    
    /**
     * 默认跳转操作 支持错误导向和正确跳转
     * 调用模板显示 默认为public目录下面的success页面
     * 提示页面为可配置 支持模板标签
     * @param string $message 提示信息
     * @param Boolean $status 状态
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @access private
     * @return void
     */
    private function dispatchJump($message,$status=1,$jumpUrl='',$ajax=false) {
    	if(true === $ajax || IS_AJAX) {// AJAX提交
    		$data           =   is_array($ajax)?$ajax:array();
    		$data['info']   =   $message;
    		$data['status'] =   $status;
    		$data['url']    =   $jumpUrl;
    		$this->ajaxReturn($data);
    	}
    	if(is_int($ajax)) $this->assign('waitSecond',$ajax);
    	if(!empty($jumpUrl)) $this->assign('jumpUrl',$jumpUrl);
    	// 提示标题
    	$this->assign('msgTitle',$status? L('_OPERATION_SUCCESS_') : L('_OPERATION_FAIL_'));
    	//如果设置了关闭窗口，则提示完毕后自动关闭窗口
    	if($this->get('closeWin'))    $this->assign('jumpUrl','javascript:window.close();');
    	$this->assign('status',$status);   // 状态
    	//保证输出不受静态缓存影响
    	C('HTML_CACHE_ON',false);
    	if($status) { //发送成功信息
    		$this->assign('message',$message);// 提示信息
    		// 成功操作后默认停留1秒
    		if(!isset($this->waitSecond))    $this->assign('waitSecond','1200');
    		// 默认操作成功自动返回操作前页面
    		if(!isset($this->jumpUrl)) $this->assign("jumpUrl",$_SERVER["HTTP_REFERER"]);
    		$this->display(C('TMPL_ACTION_SUCCESS'));
    	}else{
    		//$this->assign('error',$message);// 提示信息
    		$this->assign('message',$message);// 提示信息
    		//发生错误时候默认停留3秒
    		if(!isset($this->waitSecond))    $this->assign('waitSecond','3000');
    		// 默认发生错误的话自动返回上页
    		if(!isset($this->jumpUrl)) $this->assign('jumpUrl',"javascript:history.back(-1);");
    		$this->display(C('TMPL_ACTION_ERROR'));
    		// 中止执行  避免出错后继续执行
    		exit ;
    	}
    }
    
    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    protected function error($message='',$jumpUrl='',$ajax=false) {
    	$this->dispatchJump($message,0,$jumpUrl,$ajax);
    }

}
