<?php
class IndexAction extends CommAction {
    /**
     * author Tony
     * 2013-09-05
     * 后台首页
     */
    public function index(){
        $this->display('default');

    }

    /**
     * 后台右则默认页
     */
    public function main(){

        $this->tabList();
    }
    /**
     * 桌号列表
     * @author Tony
     */
    public function tabList(){
        $tab_db = D('tab');
        $admin_db = D('admin');
        $floor_db = D('floor');
        $adminid= $this->adminid;
        $floorInfo = $floor_db->select();
        $floorList = getMenuType($floorInfo,'┠');
        $nowPage = isset($_GET['p'])?$_GET['p']:1;
        import("ORG.Util.Page");// 导入分页类
        $where=$this->getWhere($adminid,'','id');
        $count=$tab_db->where($where)->count();
        $Page       = new Page($count,7);// 实例化分页类 传入总记录数和每页显示的记录数
            //     	print_r($Page);die;
        $Page->setNowPage($nowPage);
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $tab_db->where($where)->order('id asc')->page($nowPage.','.$Page->listRows)->select();
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
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->display('tabList');
    }

    /**
     * 空闲中桌位
     * @author Tony
     */
    public function free(){
        $tab_db = D('tab');
        $admin_db = D('admin');
        $floor_db = D('floor');
        $adminid= $this->adminid;
        $floorInfo = $floor_db->select();
        $floorList = getMenuType($floorInfo,'┠');
        $nowPage = isset($_GET['p'])?$_GET['p']:1;
        import("ORG.Util.Page");// 导入分页类
        $where=$this->getWhere($adminid,'status=1','id');
        $count=$tab_db->where($where)->count();
        $Page       = new Page($count,7);// 实例化分页类 传入总记录数和每页显示的记录数
        //     	print_r($Page);die;
        $Page->setNowPage($nowPage);
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $tab_db->where($where)->order('id asc')->page($nowPage.','.$Page->listRows)->select();
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
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->display('tabList');
    }

    /**
     * 使用中桌位
     * @author Tony
     */
//    public function employ(){
//    	$temp_db = D('temp');
//        $admin_db = D('admin');
//        $floor_db = D('floor');
//        $adminid=session('adminid');
//        $arr=$this->screen($temp_db,$adminid);
//        $this->assign('count',$arr['count']);
//        $this->assign('page',$arr['show']);
//        $this->assign('list',$arr['list']);
//    	$this->display('employ');
//    }

    /**
     * 使用中（临时表中）桌位详细
     * @author Tony
     */
    public function tempTabDetail(){
        $tab_id = $this->_get('t_id');
        $tabInfo = getTabInfoByTabId($tab_id);
        $tabNum = $tabInfo['num'];
        $this->assign('tabNum',$tabNum);
        $temp_db = D('temp');
        $nowPage = isset($_GET['p'])?$_GET['p']:1;
        $where['t_id'] = $tab_id;
        import("ORG.Util.Page");// 导入分页类
        $count      = $temp_db->where($where)->count();// 查询满足要求的总记录数
        $this->assign('count',$count);
        $Page       = new Page($count,7);// 实例化分页类 传入总记录数和每页显示的记录数
        //     	print_r($Page);die;
        $Page->setNowPage($nowPage);
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $temp_db->where($where)->order('id asc')->page($nowPage.','.$Page->listRows)->select();
        $this->assign('page',$show);// 赋值分页输出
        foreach($list as $k => $v){
            $list[$k]['allPrice'] = $v['price']*$v['amount'];
            if($list[$k]['allPrice'] && strpos($list[$k]['allPrice'],'.') === false){
                $list[$k]['allPrice'].='.00';
            }
        }
        $allPrice = $temp_db->where($where)->sum('price*amount');//总计
        $allPrice = $allPrice ? $allPrice : 0;
        $this->assign('allPrice',$allPrice);
		$settle_db = D('settle');
		$settleInfo = $settle_db->where('t_id='.$tab_id)->find();
		$this->assign('settleInfo',$settleInfo);
        $this->assign('list',$list);
        $this->display('tempTabDetail');
    }
    
    /**
     * 修改使用中（临时表中）桌位信息
     * @author Tony
     */
    public function updateTempTab(){
    	$id = $_REQUEST['id'];
    	$amount = $_REQUEST['amount'];
    	if($id && $amount){
	    	$temp_db = D('temp');
	    	$data['amount'] = $amount;
	    	$re = $temp_db->where('id='.$id)->save($data);
	    	$result['status'] = $re !== false ? 1 : 0;
    	}else{
    		$result['status'] = 0;
    	}
	    $this->ajaxReturn($result);
    }
    
    /**
     * 删除使用中（临时表中）桌位信息
     * @author Tony
     */
    public function delTempTab(){
    	$ids = !empty($_REQUEST['id'])?$_REQUEST['id']:'';
    	$this->delApi('temp', $ids, "id");
    }
    

    /**
     * 已预定桌位
     * @author Tony
     */
    public function reserve(){
        $tab_db = D('tab');
        $admin_db=D('admin');
        $nowPage = isset($_GET['p'])?$_GET['p']:1;
        import("ORG.Util.Page");// 导入分页类
        $adminid=session('adminid');
        $where=$this->getWhere($adminid,'status=2','floor_num');
        $count      = $tab_db->where($where)->count();// 查询满足要求的总记录数
        $this->assign('count',$count);
        $Page       = new Page($count,7);// 实例化分页类 传入总记录数和每页显示的记录数
            //     	print_r($Page);die;
        $Page->setNowPage($nowPage);
        $show       = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $tab_db->where($where)->order('id asc')->page($nowPage.','.$Page->listRows)->select();
        $this->assign('page',$show);// 赋值分页输出
        $admin_db = D('admin');
        foreach($list as $k => $v){
            $tempInfo = $admin_db->where('id='.$v['adminid'])->field('nickname')->find();
            $list[$k]['adminName'] = $tempInfo['nickname'];
        }
        //P($list);die;
        $this->assign('list',$list);
        $this->display('reserve');
    }

    /**
     *  新訂單
     * @author William
     */
    public function newOrder(){
        $this->display('newOrder');
    }
    /**
     * 未结账桌位
     * @author Tony
     */
    public function employ(){
        $temp_db = D('temp');
        $admin_db = D('admin');
        $floor_db = D('floor');
        $nowPage = isset($_GET['p'])?$_GET['p']:1;
        import("ORG.Util.Page");// 导入分页类
        $adminid=$this->adminid;
        $where=$this->getWhere($adminid,'`check`<3','t_id');
        $tempId = $temp_db->field('id')->where($where)->group('t_id')->select();
        $count = count($tempId);
        $this->assign('count',$count);
        if($count){
            $Page       = new Page($count,7);// 实例化分页类 传入总记录数和每页显示的记录数
            //     	print_r($Page);die;
            $Page->setNowPage($nowPage);
            $show       = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = $temp_db->where($where)->field('sum(price*amount) as allPrice,t_id,check')->group('t_id')->order('id asc')->page($nowPage.','.$Page->listRows)->select();
            $this->assign('page',$show);// 赋值分页输出
            $floorInfo = $floor_db->select();
            $floorList = getMenuType($floorInfo,'┠');
            $tab_db = D('tab');
            foreach($list as $k => $v){
                $tabTempInfo = $tab_db->where('id='.$v['t_id'])->field('floor_num')->find();
                foreach($floorList as $kk => $vv){
                    if($tabTempInfo['floor_num'] == $vv['id']){
                    	$adminids = $vv['adminid'];
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
                $tabInfo = getTabInfoByTabId($v['t_id']);
                $list[$k]['tabNum'] = $tabInfo['num'];
            	if($adminids){
                    $tempInfo = $admin_db->where('id in('.$adminids.')')->field('nickname')->select();
                    foreach($tempInfo as $tk => $tv){
	                	if(!empty($tv['nickname'])){
	                		$list[$k]['adminName'] .= $tv['nickname'].',';
	                	}
	                }
	                $list[$k]['adminName'] = trim($list[$k]['adminName'],',');
                }
            }
	        $settle_db = D('settle');
	        $settleList = $settle_db->select();
	        foreach($list as $k => $v){
	        	foreach($settleList as $kk => $vv){
	        		if($v['t_id'] == $vv['t_id']){
	        			$list[$k]['hint'] = $vv['type'];
	        		}
	        	}	
	        }
        }
        $this->assign('list',$list);
        $this->display('employ');
    }

    /**
     * 已结账订单
     * @author Tony
     */
    public function invoicing(){
        $nowPagePrice='';
        $nowPage = isset($_GET['p'])?$_GET['p']:1;
        $admin_db = D('admin');
        $floor_db = D('floor');
        $orderform_db = D('orderform');
        $tab_db = D('tab');
        import("ORG.Util.Page");// 导入分页类
        $adminid=session('adminid');
        $floorInfo = $floor_db->select();
        $floorList = getMenuType($floorInfo,'┠');
        $adminid=$this->adminid;
        $where=$this->getWhere($adminid,'','t_id');
        $tabDi=$tab_db->where($where)->select();
            $count      = $orderform_db->where($where)->count();// 查询满足要求的总记录数
            $this->assign('count',$count);
            $Page       = new Page($count,7);// 实例化分页类 传入总记录数和每页显示的记录数
            //     	print_r($Page);die;
            $Page->setNowPage($nowPage);
            $show       = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = $orderform_db->where($where)->order('createtime desc')->page($nowPage.','.$Page->listRows)->select();
        $this->assign('page',$show);// 赋值分页输出
            foreach($list as $k => $v){
                $tabTempInfo = $tab_db->where('id='.$v['t_id'])->field('floor_num')->find();
                foreach($floorList as $kk => $vv){
                    if($tabTempInfo['floor_num'] == $vv['id']){
                    	$adminids = $vv['adminid'];
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
                $tabInfo = getTabInfoByTabId($v['t_id']);
                $list[$k]['tabNum'] = $tabInfo['num'];
                if($adminids){
                    $tempInfo = $admin_db->where('id in('.$adminids.')')->field('nickname')->select();
                    foreach($tempInfo as $tk => $tv){
	                	if(!empty($tv['nickname'])){
	                		$list[$k]['adminName'] .= $tv['nickname'].',';
	                	}
	                }
	                $list[$k]['adminName'] = trim($list[$k]['adminName'],',');
                }
                $nowPagePrice += $v['price'];
            }
        if($nowPagePrice && strpos($nowPagePrice,'.') === false){
            $nowPagePrice.='.00';
        }
        $nowPagePrice = $nowPagePrice ? $nowPagePrice : 0;
        $this->assign('nowPagePrice',$nowPagePrice);
        $allPrice = $orderform_db->sum('price');//总计
        $allPrice = $allPrice ? $allPrice : 0;
        $this->assign('allPrice',$allPrice);
        $this->assign('list',$list);
        $this->display('invoicing');
    }
    //后台当前位置面包屑处理
    public function public_current_pos() {
        echo $this->current_pos($_GET['menuid']);
        exit;
    }
    public function current_pos($id) {
        $menuArray = $this->menuArray;
        foreach($menuArray as $k => $v){
            if(strlen($id) < 3){
                if($id == $v['id']){
                    $str = $v['name'];
                }
            }else{
                foreach($v['view'] as $kk => $vv){
                    if($id == $vv['id']){
                        $str = $v['name'].' > '.$vv['name'];
                    }
                }
            }
        }
        return $str;
    }
    /**
     * author Tony
     * 2013-09-05
     * 后台头部
     */
    public function header(){
        $this->display('Public/header');
    }

    /**
     * author Tony
     * 2013-09-05
     * 后台左则
     */
    public function left(){
        $menuid = !empty($_REQUEST['menuid'])?trim($_REQUEST['menuid']): 1;
        $menuArray = $this->menuArray;
        foreach($menuArray as $k => $v){
            if($v['id'] == $menuid){
                $nowNode = $v;
                break;
            }
        }
        foreach($nowNode['view'] as $k => $v){
            $nowNode['view'][$k]['g'] = 'Admin';
            $nowNode['view'][$k]['m'] = $nowNode['val'];
            $nowNode['view'][$k]['a'] = $v['val'];
        }
// 		print_r($nowNode);die;
        $this->assign('nowNode',$nowNode);
        $this->display('left');
    }

    public function mid(){
    	$this->display('mid');
    }
    public function bottom(){
    	$this->assign('bottom');
    }
    
    /**
     * 打印订单
     * @author Tony
     */
    public function printer($tabId=null){
    	if($tabId){
    		$tabed_id = $tabId;
    	}else{
        	$tabed_id = $this->_get('t_id');
    	}
        if($tabed_id){
            $tab_id = $this->_get('t_id');
            $tabInfo = getTabInfoByTabId($tab_id);
            $tabNum = $tabInfo['num'];
            $list[$k]['floorNum'] = $tabInfo['floor_num'];
            $this->assign('tabNum',$tabNum);
            $temp_db = D('temp');
            $nowPage = isset($_GET['p'])?$_GET['p']:1;
            $where['t_id'] = $tab_id;
            import("ORG.Util.Page");// 导入分页类
            $count      = $temp_db->where($where)->count();// 查询满足要求的总记录数
            $this->assign('count',$count);
            $Page       = new Page($count,7);// 实例化分页类 传入总记录数和每页显示的记录数
            //     	print_r($Page);die;
            $Page->setNowPage($nowPage);
            $show       = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = $temp_db->where($where)->order('id asc')->page($nowPage.','.$Page->listRows)->select();
            $this->assign('page',$show);// 赋值分页输出
            foreach($list as $k => $v){
                $list[$k]['allPrice'] = $v['price']*$v['amount'];
                if($list[$k]['allPrice'] && strpos($list[$k]['allPrice'],'.') === false){
                    $list[$k]['allPrice'].='.00';
                }
            }
            //获得系统设置>本店设置
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
            $this->assign('logoInfo',$get_config_info);
            $allPrice = $temp_db->where($where)->sum('price*amount');//总计
            $allPrice = $allPrice ? $allPrice : 0;
            $this->assign('allPrice',$allPrice);

            $this->assign('list',$list);
            $this->display('printer');
        }
    }
    
    /**
     * 确定下单
     * @author Tony
     */
    public function order(){
    	$tabId = $this->_get('t_id');
    	if($tabId){
    		$temp_db = D('temp');
    		$temp_db->where('t_id='.$tabId)->save(array('check'=>2));
    		$str = '';
    		$time = time();
    		D('settle')->where('t_id='.$tabId.' and type = 1')->delete();
    		$this->printer($tabId);
    	}
    }
    
    /**
     * 确定结账
     * @author Tony
     */
    public function settle(){
    	$tabId = $this->_get('t_id');
    	if($tabId){
    		$str = '';
    		$temp_db = D('temp');
    		$stream_db = D('stream');
    		$orderform_db = D('orderform');
    		$menu_db = D('menu');
    		$streamData = array();
    		$orderformData = array();
    		$time = time();
    		$allPrice = $temp_db->where('t_id='.$tabId)->sum('price*amount');//总计 
    		$tempTimeInfo = $temp_db->where('t_id='.$tabId.' and starttime<>0')->order('starttime asc')->find();
    		$orderformData['t_id'] = $tabId;
    		$orderformData['price'] = $allPrice;
    		$orderformData['starttime'] = $tempTimeInfo['starttime'];
    		$orderformData['endtime'] = $time;
    		$orderformData['createtime'] = $time;
			$re = $orderform_db->add($orderformData);
			if($re !== false){
	    		$tempList = $temp_db->where('t_id='.$tabId.' and `check` > 0')->select();
	    		foreach($tempList as $k => $v){
	    			$streamData[$k]['t_id'] = $v['t_id'];
	    			$streamData[$k]['amount'] = $v['amount'];
	    			$streamData[$k]['u_id'] = $v['u_id'];
	    			$streamData[$k]['o_id'] = $re;
	    			if($v['u_id']){
	    				$tempMenuInfo = $menu_db->where('id='.$v['u_id'])->find();
	    				$streamData[$k]['price'] = $tempMenuInfo['price'];
	    				$streamData[$k]['uname'] = $tempMenuInfo['name'];
	    				$streamData[$k]['menutypeid'] = $tempMenuInfo['menutypeid'];
	    			}
	    			$streamData[$k]['status'] = $v['status'];
	    			$streamData[$k]['createtime'] = $time;
	    		}
	    		$re = $stream_db->addAll($streamData);
	    		if($re !== false){
    				D('tab')->where('id='.$tabId)->save(array('status'=>4));
	    			$temp_db->where('t_id='.$tabId)->delete();
	    			D('settle')->where('t_id='.$tabId)->delete();
	    			D('user')->where('t_id='.$tabId)->delete();
	    			$str = '成功';
	    		}
			}
			$this->success('结账'.$str,'index.php?g=Admin&m=Index&a=employ');
    	}
    }
}
