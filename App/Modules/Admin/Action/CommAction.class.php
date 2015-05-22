<?php
class CommAction extends Action {
	protected $_domain = null; //由后端发往前段js中domain的有效值 added by Tony
	public $adminid = '';//管理员id
	public $info = '';
	public $menuArray = array();
	public $original = array();

	/**
	 * 权限数组
	 * @author Tony
	 * @var unknown
	 */
	public $priv = array(
// 			"0"=>array('id'=>'0','val'=>'Index','name'=>'系统首页','show'=> 1,'view'=>array(array('id'=>'001','val'=>'tabList','name'=>'桌号列表','show'=> 1),array('id'=>'002','val'=>'free','name'=>'空闲中桌位','show'=> 1),array('id'=>'003','val'=>'employ','name'=>'使用中桌位','show'=> 1),array('id'=>'004','val'=>'reserve','name'=>'已预定桌位','show'=> 1),array('id'=>'005','val'=>'notInvoicing','name'=>'未结账订单','show'=> 1),array('id'=>'006','val'=>'invoicing','name'=>'已结账订单','show'=> 1))),
// 			"1"=>array('id'=>'1','val'=>'Menu','name'=>'菜谱','show'=> 0,'view'=>array(array('id'=>'101','val'=>'typeList','name'=>'分类列表','show'=> 0),array('id'=>'102','val'=>'addType','name'=>'添加分类','show'=> 0),array('id'=>'103','val'=>'menuList','name'=>'菜谱列表','show'=> 0),array('id'=>'104','val'=>'addMenu','name'=>'添加菜谱','show'=> 0),array('id'=>'105','val'=>'hotMenu','name'=>'热卖菜','show'=> 0),array('id'=>'106','val'=>'material','name'=>'素材管理','show'=> 0))),
// 			"2"=>array('id'=>'2','val'=>'Stream','name'=>'流水统计','show'=> 0,'view'=>array(array('id'=>'201','val'=>'day','name'=>'今日流水','show'=> 0),array('id'=>'202','val'=>'week','name'=>'周流水','show'=> 0),array('id'=>'203','val'=>'month','name'=>'月流水','show'=> 0),array('id'=>'204','val'=>'all','name'=>'总流水','show'=> 0))),
// 			"3"=>array('id'=>'3','val'=>'Personal','name'=>'个人信息','show'=> 0,'view'=>array(array('id'=>'301','val'=>'password','name'=>'修改密码','show'=> 0),array('id'=>'302','val'=>'info','name'=>'修改个人信息','show'=> 0))),
// 			//"4"=>array('id'=>'4','val'=>'Indent','name'=>'订单管理','show'=> 0,'view'=>array(array('id'=>'401','val'=>'indentList','name'=>'订单列表','show'=> 0),array('id'=>'402','val'=>'serviceList','name'=>'使用中','show'=> 0),array('id'=>'403','val'=>'reserveList','name'=>'已预定','show'=> 0),array('id'=>'404','val'=>'outList','name'=>'已结账','show'=> 0))),
// 			"5"=>array('id'=>'5','val'=>'System','name'=>'系统设置','show'=> 0,'view'=>array(array('id'=>'501','val'=>'adminList','name'=>'操作员','show'=> 0),array('id'=>'502','val'=>'addAdmin','name'=>'添加员工','show'=> 0),array('id'=>'503','val'=>'logs','name'=>'日志','show'=> 0))),
// 			"6"=>array('id'=>'6','val'=>'Table','name'=>'餐桌设置','show'=> 0,'view'=>array(array('id'=>'601','val'=>'tableList','name'=>'餐桌列表','show'=> 0),array('id'=>'602','val'=>'areaType','name'=>'区域分类','show'=> 0),array('id'=>'603','val'=>'addArea','name'=>'添加区域','show'=> 0),array('id'=>'604','val'=>'editTable','name'=>'添加餐桌','show'=> 0)))
	);
	public  $siste_upload_url='';
    public function _initialize(){
        // 判断是否登陆
        self::check_admin();
        // 判断权限
        self::check_priv();
        // 记录日志
        self::manage_log();
        // ip屏蔽
        self::check_ip();
        $this->assign('adminid',session('adminid'));
        $this->assign('groupid',session('groupid'));
        $this->assign('roleid',session('roleid'));
        $this->assign('nickname',session('nickname'));

        $tmpl_conf=C("TMPL_PARSE_STRING");
        $_domain=str_replace("http://www.","",$tmpl_conf["__APP_PATH__"]);
        $this->_domain = $_domain=str_replace('/','',$_domain);
        $this->assign('domain_url',$_domain);
        $this->assign('syste_upload_url',C('UPLOAD_URL'));
        $this->siste_upload_url = C('UPLOAD_URL');

    }

    /**
     * 判断当前管理员权限
     * @author Tony
     */
    public  function check_priv(){
    	$admin_db = D('admin');
    	$adminid = $this->adminid;
    	if($adminid){
	    	$info = $admin_db->where('id='.$adminid)->find();
	    	$roleid = $info['roleid'];
	    	if($roleid != 1){
	    		$this->error('该账户已经被禁用！',"__APP_PATH__/index.php?g=Admin&m=Login&a=logOut");
	    	}
	    	$this->info = $info;
	    	$this->assign('adminInfo',$info);
	    	$groupid = $info['groupid'];

	    	$nav_db = D('nav');
	    	$privInfo = $privInfoTemp = $nav_db->select();
	    	foreach($privInfo as $k => $v){
	    		if(!$v['parentid']){
	    			$privTemp[$k] = $v;
	    		}
	    	}
	    	foreach($privInfo as $k => $v){
	    		if($v['parentid']){
	    			foreach($privInfoTemp as $kk => $vv){
	    				if($v['parentid'] == $vv['id']){
	    					$privTemp[$kk]['view'][] = $v;
	    				}
	    			}
	    		}
	    	}
    		$priv = $this->original = $privTemp;
    		$this->assign('menuOriginal',$privTemp);
    		if($groupid == 1){//超级管理员
    			foreach($priv as $k => $v){
    				$priv[$k]['show'] = 1;
    				foreach($v['view'] as $kkk => $vvv){
    					$priv[$k]['view'][$kkk]['show'] = 1;
    				}
    			}
    		}else{//普通管理员
	    		$roleid = $info['roleid'];
	    		if(!$roleid){
	    			session('adminuser',null);
	    			session('adminid',null);
	    			session('roleid',null);
	    			$this->error('该账户已经被禁止！',"__APP_PATH__/index.php?g=Admin&m=Login&a=index");
	    		}else{
	    			$permission = $info['permission'];
	    			$permissionArr = explode(',',$permission);
	    			foreach($permissionArr as $kk => $vv){
	    				foreach($priv as $k => $v){
	    						if($v['id']===$vv){
	    							$priv[$k]['show'] = 1;
	    						}
	    						foreach($v['view'] as $kkk => $vvv){
	    							if($vvv['id']===$vv){
	    								$priv[$k]['view'][$kkk]['show'] = 1;
	    							}
	    						}
	    				}
	    			}
	    		}
    			//获得当前操作Action
    			$action = MODULE_NAME;
    			//获得当前操作function
    			$function = ACTION_NAME;
    			foreach($priv as $k => $v){
    				if($action == $v['val']){
    					if($v['show'] != 1){
    						$this->error('该账户没有权限！');die;
    					}
    				}
    				foreach($v['view'] as $kk => $vv){
    					if($function == $vv['val']){
    						if($vv['show'] != 1){
    							$this->error('该账户没有权限！');die;
    						}
    					}
    				}
    			}
	    	}
	    	$this->menuArray = $priv;
	    	$this->assign('topMenu',$priv);
    	}
    }

    /**
     * 判断用户是否已经登陆
     */
    final public function check_admin() {
        if(GROUP_NAME =='admin' && MODULE_NAME =='index' && in_array(ACTION_NAME, array('login', 'public_card'))) {
            return true;
        } else {
            $this->adminid = $adminid = session('adminid');
            $groupid = session('groupid');
            if(!isset($adminid) || !isset($groupid) || !$adminid || !$groupid){
                //showmessage(L('admin_login'),'?m=admin&c=index&a=login');
                $this->errorLogin('您没有登陆，请重新登陆.',"__APP_PATH__/index.php?g=Admin&m=Login&a=index");

            }
        }
    }

    /**
     * author Tony
     * 2013-09-04
     * 系统记录日志
     */
    public function manage_log()
    {
        /**
         * 后期判断是否记录日志
         */
        $M=M('log');
        $M->update_user = session('userid');
        $M->update_time = get_time();
        $M->operate 	= 'login';
        $M->ip 			= get_client_ip();
        $M->status 		= '0';
        $M->add();
    }

    /**
     * author Tony
     * 2013-09-04
     * ip屏蔽
     */
    public  function check_ip()
    {

    }


    public function _empty($action){
       $this->error('Unknow handle.',U('index'));
    }


    /**
     * 按父ID查找菜单子项
     * @param integer $parentid   父菜单ID
     * @param integer $with_self  是否包括他自己
     */
    final public static function admin_menu($parentid, $with_self = 0) {
        $parentid = intval($parentid);
        $menudb = new Model('node');
        $condition['pid']= $parentid;
        $condition['status']= 1;

        $result =$menudb->field('*')->where($condition)->limit('0,1000')->ORDER('sort ASC')->select();
        if($with_self) {
            $result2[] = $menudb->field('*')->where(array('id'=>$parentid))->find();
            $result = array_merge($result2,$result);
        }
        //权限检查
        if(session('roleid') == 1) return $result;
        $array = array();
        //$privdb = pc_base::load_model('admin_role_priv_model');
        //$siteid = param::get_cookie('siteid');
        $privdb = M('access');
        foreach($result as $v) {
            $action = $v['a'];
            if(preg_match('/^public_/',$action)) {
                $array[] = $v;
            } else {
                if(preg_match('/^ajax_([a-z]+)_/',$action,$_match)) $action = $_match[1];
                //$r = $privdb->get_one(array('m'=>$v['m'],'c'=>$v['c'],'a'=>$action,'roleid'=>$_SESSION['roleid'],'siteid'=>$siteid));

                $r = $privdb->where(array('role_id'=>session('roleid'),'node_id'=>$v['node_id']));
                if($r) $array[] = $v;
            }
        }
        return $array;
    }

    /**
     * 获取菜单 头部菜单导航
     *
     * @param $parentid 菜单id
     */
    final public static function submenu($parentid = '', $big_menu = false,$type=0) {

        /*
        echo "<br>--GROUP_NAME:".GROUP_NAME.'<br>';
        echo "<br>--MODULE_NAME:".MODULE_NAME.'<br>';
        echo "<br>--ACTION_NAME:".ACTION_NAME.'<br>';
        */


        if(empty($parentid)) {
            $menudb = M('node');
            $r = $menudb->where(array('g'=>GROUP_NAME,'m'=>MODULE_NAME,'a'=>ACTION_NAME))->find();
            $parentid = $_GET['menuid'] = $r['id'];
        }
        $array = self::admin_menu($parentid,1);
        $numbers = count($array);
        if($numbers==1 && !$big_menu) return '';
        $string = '';

        foreach($array as $_value) {
             if($type==-1)
             {
                 if($_value['type']!=0)
                 {
                     continue;
                 }
             }
            if (!isset($_GET['s'])) {
                $classname = GROUP_NAME == $_value['g'] && MODULE_NAME == $_value['m'] && ACTION_NAME == $_value['a'] ? 'class="on"' : '';
            } else {
                $_s = !empty($_value['data']) ? str_replace('=', '', strstr($_value['data'], '=')) : '';
                $classname = GROUP_NAME == $_value['g'] && MODULE_NAME == $_value['m'] && ACTION_NAME == $_value['a'] && $_GET['s'] == $_s ? 'class="on"' : '';
            }
            if($_value['pid'] == 0 || $_value['g']=='') continue;
            if($classname) {
                $string .= "<a href='javascript:;' $classname><em>".$_value['title']."</em></a><span>|</span>";
            } else {
                $string .= "<a href='?g=".$_value['g']."&m=".$_value['m']."&a=".$_value['a']."&menuid=$parentid".'&'.$_value['data']."' $classname><em>".$_value['title']."</em></a><span>|</span>";
            }
        }
        $string = substr($string,0,-14);
        return $string;
    }

    /**
     * 当前位置
     *
     * @param $id 菜单id
     */
    final public static function current_one($id) {
        $menudb = M('node');
        $r =$menudb->field('id,name,title')->where(array('id'=>$id))->find();
        $str = '';
        return $str.'<a class="on"> <em>'.$r['title'].'</em></a> <span>|</span>';
    }


    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param string $message 提示信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    protected function success($message='',$jumpUrl='',$ajax=false) {
        $this->dispatchJump($message,1,$jumpUrl,$ajax);
        die;//为了每个页面的后台验证，输出为单一文字 added by Tony 2013/09/28
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
        if($status == 1) { //发送成功信息
            $this->assign('message',$message);// 提示信息
            // 成功操作后默认停留1秒
            if(!isset($this->waitSecond))    $this->assign('waitSecond','1200');
            // 默认操作成功自动返回操作前页面
            if(!isset($this->jumpUrl)) $this->assign("jumpUrl",$_SERVER["HTTP_REFERER"]);
            $this->display(C('TMPL_ACTION_SUCCESS'));
        }else{
        	if($status == -1){
        		$this->assign('message',$message);// 提示信息
	            // 成功操作后默认停留1秒
	            if(!isset($this->waitSecond))    $this->assign('waitSecond','1200');
	            // 默认操作成功自动返回操作前页面
	            if(!isset($this->jumpUrl)) $this->assign("jumpUrl",$_SERVER["HTTP_REFERER"]);
	            $this->display(C('TMPL_ACTION_ERRORLOGIN'));
	            die;
        	}
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
     * 关闭fancybox
     * @author Tony
     * @param unknown $title
     */
    public function fancyboxClose($title){
    	header("Content-type: text/html; charset=utf-8");
    	$html .= '<div style="text-align:center;"><div><h2>'.$title.'</h2></div>';
    	$html .= '<div><input type="button" onclick="parent.fancyboxClose();" value="确定" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="parent.fancyboxClose(1);" value="关闭并刷新" /></div>';
    	$html .= "<script>document.domain='".$this->_domain."';</script></div>";
    	echo $html;
    	die;
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
    
    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    protected function errorLogin($message='',$jumpUrl='',$ajax=false) {
    	$this->dispatchJump($message,-1,$jumpUrl,$ajax);
    }

    
    /**
     * 通用删除方法（包括多数据删除）
     * @author Tony
     * @param unknown $db	对象数据库表名
     * @param unknown $str	删除数据
     * @param unknown $par	条件
     * @param unknown $type 默认：无返回值；1：有返回值
     */
    public function delApi($db,$str,$par,$type=null){
    	$ids = !empty($str)?$str:'';
    	if($ids=='' || $ids==0 ){
    		if(!$type){
	    		$this->success('没有选择删除的数据');
    		}else{
    			return false;
    		}
    	}else{
    		if(is_array($ids) && count($ids)>0){
    			$ids = implode(',',$ids);
    		}
    		if(strpos($ids,',')){
    			$ids = trim($ids, ",");
    		}
    		$obj_db = D($db);
    		$re = $obj_db->where($par.' in('.$ids.')')->delete();
    		if($re !== false){
	    		if(!$type){
		    		$this->success('删除成功');
	    		}else{
	    			return true;
	    		}
    		}else{
    			if(!$type){
    				$this->success('删除失败');
    			}else{
    				return false;
    			}
    		}
    	}
    }

    /**桌列表筛选权限返回$arr
     * @param $db
     * @param $adminid
     * @param $where
     */
    public function screen($db,$adminid,$where=''){
        $admin_db = D('admin');
        $floor_db = D('floor');
        $floor=$floor_db->select();
        $floorInfo = $floor_db->select();
        $floorList = getMenuType($floorInfo,'┠');
        $nowPage = isset($_GET['p'])?$_GET['p']:1;
        import("ORG.Util.Page");// 导入分页类
        $tempInfo = $admin_db->where('id='.$adminid)->field('floor_id,nickname,groupid')->find();
        if($tempInfo['groupid']==1){
            $count=$db->where($where)->count();
            $Page       = new Page($count,7);// 实例化分页类 传入总记录数和每页显示的记录数
            //     	print_r($Page);die;
            $Page->setNowPage($nowPage);
            $show       = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = $db->where($where)->order('id asc')->page($nowPage.','.$Page->listRows)->select();
            foreach($list as $k => $v){
                foreach($floorList as $kk => $vv){
                    if($v['floor_num'] == $vv['id']){
                        if($vv['parentid'] > 0){
                            foreach($floorList as $kkk => $vvv){
                                if($vv['parentid'] == $vvv['id']){
                                    $list[$k]['floorName'] = $vvv['name'].'&nbsp;>>';
                                }
                            }
                        }
                        $list[$k]['floorName'] .= $vv['name'];
                    }
                }
                $floorTempInfo = $floor_db->where('id='.$v['floor_num'])->field('adminid')->find();
                $tempInfo = $admin_db->where('id in('.$floorTempInfo['adminid'].')')->field('nickname')->select();
                foreach($tempInfo as $tk => $tv){
                	if(!empty($tv['nickname'])){
                		$list[$k]['adminName'] .= $tv['nickname'].',';
                	}
                }
                $list[$k]['adminName'] = trim($list[$k]['adminName'],',');
            }

        }else{
            $floor=getChilds($floor,$tempInfo['floor_id']);
            if(!empty($where)){
                $where=' and '.$where;
            }
            if(count($floor)>1){
                $floor=implode(',',$floor);
                $count      = $db->where('floor_num in ('.$floor.')'.$where)->count();// 查询满足要求的总记录数
                $Page       = new Page($count,7);// 实例化分页类 传入总记录数和每页显示的记录数
                //     	print_r($Page);die;
                $Page->setNowPage($nowPage);
                // 分页显示输出
                $show       = $Page->show();
                // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
                $list = $db->where('floor_num in ('.$floor.')'.$where)->order('id asc')->page($nowPage.','.$Page->listRows)->select();
                foreach($list as $k => $v){
                    foreach($floorList as $kk => $vv){
                        if($v['floor_num'] == $vv['id']){
                            if($vv['parentid'] > 0){
                                foreach($floorList as $kkk => $vvv){
                                    if($vv['parentid'] == $vvv['id']){
                                        $list[$k]['floorName'] = $vvv['name'].'&nbsp;>>';
                                    }
                                }
                            }
                            $list[$k]['floorName'] .= $vv['name'];
                        }
                    }
                }
            }else{
                $count      = $db->where('floor_num ='.$floor[0].$where)->count();// 查询满足要求的总记录数
                $Page       = new Page($count,7);// 实例化分页类 传入总记录数和每页显示的记录数
                //     	print_r($Page);die;
                $Page->setNowPage($nowPage);
                // 分页显示输出
                $show       = $Page->show();
                $list = $db->where('floor_num ='.$floor[0].$where)->order('id asc')->page($nowPage.','.$Page->listRows)->select();
                foreach($list as $k => $v){
                    foreach($floorList as $kk => $vv){
                        if($v['floor_num'] == $vv['id']){
                            if($vv['parentid'] > 0){
                                foreach($floorList as $kkk => $vvv){
                                    if($vv['parentid'] == $vvv['id']){
                                        $list[$k]['floorName'] = $vvv['name'].'&nbsp;>>';
                                    }
                                }
                            }
                            $list[$k]['floorName'] .= $vv['name'];
                        }
                    }
                }
            }
            foreach($list as $k => $v){
                $list[$k]['adminName'] = $tempInfo['nickname'];
            }
        }
        $arr=array();
        $arr['count']=$count;
        $arr['show']=$show;
        $arr['list']=$list;
        return $arr;
    }



    /**权限过滤$where条件
     * @param $id  int 管理的id
     * @param $where string 原始条件
     * @param $field string where条件的字段
     * @return mixed        筛选后的条件
     * @author  huailong
     *
     */
    protected function getWhere($id,$where='',$field){
        $tab_db=M("tab");
        $floor_db=M('floor');
        if($this->info['groupid']==1){
            return $where;
        }else{
            if(empty($where)){
                $tabInfo=$floor_db->where('id in ('.$this->info['floor_id'].')')->select();
                foreach($tabInfo as $v){
                    if($v['parentid']==0){
                        $arr[]=$floor_db->field('id')->where('parentid='.$v['id'])->select();
                    }else{
                        $newArr[]=$v['id'];
                    }
                }

                $arr=getOne($arr);
                $arr=getOne($arr);
                $tabId=implode(',',$arr);
                $tabid=implode(',',$newArr);
                if($tabId && $tabid){
                    $tabId=$tabId.','.$tabid;
                }elseif(!$tabId && $tabid){
                    $tabId=$tabid;
                }
                $tabDi=$tab_db->field('id')->where('floor_num in('.$tabId.')')->select();
                $tabDi=getOne($tabDi);
                $tabDi=implode(',',$tabDi);
                $where=$field.' in ('.$tabDi.')';
            }else{
                $tabInfo=$floor_db->where('id in ('.$this->info['floor_id'].')')->select();
                foreach($tabInfo as $v){
                    if($v['parentid']==0){
                        $arr[]=$floor_db->field('id')->where('parentid='.$v['id'])->select();
                    }else{
                        $newArr[]=$v['id'];
                    }
                }
                $arr=getOne($arr);
                $arr=getOne($arr);
                $tabId=implode(',',$arr);
                $tabid=implode(',',$newArr);
                if($tabId && $tabid){
                    $tabId=$tabId.','.$tabid;
                }elseif(!$tabId && $tabid){
                    $tabId=$tabid;
                }
                $tabDi=$tab_db->field('id')->where('floor_num in ('.$tabId.')')->select();
                $tabDi=getOne($tabDi);
                $tabDi=implode(',',$tabDi);
                $where=$field.' in ('.$tabDi.') and '.$where;
            }
	        return $where;
        }
    }


}