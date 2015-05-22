

<?php
/**
 * 餐桌操作类
 * @author Tony
 *
 */
class TableAction extends CommAction {

    /**
     * 首页
     * @author Tony
     */
    public function index(){
        $this->tableList();
    }


    /**
     * 全部餐桌列表
     * @author Tony
     */
    public function tableList(){
    	$nowPage = isset($_GET['p'])?$_GET['p']:1;
    	$search['type'] = $_REQUEST['type'];
    	$search['typevalue'] = $_REQUEST['typevalue'];
    	$search['floor'] = intval($_REQUEST['floor']);
    	$this->assign('search',$search);
    	$tab_db = D('tab');
    	$floor_db = D('floor');
	    $floorInfo = $floor_db->select();
    	$floorList = getMenuType($floorInfo,'┠');
    	$this->assign('floorList',$floorList);
    	import("ORG.Util.Page");// 导入分页类
    	if($search['typevalue'] || $search['floor']){
	    	$where = '1';
	    	$wherePar = '';
	    	if($search['type'] && $search['typevalue']){
	    		if($search['type'] == 'tabname'){
		    		$where .= " and tabname like '%".$search['typevalue']."%'";
	    		}elseif($search['type'] == 'num'){
	    			$where .= ' and num='.$search['typevalue'];
	    		}
	    		$wherePar .= "&".$search['type']."=".$search['type']."&typevalue=".$search['typevalue'];
	    	}
	    	if($search['floor']){
	    		$parentidStr = '';
	    		foreach($floorInfo as $k => $v){
	    			if($v['parentid'] == $search['floor']){
	    				$parentidStr .=$v['id'].',';
	    			}
	    		}
	    		$parentidStr = trim($parentidStr,',');
	    		if($parentidStr){
	    			$parentidStr .= ','.$search['floor'];
	    		}else{
	    			$parentidStr = $search['floor'];
	    		}
	    		$where .= " and floor_num in(".$parentidStr.")";
	    		$wherePar .= "&floor=".$search['floor'];
	    	}
	    	
	    	$count      = $tab_db->where($where)->count();// 查询满足要求的总记录数
	    	$this->assign('count',$count);
	    	$Page       = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
	    	//     	print_r($Page);die;
	    	$Page->setNowPage($nowPage);
	    	$Page->parameter = $wherePar;
	    	$show       = $Page->show();// 分页显示输出
	    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
	    	$list = $tab_db->where($where)->order('id desc')->page($nowPage.','.$Page->listRows)->select();
	    	//     	echo $tab_db->getLastSql();die;
	    	$this->assign('page',$show);// 赋值分页输出
	    	$floorList = getMenuType($floorInfo,'┠');
	    	$this->assign('floorList',$floorList);
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
	    	$tpl = 'tableList';
    	}else{
	    	$count      = $floor_db->where('parentid=0')->count();
	    	$this->assign('count',$count);
	    	$Page       = new Page($count,1);
	    	$Page->config['header'] = '页';
	    	$Page->setNowPage($nowPage);
	    	$show       = $Page->show();// 分页显示输出
	    	$nowParent = $floor_db->where('parentid=0')->order('id asc')->limit($nowPage-1,1)->select();
	    	$parentId = $nowParent[0]['id'];
	    	$list = $floor_db->where('parentid='.$parentId)->order('id desc')->select();
	    	$list[] = $nowParent[0];
	    	$this->assign('page',$show);// 赋值分页输出
	    	$list = getMenuType($list);
	    	unset($list[0]);
	    	foreach($list as $k => $v){
	    		if($v['parentid']){
	    			$list[$k]['view'] = $tab_db->where('floor_num='.$v['id'])->select();
	    		}
	    	}
	    	$tpl = 'tableTypeList';
    	}    	
    	$this->assign('list',$list);
    	$this->display($tpl);
    }
    
    /**
     * 全部区域
     * @author Tony
     */
    public function areaList(){
    	$nowPage = isset($_GET['p'])?$_GET['p']:1;
    	$floor_db = D('floor');
    	import("ORG.Util.Page");// 导入分页类
    	$count      = $floor_db->where('parentid=0')->count();// 查询满足要求的总记录数
    	$this->assign('count',$count);
    	$Page       = new Page($count,1);// 实例化分页类 传入总记录数和每页显示的记录数
    	//     	print_r($Page);die;
    	$Page->setNowPage($nowPage);
    	$show       = $Page->show();// 分页显示输出
    	$nowParent = $floor_db->where('parentid=0')->order('id asc')->limit($nowPage-1,1)->select();
    	$parentId = $nowParent[0]['id'];
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $floor_db->where('parentid='.$parentId)->order('id desc')->select();
        $list[] = $nowParent[0];
    	//     	echo $tab_db->getLastSql();die;
    	$this->assign('page',$show);// 赋值分页输出
    	$list = getMenuType($list);
    	$admin_db = D('admin');
    	foreach($list as $k => $v){
    		if($v['adminid']){
    			$tempInfo = $admin_db->where('id in('.$v['adminid'].')')->field('nickname')->select();
    			foreach($tempInfo as $tk => $tv){
    				if(!empty($tv['nickname'])){
    					$list[$k]['adminName'] .= $tv['nickname'].',';
    				}
    			}
    			$list[$k]['adminName'] = trim($list[$k]['adminName'],',');
    		}
    	}
    	$this->assign('list',$list);
    	$this->display('areaList');
    }

    /**
     * 编辑区域（包括添加）
     * @author Tony
     */
    public function editArea(){
    	$targetid = intval($_REQUEST['targetid']);
    	$floor_db = D('floor');
    	$method = $this->isPost();
    	if($method){
    		$adminidStatus = intval($this->_post('adminidStatus'));//如果有值，管理区域管理员未清空，如果没值，管理员已清空
    		$data['name'] = $this->_post('name');
	    	$adminid = intval($this->_post('adminid'));
    		$data['parentid'] = intval($this->_post('floor'));
    		$data['createtime']=time();
    		if(!$data['name']){
    			$this->error('区域名称不能为空，请输入！',"__APP_PATH__/index.php?g=Admin&m=Table&a=editArea");die;
    		}
    		if($targetid){
    			$str = '编辑';
	    		$data['updatetime'] = time();
	    		$re = $floor_db->where('id='.$targetid)->save($data);
    		}else{
    			if(!$data['parentid']){
    				$count = $floor_db->where('parentid=0')->count();
    				if($count >= 2){
    					$this->error('只能创建2个一级区域！',"__APP_PATH__/index.php?g=Admin&m=Table&a=editArea");die;
    				}
    			}
    			$str = '添加';
	    		$data['createtime'] = time();
	    		$targetid = $re = $floor_db->add($data);
    		}    	
    		if($re!==false){
    			$admin_db = D('admin');
    			$floorInfo = $floor_db->where('id='.$targetid)->find();
    			if(!$adminidStatus){
    				//查看该区域是否已经有操作员	added by Tony
    				if(strpos($floorInfo['adminid'],',')){
    					$adminidArray = explode(',',$floorInfo['adminid']);
    					foreach ($adminidArray as $k => $v){
    						$tempAdminFloor = $admin_db->where('id='.$v)->field('floor_id')->find();
    						//查看此操作员是否已经可操作别的区域		added by Tony
    						if(strpos($tempAdminFloor['floor_id'],',')){
    							$targetidArray = explode(',',$tempAdminFloor['floor_id']);
    							if(in_array($targetid,$targetidArray)){
    								foreach($targetidArray as $kk => $vv){
    									if($vv == $targetid){
    										unset($targetidArray[$kk]);
    									}
    								}
    								$targetids = implode(",",$targetidArray);;
    							}
    						}
    						$admin_db->where('id='.$v)->save(array('floor_id'=>$targetids));
    					}
    				}
    				$re = $floor_db->where('id='.$floorInfo['id'])->save(array('adminid'=>0));
    				if($re !== false){
    					unset($floorInfo['adminid']);
    				}
    			}
    			if($adminid){
    				$parentid = $floorInfo['parentid'];
    				if($parentid){
    					$parentFloorInfo = $floor_db->where('id='.$parentid)->find();
    				}
    				$parent_adminid = $parentFloorInfo['adminid'];
    				if(strpos($parent_adminid,',')){
    					$parent_adminidArray = explode(',',$parent_adminid);
    					if(in_array($adminid,$parent_adminidArray)){
    						$str2 = '!<br/>分配操作员失败（该账户可以管辖此区域）';
    					}
    				}elseif($adminid == $parent_adminid){
    					$str2 = '!<br/>分配操作员失败（该账户可以管辖此区域）';
    				}else{
    					$adminFloor = $admin_db->where('id='.$adminid)->field('floor_id')->find();
    					//查看此操作员是否已经可操作别的区域		added by Tony
    					if(strpos($adminFloor['floor_id'],',')){
    						$targetidArray = explode(',',$adminFloor['floor_id']);
    						if(!in_array($targetid,$targetidArray)){
    							$targetids = trim($adminFloor['floor_id']).','.$targetid;
    						}
    					}else{
    						if($adminFloor['floor_id'] != $targetid){
    							$targetids = $adminFloor['floor_id'].','.$targetid;
    						}else{
    							$targetids = $targetid;
    						}
    					}
    					$targetids = trim($targetids,',');
    					$admin_db->where('id='.$adminid)->save(array('floor_id'=>$targetids));
    					//查看该区域是否已经有操作员	added by Tony
    					if(strpos($floorInfo['adminid'],',')){
    						$adminidArray = explode(',',$floorInfo['adminid']);
    						if(!in_array($adminid,$adminidArray)){
	    						$adminids = trim($floorInfo['adminid']).','.$adminid;
    						}else{
    							$adminids = $floorInfo['adminid'];
    						}
    					}else{
    						if($floorInfo['adminid'] != $adminid){
	    						$adminids = $floorInfo['adminid'].','.$adminid;
    						}else{
    							$adminids = $adminid;
    						}
    					}
    					$adminids = trim($adminids,',');
	    				$floor_db->where('id='.$targetid)->save(array('adminid'=>$adminids));
    				}
    			}
    			$this->success($str.'成功'.$str2,U('Table/areaList'));
    		}else{
    			$this->success($str.'失败',U('Table/editArea'));
    		}
    	}else{
    		$admin_db = D('admin');
    		if($targetid){
    			$title = '编辑';
		    	$info = $floor_db->where('id='.$targetid)->find();
		    	$adminids = $info['adminid'];
		    	if($adminids){
		    		$adminidArr = explode(',',$adminids);
		    		$admin_db = D('admin');
		    		$adminArray = array();
		    		foreach($adminidArr as $k => $v){
		    			$tempAdminInfo = $admin_db->where('id='.$v)->field('nickname')->find();
		    			$adminArray[] = $tempAdminInfo['nickname'];
		    		}
		    		$info['adminStr'] = trim(implode(',', $adminArray),',');
		    	}
    		}else{
    			$title = '添加';
    		}
    		$adminList = $admin_db->select();
    		$this->assign('adminList',$adminList);
    		$list = $floor_db->where('parentid=0')->select();
    		$count = count($list);//只能创建2个大区
    		$this->assign('count',$count);
	    	$this->assign('list',$list);
    		$this->assign('info',$info);
	    	$this->assign('title',$title);
	    	$this->display('editArea');
    	}
    }
    
    /**
     * 编辑餐桌（包括添加餐桌）
     * @author Tony
     */
    public function editTable(){
// 	    $adminUser = $this->info['adminuser'];
// 	    if($adminUser != 'admin'){
// 	    	$this->error('该账户没有权限！',"__APP_PATH__/index.php?g=Admin&m=System&a=adminList");die;
// 	    }
// 	    $this->assign('priv',$this->priv);
	    $targetid = intval($_REQUEST['targetid']);
	    $method = $this->isPost();
	    $tab_db = D('tab');
	    $floor_db = D('floor');
	    $floorInfo = $floor_db->select();
	    $floorList = getMenuType($floorInfo,'┠');
	    $this->assign('floorList',$floorList);
	    if($method){
	    	$info['t_id'] = $targetid;
	    	$info['num'] = intval($this->_post('num'));
	    	$info['chair'] = intval($this->_post('chair'));
	    	$info['status'] = intval($this->_post('status'));
	    	$info['floor_num'] = intval($this->_post('floor_num'));
	    	$info['tabname'] = $this->_post('tabname');
	    	$this->check_from($info,"__APP_PATH__/index.php?g=Admin&m=Table&a=editTable");
	    	$tabData['tabname'] = $info['tabname'];
	    	$tabData['num'] = $info['num'];
	    	$tabData['chair'] = $info['chair'];
	    	$tabData['status'] = $info['status'] ? $info['status'] : 1;
	    	$tabData['floor_num'] = $info['floor_num'];
	    	//     		print_r($adminData);die;
	    	if($info['t_id']){
	    		$str = '编辑';
	    		$tabData['updatetime'] = time();
	    		$re = $tab_db->where('id='.$targetid)->save($tabData);
	    	}else{
	    		$str = '添加';
	    		$tabData['createtime'] = time();
	    		$re = $tab_db->add($tabData);
	    	}
	    	if($re!==false){
	    		$this->success($str.'成功',U('Table/tableList'));
	    	}else{
	    		$this->success($str.'失败',U('Table/editTable'));
	    	}
	    }else{
	    	$info = array();
	    	if($targetid){
	    		$title = '编辑';
	    		$info = $tab_db->where('id='.$targetid)->find();
	    	}else{
	    		$title = '添加';
	    		$tempInfo = $tab_db->order('num desc')->find();
	    		$info['num'] = $tempInfo['num']+1;
	    		$info['status'] = 1;
	    	}
	    	$this->assign('targetid',$targetid);
	    	$this->assign('title',$title);
	    	$this->assign('info',$info);
	    	$this->display('editTable');
	    }
    }
    
    /**
     * 删除区域
     * @author Tony
     */
    public function delArea(){
    	$adminUser = $this->info['adminuser'];
    	if($adminUser == 'admin'){
	    	$ids = !empty($_REQUEST['ids'])?$_REQUEST['ids']:'';
	    	$re = $this->delApi('floor',$ids,'id');
    	}else{
    		$this->error('该账户没有权限！',"__APP_PATH__/index.php?g=Admin&m=System&a=adminList");
    	}
    }
    
    /**
     * 删除餐桌
     * @author Tony
     */
    public function delTable(){
    	$adminUser = $this->info['adminuser'];
    	if($adminUser == 'admin'){
    		$ids = !empty($_REQUEST['ids'])?$_REQUEST['ids']:'';
    		$re = $this->delApi('tab',$ids,'id');
    	}else{
    		$this->error('该账户没有权限！',"__APP_PATH__/index.php?g=Admin&m=System&a=adminList");
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
			if(!$info['num']){
				$this->error('桌位号码不可为空，请输入！',$jumpUrl);die;
			}
			if(!$info['chair']){
				$this->error('椅子数量不可为空，请输入！',$jumpUrl);die;
			}
			if(!$info['floor_num']){
				$this->error('请选择所属区域！',$jumpUrl);die;
			}
			if($info['floor_num'] && $info['num']){
				$tabNums = D('tab')->where('floor_num='.$info['floor_num'])->field('num')->select();
				foreach($tabNums as $k => $v){
					if($info['num'] == $v['num']){
						$this->error('所选区域下已有'.$info['num'].'号桌位，请重新输入！',$jumpUrl);die;
						break;
					}
				}
			}
		}    	
    }
    
    /**
     * ajax改变餐桌状态
     * @author Tony
     */
    public function modifStatus(){
    	$id = intval($this->_post('id'));
    	$status = intval($this->_post('value'));
    	$result = 0;
    	if($id && $status){
    		$tab_db = D('tab');
    		$tabInfo = $tab_db->where('id='.$id)->field('id,status')->find();
    		if($tabInfo['status'] == 3){
    			$tabId = $tabInfo['id'];
    			D('temp')->where('t_id='.$tabId)->delete();
    			D('settle')->where('t_id='.$tabId)->delete();
    			D('user')->where('t_id='.$tabId)->delete();
    		}
    		$re = $tab_db->where('id='.$id)->save(array('status'=>$status));
	    	$result = $re !== false ? 1 : 0;
    	}
		$this->ajaxReturn($result);
    }

}