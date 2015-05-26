<?php
/**
 * 系统设置类
 * @author Tony
 *
 */
class SystemAction extends CommAction {
	public $img;
    /**
     * 首页
     * @author Tony
     */
    public function index(){
        $this->adminList();
    }


    /**
     * 管理员列表
     * @author Tony
     */
    public function adminList(){
    	$nowPage = isset($_GET['p'])?$_GET['p']:1;
    	$search['nickname'] = $_REQUEST['nickname'];
    	$search['roleid'] = $_REQUEST['roleid'];
    	$this->assign('search',$search);
    	$where = 'adminuser <>"admin"';
    	$wherePar = '';
    	if($search['nickname']){
    		$where .= " and nickname like '%".$search['nickname']."%'";
    		$wherePar .= "&nickname=".$search['nickname'];
    	}
    	if($search['roleid']){
    		if($search['roleid'] == 'normal'){
    			$where .= ' and roleid = 1';
    		}elseif($search['roleid'] == 'forbidden'){
    			$where .= ' and roleid = 0';
    		}
    		$wherePar .= "&roleid=".$search['roleid'];
    	}
    	$admin_db = D('admin');
    	import("ORG.Util.Page");// 导入分页类
    	$count      = $admin_db->where($where)->count();// 查询满足要求的总记录数
    	$this->assign('count',$count);
    	$Page       = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
    	//     	print_r($Page);die;
    	$Page->setNowPage($nowPage);
    	$Page->parameter = $wherePar;
    	$show       = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $admin_db->where($where)->order('id desc')->page($nowPage.','.$Page->listRows)->select();
    	$nav_db = D('nav');
    	$navList = $nav_db->select();
    	foreach($list as $k => $v){
    		if($v['groupid'] == 1){
    			$list[$k]['permissionStr'] = '<font color="green">超级管理員</font>(無需分配權限)';
    		}else{
	    		if($v['permission']){
	    			if(strpos($v['permission'],',')){
	    				$permissionArray = explode(',',$v['permission']);
	    				foreach($navList as $kk => $vv){
	    					foreach($permissionArray as $kkk => $vvv){
	    						if($vvv == $vv['id']){
	    							$list[$k]['permissionStr'] .= $vv['name'].',';
	    						}
	    					}
	    				}
	    				$list[$k]['permissionStr'] = trim($list[$k]['permissionStr'],',');
	    			}else{
	    				foreach($navList as $kk => $vv){
	    					if($vv['id'] == $v['permission']){
	    						$list[$k]['permissionStr'] = $vv['name'];
	    					}
	    				}
	    			}
	    		}else{
		    		$list[$k]['permissionStr'] = '<font color="red">權分未分配</font>(需要分配權限)';
	    		}
    		}
    	}
    	$this->assign('page',$show);// 赋值分页输出
    	$this->assign('list',$list);
    	$this->display('adminList');
    }
    
    /**
     * 编辑管理员（包括添加管理员）
     * @author Tony
     */
    public function editAdmin(){
    	$adminUser = $this->info['adminuser'];
    	if($adminUser != 'admin'){
    		$this->error('該帳戶沒有權限！',"__APP_PATH__/index.php?g=Admin&m=System&a=adminList");die;
    	}
    	$this->assign('priv',$this->priv);
    	$targetid = intval($_REQUEST['targetid']);
    	$method = $this->isPost();
    	if($method){
    		$admin_db = D('admin');
    		$info['adminid'] = $targetid;
    		$info['adminuser'] = $this->_post('adminuser');
    		$info['password'] = $this->_post('password');
    		$info['repassword'] = $this->_post('repassword');
    		$info['groupid'] = $this->_post('groupid');
    		$info['roleid'] = $this->_post('roleid');
    		$info['nickname'] = $this->_post('nickname');
    		$info['phone'] = $this->_post('phone');
    		$info['tel'] = $this->_post('tel');
    		if($info['groupid'] != 1){
    			$info['permission'] = trim(implode(",",$this->_post('permission')));//以逗号为分界点;
    		}else{
    			$info['permission'] = '';
    		}
    		$this->check_from($info,"__APP_PATH__/index.php?g=Admin&m=System&a=editAdmin");
    		$adminData['adminuser'] = $info['adminuser'];
    		if($info['password']){
    			$adminData['password'] = md5Encrypt($info['password']);
    		}
    		$adminData['nickname'] = $info['nickname'];
    		$adminData['groupid'] = $info['groupid'];
    		$adminData['permission'] = $info['permission'];
    		$adminData['email'] = $info['email'];
    		$adminData['phone'] = $info['phone'];
    		$adminData['tel'] = $info['tel'];
    		$adminData['roleid'] = $info['roleid'] == 'normal' ? 1 : 0;
//     		print_r($adminData);die;
    		if($info['adminid']){
    			$str = '编辑';
	    		$adminData['updatetime'] = time();
	    		$re = $admin_db->where('id='.$targetid)->save($adminData);
    		}else{
    			$str = '添加';
	    		$adminData['createtime'] = time();
	    		$re = $admin_db->add($adminData);
    		}    	
    		if($re!==false){
    			$this->success($str.'成功',U('System/adminList'));
    		}else{
    			$this->success($str.'失败',U('System/editAdmin'));
    		}
    	}else{
    		$info = array();
    		if($targetid){
    			$title = '编辑';
				$admin_db = D('admin');
				$info = $admin_db->where('id='.$targetid)->find();
				$permission = $info['permission'];
// 				$permission = explode(",",$info['permission']);
				$this->assign('permission',$permission);
    		}else{
    			$title = '添加';
    			$info['roleid'] = 1;
    			$info['groupid'] = 2;
    		}
    		$this->assign('title',$title);
    		$this->assign('info',$info);
    		$this->display('editAdmin');
    	}
    }
    
    /**
     * 删除管理员
     * @author Tony
     */
    public function delAdmin(){
    	$adminUser = $this->info['adminuser'];

    	if($adminUser == 'admin'){
	    	$ids = !empty($_REQUEST['ids'])?$_REQUEST['ids']:'';
	    	$re = $this->delApi('admin', $ids, "adminuser <> 'admin' and id");
    	}else{
    		$this->error('該帳戶沒有權限！',"__APP_PATH__/index.php?g=Admin&m=System&a=adminList");
    	}
    }

    /**
     * 日志
     * @author Tony
     */
    public function logs(){
    	$nowPage = isset($_GET['p'])?$_GET['p']:1;
    	$logs_db = D('logs');
    	import("ORG.Util.Page");// 导入分页类
    	$count      = $logs_db->count();// 查询满足要求的总记录数
    	$this->assign('count',$count);
    	$Page       = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
    	//     	print_r($Page);die;
    	$Page->setNowPage($nowPage);
    	$show       = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $logs_db->order('id desc')->page($nowPage.','.$Page->listRows)->select();
    	$this->assign('page',$show);// 赋值分页输出
    	$admin_db = D('admin');
    	foreach($list as $k => $v){
    		$tempInfo = $admin_db->where('id='.$v['adminid'])->field('nickname')->find();
    		$list[$k]['adminName'] = $tempInfo['nickname'];
    	}
    	$this->assign('list',$list);
    	$this->display('logs');
    }
    
    /**
     * 本店设置
     * @author Tony
     */
    public function nowStore(){
    	$adminUser = $this->info['adminuser'];
    	if($adminUser != 'admin'){
    		$this->error('此帳戶沒有權限！');die;
    	}
    	$options_db = D('options');
        $fileName = 'now_store';
        $condition['optionname'] = $fileName;
        $method = $this->isPost();
        if($method)
        {
        	
            $data = $this->_post('info');
        	$imgTag = $this->_post('imgTag');
        	if($imgTag == 2){
            	$data['logo'] = $this->_post('logoImg');
            }else{
	            if($_FILES['fileField']['size'] > 0){
	            	$this->_upload();
	            	$data['logo'] .= $this->img;
	            }
        	}
        	if($data['logo']){
	            $data['imgTag'] = 2;
        	}else{
        		$data['imgTag'] = 1;
        	}
            setcache($fileName,$data,'setting');
            $options_info = $options_db->field('*')->where($condition)->find();
            if(is_array($options_info) && count($options_info)>0)
            {
                $data_sql['optionvalue']=serialize($data);
                $data_sql['optiondesc']='本店设置';
                $re = $options_db->where($condition)->save($data_sql);
                if($re !== false){
                    $this->success('修改配置成功','__APP_PATH__/index.php?g=Admin&m=System&a=nowStore');
                }
                else
                {
                    $this->success('修改配置失敗，請檢查輸入','__APP_PATH__/index.php?g=Admin&m=System&a=nowStore');
                }
            }
            else
            {
                $data_sql['optionname']=$fileName;
                $data_sql['optionvalue']=serialize($data);
                $data_sql['optiondesc']='本店设置';
                $re = $options_db->add($data_sql);
                if($re !== false){
                    $this->success('修改配置成功','__APP_PATH__/index.php?g=Admin&m=System&a=nowStore');
                }else{
                    $this->success('修改配置失败，請檢查輸入','__APP_PATH__/index.php?g=Admin&m=System&a=nowStore');
                }
            }
            exit;
        }
        import('ORG.Configs.Configs');
        $get_config_info = getcache($fileName,'setting');
        if(is_array($get_config_info) && count($get_config_info)>0)
        {
            $options_info = $options_db->field('*')->where($condition)->find();
            $get_config_info=unserialize($options_info['optionvalue']);
        }
        $this->assign('info',$get_config_info);
    	$this->display('nowStore');
    }
    
    /**
     * 图片上传
     */
    protected function _upload() {
    	import("ORG.Net.UploadFile");
    	$upload = new UploadFile();
    	//设置上传文件大小
    	$upload->maxSize = 3292200000;
    	//设置上传文件类型
    	$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
    	//设置附件上传目录
    	$upload->savePath = './Public/upload/';
    	//        $upload->thumb = true;
    	//        // 设置引用图片类库包路径
    	//        //设置需要生成缩略图的文件后缀
    	//        $upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
    	//        //设置缩略图最大宽度
    	//        $upload->thumbMaxWidth = '400,100';
    	//        //设置缩略图最大高度
    	//        $upload->thumbMaxHeight = '400,100';
    	//        //设置上传文件规则
    	//        $upload->saveRule = uniqid;
    	//        //删除原图
    	//        //$upload->thumbRemoveOrigin = true;
    	if (!$upload->upload()) {
    		//捕获上传异常
    		$this->error($upload->getErrorMsg());
    	} else {
    		//取得成功上传的文件信息
    
    		$uploadList = $upload->getUploadFileInfo();
    		//P($uploadList);die;
    		$img='';
    		foreach($uploadList as $v){
    			$img=$img.'|'.$v['savepath'].$v['savename'];
    		}
    		$img=substr($img,1);
    		$this->img=$img;
    		//保存当前数据对象
    		//        $data['image'] = $_POST['image'];
    		//        $data['create_time'] = time();
    		//        P($data);
    
    	}
    }
    /**
     * 校验当前用户是否可用
     * @author Tony
     * @param string $adminUser	需要校验的用户名
     * @return boolean	返回布尔类型（前台调用返回ajax）
     */
    public function checkAdminUser($adminUser=null){
    	$par = false;
    	if(!$adminUser){
    		$adminUser = $this->_post('adminuser');
    		$par = true;
    	}
    	$where['adminuser'] = $adminUser;
    	$admin_db = D('admin');
    	$count = $admin_db->where($where)->count();
    	$result = true;//默认可用
    	if($count){
    		$result = false;
    	}
    	if($par){
    		$this->ajaxReturn($result);
    	}else{
    		return $result;
    	}
    
    }
    /**
     * 数据过滤校验
     * @author Tony
     * @param unknown $info	需要检验的数组
     * @param unknown $jumpUrl	出错后跳转的地址
     */
    public function check_from($info,$jumpUrl){
		if($info){
			if(!$info['adminid']){
				if($info['adminuser']){
					$ifCheck = $this->checkAdminUser($info['adminuser']);
					if(!$ifCheck){
						$this->error('此帳戶已經存在，請重新輸入！',$jumpUrl);die;
					}
				}else{
					$this->error('帳戶名稱不可為空，請輸入！',$jumpUrl);die;
				}
				if(!$info['password']){
					$this->error('密碼不可為空，請輸入！',$jumpUrl);die;
				}
				if($info['password'] != $info['repassword']){
					$this->error('兩次密碼不一致，請重新輸入！',$jumpUrl);die;
				}
			}
			if(!$info['nickname']){
				$this->error('暱稱不可為空，請輸入！',$jumpUrl);die;
			}
			if(!$info['groupid']){
				$this->error('請選擇該帳戶的分組！',$jumpUrl);die;
			}
			if(!$info['roleid']){
				$this->error('請選擇該帳戶的狀態！',$jumpUrl);die;
			}
		}    	
    }

}
