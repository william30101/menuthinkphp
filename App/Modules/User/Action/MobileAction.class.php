<?php
/**
 * 会员中心--通用控制器
 * Class PublicAction
 *
 */
class MobileAction extends Action {
	public $tabId;//餐桌id
	public $tabNum;//餐桌号
	public $uid; //用户id
	public $uname; //用户名字
	public $houseLogo; //logo
	public $houseName; //酒店名字
	public $houseCountent; //详细
	
    public function __construct(){
        parent::__construct();
    	$this->tabId = cookie('tabId');
    	$this->tabNum = cookie('tabNum');
    	$this->uid   = cookie('uid');
    	$this->uname   = cookie('uname');
    	$this->houseLogo   = cookie('houseLogo');
    	$this->houseName   = cookie('houseName');
		if(empty($this->houseLogo) || empty($this->houseName)){
			$options_db = D('options');
			$fileName = 'now_store';
			$condition['optionname'] = $fileName;
			import('ORG.Configs.Configs');
			$get_config_info = getcache($fileName,'setting');
			if(is_array($get_config_info) && count($get_config_info)>0)
			{
				$options_info = $options_db->field('*')->where($condition)->find();
				$get_config_info=unserialize($options_info['optionvalue']);
			}
			$this->houseLogo = $get_config_info['logo'];
			cookie('houseLogo',$this->houseLogo);
			$this->houseName = $get_config_info['name'];
			cookie('houseName',$this->houseName);
		}
    	$this->assign('houseLogo',$this->houseLogo);
    	$this->assign('houseName',$this->houseName);
    	$this->assign('tabId',$this->tabId);
    	$this->assign('tabNum',$this->tabNum);
    	$this->assign('uid',$this->uid);
    	$this->assign('uname',$this->uname);
    }
    /**
     * 判断当前是否选桌
     * @author Tony
     */
    protected function isCookieTabId(){
    	if(!isset($this->tabId) || !isset($this->uid) || !isset($this->uname)){
    		$this->redirect(GROUP_NAME.'/Mobile/index');
    	}else{
    		$userInfo = D("user")->where('t_id='.$this->tabId.' and num='.$this->uid)->find();
    		if(!$userInfo){
    			cookie('tabId',null);
    			cookie('tabNum',null);
    			cookie('uid',null);
    			cookie('uname',null);
    			$this->redirect(GROUP_NAME.'/Mobile/index');
    		}
    	}
    }
    //半个小时不下订单，删除临时表和用户表
    protected function clear($tabId){
    	$temp_db=M('temp');
    	$tempInfo=$temp_db->field('starttime,check')->where('t_id='.$tabId)->order('starttime asc')->find();
    	$time=time();
    	$oldTime=$tempInfo['starttime']+60*1000*30;//半个小内未下单
    	if($time>$oldTime && $tempInfo['check']==0){
    		$temp_db->where('t_id='.$tabId)->delete();
    		M('user')->where('t_id='.$tabId)->delete();
    	}
    }
    
    /**
     * 手机端页面首页
     * @author Tony
     */
	public function index(){
		if($this->tabId && $this->uid && $this->uname){
			$tabInfo = D('tab')->where('id='.$this->tabId)->field('status')->find();
			if($tabInfo['status'] == 3){
				$count = D('temp')->where('t_id='.$this->tabId.' and uname="'.$this->uname.'" and `check` > 0')->count();
				if($count){
					$this->classify();die;
				}
			}elseif($tabInfo['status'] == 1){
				$this->classify();die;
			}
		}
		$title = '手机点菜系统';
		$this->assign('title',$title);
		$id = $_REQUEST['id'] ? $_REQUEST['id'] : 0;
		import('ORG.Configs.Configs');
		$fileName = 'now_store';
		$get_config_info = getcache($fileName,'setting');
		if(is_array($get_config_info) && count($get_config_info)>0)
		{
			$options_db = D('options');
			$condition['optionname'] = $fileName;
			$options_info = $options_db->field('*')->where($condition)->find();
			$get_config_info=unserialize($options_info['optionvalue']);
		}
		$this->assign('configInfo',$get_config_info);//获得系统logo相关信息
		$floor_db = D('floor');
		$floorList = getMenuType($floor_db->select());
		$tab_db = D('tab');
		$tabList = $tab_db->select();
		$id = $id ? $id : $floorList[0]['id'];
		$this->assign('id',$id);
		foreach($floorList as $k => $v){
			if($v['parentid']){
				foreach($tabList as $kk => $vv){
					if($vv['floor_num'] == $v['id']){
						$floorList[$k]['view'][] = $vv;
						$v['view'][] = $vv;
					}
				}
				if($v['parentid'] == $id){
					$viewList[] = $v;
				}
			}else{
				$actionList[] = $v;
			}
		}
		$this->assign('viewList',$viewList);
		$this->assign('actionList',$actionList);
		$this->display('index');
	}    
	
	/**
	 * ajax自动判断用户id和自动添加用户名
	 * @author Tony
	 */
	public function tabPost(){
		$status = 0;
		$tabId = intval($this->_post('tabid'));
		$tab=M('tab')->where('id='.$tabId)->find();
		$adminid=M('floor')->where('id='.$tab['floor_num'])->field('adminid as id')->find();
		if($tab['status'] != 1 ){
			$status = $tab['status'];
		}else{
			$this->clear($tabId);
			$this->tabId = $tabId;
			cookie('tabId', $this->tabId);
			$this->tabNum = $tab['num'];
			cookie('tabNum', $this->tabNum);
			$user_db= M('user');
			$id=$user_db->where(array('t_id'=>$tabId))->order('num desc')->find();
			$arr['t_id']=$tabId;
			$arr['num']=$id['num']+=1;
			$this->uname='用户'.$id['num'];
			$arr['uname']=$this->uname;
			cookie('uname', $this->uname);
			cookie('uid',$id['num']);
			$re=$user_db->add($arr);
			if($re !== false){
				$adminid=M('floor')->where('id='.$tab['floor_num'])->field('adminid as id')->find();
				$admin_db=M(admin);
				if(strpos(',',$adminid['id'])){
					$nameArr=$admin_db->where('id in'.$adminid['id'])->field('nickname')->select();
					$nameArr=getOne($nameArr);
					$name=explode(',',$nameArr);
				}else{
					$nameArr=$admin_db->where('id ='.$adminid['id'])->field('nickname')->find();
					$name=$nameArr['nickname'];
				}
				$status = 1;
			}
		}
		$this->ajaxReturn($status);
	}

	/**
	 * 菜单分类列表
	 * @author Tony
	 */
	public function classify(){
		$this->isCookieTabId();
		$title = '菜单分类';
		$this->assign('title',$title);
		$db = M('menustype');
		$list = $db->where('parentid=0')->select();
		$this->assign('list',$list);
		$this->display('classify');
	}
	
	/**
	 * 菜单单一分类详细列表
	 * @author Tony
	 */
	public function greensList(){
		echo 'greenlist';
		$this->isCookieTabId();
		$id = $_GET['id'];
		$menustype_db = D('menustype');
		$nowInfo = $menustype_db->field('id,name,img')->where('id='.$id)->find();
		$listIds = $menustype_db->where('parentid='.$nowInfo['id'])->field('id')->select();
		$idArr = getOne($listIds);
		$idStr = implode(',',$idArr); 
		$list = M('menu')->where('menutypeid in('.$idStr.')')->select();
		$this->assign('nowInfo',$nowInfo);
		$title = $nowInfo['name'].'类';
		$this->assign('title',$title);
		$this->assign('list',$list);
		$this->display('greensList');
	}
	/**
	 * 单个菜的详细
	 * @author Tony 
	 */
	public function detail(){
		$this->isCookieTabId();
		$id = $_GET['id'];
		if($id){
			$info = M('menu')->where('id='.$id)->find();
			$menustype_db = D('menustype');
			$typeInfo = $menustype_db->where('id='.$info['menutypeid'])->find();
			$floorName = '';
			if($typeInfo['parentid']){
				$tempInfo = $menustype_db->where('id='.$typeInfo['parentid'])->find();
				$floorName = $tempInfo['name'].'&nbsp;>>';
			}
			$info['floorName'] = $floorName.$typeInfo['name'];
			$info['content'] = htmlspecialchars_decode($info['content']);
		}
		$title = $info['name'].'-详情';
		$this->assign('title',$title);
		$this->assign('info',$info);
		$this->display('detail');
	}
	/**
	 * 点菜（将菜加入临时表）
	 * @author Tony
	 */
	public function addMenu(){
		$this->isCookieTabId();
		$status = 0;//初始化返回值
		if(IS_POST){
			$tabArr = M('tab')->where('id='.$this->tabId)->find();
			$data = array();
			if($tabArr['status']==3){
				$data['status']=1;
			}
			$u_id = $this->_post('u_id');
			if($u_id){
				$menu_db = D('menu');
				$temp_db = D('temp');
				$menuInfo = $menu_db->where('id='.$u_id)->find();
				$data['t_id'] = $this->tabId;
				$data['price'] = $menuInfo['price'];
				$data['menustypeid'] = $menuInfo['menutypeid'];
				$data['name'] = $menuInfo['name'];
				$data['u_id'] = $menuInfo['id'];
				$data['starttime']=time();
				if(!$this->uname){
					$uid = $this->uid;
					$u = M('user')->where(array('t_id'=>$data['t_id']))->find(array('num'=>$uid));
					$data['uname'] = $u['uname'];
				}else{
					$data['uname'] = $this->uname;
				}
				$tempInfo =  $temp_db->where("t_id=".$this->tabId." and uname='".$data['uname']."' and u_id=".$u_id." and `check`=0")->find();
				if($tempInfo){
					$re = $temp_db->where('id='.$tempInfo['id'])->save(array('amount'=>array('exp','amount+1')));
				}else{
					$re = $temp_db->add($data);
				}
				$status = $re !== false ? 1 : 0;
			}
		}
		$this->ajaxReturn(array('status'=>$status));//ajax方式返回
	}
	/**
	 * 已点菜单列表
	 * @author Tony
	 */
	public function already(){
		$this->isCookieTabId();
		$title = '已点列表';
		$this->assign('title',$title);
		$db=M('temp');
		$status = M('tab')->where('id='.$this->tabId)->find();
		$status['status']==3;//桌子状态
		$list = $db->where('t_id='.$this->tabId)->select();
		$amount = $db->where('t_id='.$this->tabId)->sum('amount');
		$this->assign('amount',$amount);
		$totalInfo=$db->field('sum(price*amount) as total')->where('t_id='.$this->tabId)->find();
		$total = $totalInfo['total'];
		$this->assign('total',$total);
		$mList=M('menustype')->field('id,name')->select();
		$menu_db = D('menu');
		foreach($list as $k=>$v){
			foreach($mList as $m){
				if($m['id']==$v['menustypeid']){
					$list[$k]['dname']=$m['name'];
				}
			}
			if($v['u_id']){
				$tempMenuInfo = $menu_db->where('id='.$v['u_id'])->field('img,file_small,file_big')->find();
				$list[$k]['img'] = $tempMenuInfo['img'];
			}
			if(!$v['check']){
				$showStatus = 1;//1:提交订单，0:结账
			}
		}
		$this->assign('showStatus',$showStatus);
		$this->assign('list',$list);
		$this->display('already');
	}
	
	/**
	 * 删除已点菜
	 * @author Tony
	 */
	public function delAlready(){
		$id = $this->_get('id');
		$temp_db = D('temp');
		$temp_db->where('id='.$id)->delete();
		$this->already();//删除成功后掉用列表页
	}
	
	/**
	 * 推荐列表
	 * @author Tony
	 */
	public function recommend(){
		$this->isCookieTabId();
		$title = '推荐列表';
		$this->assign('title',$title);
		$menu_db = D('menu');
		$list = $menu_db->where('recommend=1')->select();
		$this->assign('list',$list);
		$nowInfo['name']='推荐列表';
		$nowInfo['img'] = $this->houseLogo;
		$this->assign('nowInfo',$nowInfo);
		$this->display('greensList');
	}
	/**
	 * 菜数量的加减
	 * @author Tony
	 */
	public function modifAmount(){
		$amount = $_POST['amount'];
		$id = $_POST['id'];
		$re = M('temp')->where('id='.$id)->save(array('amount'=>$amount));
		$status = $re !== false ? 1 : 0;
		$this->ajaxReturn(array('status'=>$status));//ajax方式返回
	}

	/**
	 * 热门列表
	 * @author Tony
	 */
	public function hotMenu(){
		$this->isCookieTabId();
		$title = '热门列表';
		$this->assign('title',$title);
		$db = M('stream');
		//查找所有点菜的id
		$a = $db ->field('u_id')->group('u_id')->select();
		$a = getOne($a);
		$arrNew=array();
		//循环组合热卖菜数组
		foreach($a as $k=>$v){
			$arr=array();//交换数据的临时数组
			$name=array();//用于放菜的名字
			$arr=$db->where(array('u_id'=>$v))->field('amount')->select();
			$arr=getOne($arr);
			$name=$db->where(array('u_id'=>$v))->limit(1)->select();
			$arrNew[$k]=$name;
			$arrNew[$k][0]['amount']=array_sum($arr);//计算所有点菜次数的总和
		}
		$hot = getOne($arrNew);
		$hotList = m_sort($hot,'amount');
		$menu_db = D('menu');
		foreach($hotList as $k => $v){
			if($v['u_id']){
				$tempInfo = $menu_db->where('id='.$v['u_id'])->find();
				if($tempInfo){
					$hotList[$k] = $tempInfo;
				}else{			
					unset($hotList[$k]);
				}
			}
		}
		$nowInfo['name']='热门列表';
		$nowInfo['img'] = $this->houseLogo;
		$this->assign('nowInfo',$nowInfo);
		$this->assign('list',$hotList);
		$this->display('greensList');
	}
	
	/**
	 * 点击下单，过滤
	 * @author Tony
	 */
	public function getStatus(){
		$status = 0;
		if($this->tabId){
			$temp_db = D('temp');
			$noTempList = $temp_db->where('t_id='.$this->tabId.' and `check`=0')->count();
			if($noTempList){
			 	$userList = D('user')->where('t_id='.$this->tabId)->select();
		        $count=count($userList);
		        if($count){
		        	$status = 1;
		            $data['count'] = $count;
		        }
			}else{
				$yeaTempList = $temp_db->where('t_id='.$this->tabId.' and `check`=1')->count();
				if($yeaTempList){
					$status = 2;//已经下过订单
				}else{
					$status = 3;//还没有点餐
				}
			}
		}
		$data['status'] = $status;
        $this->ajaxReturn($data);
	}
	
	/**
	 * 下单成功页面
	 * @author Tony
	 */
	public function orderSuccee(){
		$this->isCookieTabId();
		$nameId='';
		$temp_db=M('temp');
		//把临时表订单状态改为已经下单
		$menuIdArr = $temp_db->where('t_id='.$this->tabId.' and `check`=0')->select();
		$count = count($menuIdArr);
		if($count){
			$tab_db = D('tab');
			foreach($menuIdArr as $v){
				$nameId = $nameId.','.$v['id'];
			}
			$nameId = substr($nameId,1);
			$re = $temp_db->where('id in ('.$nameId.')')->save(array('check'=>1));
			if($re !== false){
				$data['status'] = 3;
				$re = $tab_db->where('id='.$this->tabId)->save(array('status'=>3));
			}
			$settle_db = D('settle');
			$tabId = $this->tabId;
			if($tabId){
				$floorInfo = D('floor')->select();
				$floorList = getMenuType($floorInfo,'┠');
				$tabTempInfo = $tab_db->where('id='.$tabId)->field('num,floor_num')->find();
				$floorName = '';
				foreach($floorList as $kk => $vv){
					if($tabTempInfo['floor_num'] == $vv['id']){
						if($vv['parentid'] > 0){
							foreach($floorList as $kkk => $vvv){
								if($vv['parentid'] == $vvv['id']){
									$floorName = $vvv['name'].'&nbsp;>>';
								}
							}
						}
						$floorName .= $vv['name'];
						break;
					}
				}
				
				$settleInfo = $settle_db->where('t_id='.$tabId)->find();
				$data['createtime'] = time();
				$data['type'] = 1;//下单请求
				$data['num'] = $tabTempInfo['num'];
				$data['floorname'] = $floorName;
				
				if($settleInfo){
					$settle_db->where('id='.$settleInfo['id'])->save($data);
				}else{
					$data['t_id'] = $tabId;
					$settle_db->add($data);
				}
			}
			$this->display('orderSuccee');
		}
	}
	
	/**
	 * 点击结账
	 * @author Tony
	 */
	public function orderSettle(){
		$status = 0;
		$settle_db = D('settle');
		$tabId = $this->tabId;
		if($tabId){
            $floorInfo = D('floor')->select();
			$floorList = getMenuType($floorInfo,'┠');
			$tab_db = D('tab');
			$tabTempInfo = $tab_db->where('id='.$tabId)->field('num,floor_num,status')->find();
			if($tabTempInfo['status'] == 4){
				$status = 4;
				cookie('tabId',null);
				cookie('tabNum',null);
				cookie('uid',null);
				cookie('uname',null);
			}else{
				$floorName = '';
				foreach($floorList as $kk => $vv){
					if($tabTempInfo['floor_num'] == $vv['id']){
						if($vv['parentid'] > 0){
							foreach($floorList as $kkk => $vvv){
								if($vv['parentid'] == $vvv['id']){
									$floorName = $vvv['name'].'&nbsp;>>';
								}
							}
						}
						$floorName .= $vv['name'];
						break;
					}
				}
				$data['t_id'] = $tabId;
				$data['type'] = 2;//结账请求
				$data['num'] = $tabTempInfo['num'];
				$data['floorname'] = $floorName;
				$data['createtime'] = time();
				$re = $settle_db->add($data);
				if($re !== false){
					$status = 1;
					cookie('tabId',null);
					cookie('tabNum',null);
					cookie('uid',null);
					cookie('uname',null);
				}
			}
		}
		$this->ajaxReturn(array('status'=>$status));
	}
}
