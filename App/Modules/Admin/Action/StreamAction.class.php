<?php
/**
 * 流水统计类
 * @author Tony
 *
 */
class StreamAction extends CommAction {

    /**
     * 首页
     * @author Tony
     */
    public function index(){
        $this->day();
    }


    /**
     * 日流水
     * @author Tony
     */
    public function day(){
    	$title = '今日流水账目';
    	$tit = '日';
    	$this->assign('tit',$tit);
    	$this->assign('title',$title);
    	$startTime = strtotime(date("Y-m-d"));
    	$endTime = time();
    	$nowPage = isset($_GET['p'])?$_GET['p']:1;
    	$orderform_db = D('orderform');
    	$where= 'createtime >'.$startTime.' and createtime <'.$endTime ;
    	import("ORG.Util.Page");// 导入分页类
        //筛选管理员权限
        $adminid=$this->adminid;
        $whereNew=$this->getWhere($adminid,$where,'t_id');
    	$count      = $orderform_db->where($whereNew)->count();// 查询满足要求的总记录数
    	$this->assign('count',$count);
    	$Page       = new Page($count,7);// 实例化分页类 传入总记录数和每页显示的记录数
    	//     	print_r($Page);die;
    	$Page->setNowPage($nowPage);
    	$show       = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $orderform_db->where($whereNew)->order('createtime desc')->page($nowPage.','.$Page->listRows)->select();
    	$this->assign('page',$show);// 赋值分页输出
    	$admin_db = D('admin');
    	$tab_db = D('tab');
    	$floor_db = D('floor');
    	$floorList = $floor_db->select();
    	foreach($list as $k => $v){
    		$tabInfo = getTabInfoByTabId($v['t_id']);
    		$list[$k]['tabNum'] = $tabInfo['num'];
    		foreach($floorList as $kk => $vv){
    			if($tabInfo['floor_num'] == $vv['id']){
    				$adminids = $vv['adminid'];
    			}
    		}
    		
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
    	$allPrice = $orderform_db->where($whereNew)->sum('price');//总计
    	$allPrice = $allPrice ? $allPrice : 0;
    	$this->assign('allPrice',$allPrice);
    	$this->assign('list',$list);
        $this->display('list');
    }

    
    /**
     * 周流水
     * @author Tony
     */
    public function week(){
    	$title = '本周流水账目';
    	$tit = '周';
    	$this->assign('tit',$tit);
    	$this->assign('title',$title);
    	$startTime = strtotime(date('Y-m-d', time()-86400*date('w',time())+(date('w',time())>0?86400:-/*6*86400*/518400)));
    	$endTime = strtotime(date('Y-m-d', time()-86400*date('w',time())+(date('w',time())>0?86400:-/*6*86400*/518400)))+ /*6*86400*/518400;
    	$nowPage = isset($_GET['p'])?$_GET['p']:1;
    	$orderform_db = D('orderform');
        $where= 'createtime >'.$startTime.' and createtime <'.$endTime ;
    	import("ORG.Util.Page");// 导入分页类
        //筛选管理员权限
        $id=$this->adminid;
        $whereNew=$this->getWhere($id,$where,'t_id');
    	$count      = $orderform_db->where($whereNew)->count();// 查询满足要求的总记录数
    	$this->assign('count',$count);
    	$Page       = new Page($count,7);// 实例化分页类 传入总记录数和每页显示的记录数
    	//     	print_r($Page);die;
    	$Page->setNowPage($nowPage);
    	$show       = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $orderform_db->where($whereNew)->order('createtime desc')->page($nowPage.','.$Page->listRows)->select();
    	$this->assign('page',$show);// 赋值分页输出
    	$admin_db = D('admin');
    	$tab_db = D('tab');
    	$floor_db = D('floor');
    	$floorList = $floor_db->select();
    	foreach($list as $k => $v){
    		$tabInfo = getTabInfoByTabId($v['t_id']);
    		$list[$k]['tabNum'] = $tabInfo['num'];
    		foreach($floorList as $kk => $vv){
    			if($tabInfo['floor_num'] == $vv['id']){
    				$adminids = $vv['adminid'];
    			}
    		}
    		
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
    	$allPrice = $orderform_db->where($whereNew)->sum('price');//总计
    	$allPrice = $allPrice ? $allPrice : 0;
    	$this->assign('allPrice',$allPrice);
    	$this->assign('list',$list);
        $this->display('list');
    }
    
    
    /**
     * 月流水
     * @author Tony
     */
    public function month(){
    	$title = '本月流水账目';
    	$tit = '月';
    	$this->assign('tit',$tit);
    	$this->assign('title',$title);
//     	$month = intval($_REQUEST['month']);
   		$month = date("m");
    	$startTime = strtotime(date("Y-m-d H:i:s",mktime(0, 0 , 0,$month,1,date("Y"))));
	    $endTime = time();
    	$this->assign('month',$month);
    	$nowPage = isset($_GET['p'])?$_GET['p']:1;
    	$orderform_db = D('orderform');
        $where= 'createtime >'.$startTime.' and createtime <'.$endTime ;
    	import("ORG.Util.Page");// 导入分页类
        //筛选权限条件
        $adminid=$this->adminid;
        $whereNew=$this->getWhere($adminid,$where,'t_id');
    	$count      = $orderform_db->where($whereNew)->count();// 查询满足要求的总记录数
    	$this->assign('count',$count);
    	$Page       = new Page($count,7);// 实例化分页类 传入总记录数和每页显示的记录数
    	//     	print_r($Page);die;
    	$Page->setNowPage($nowPage);
    	$show       = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $orderform_db->where($whereNew)->order('createtime desc')->page($nowPage.','.$Page->listRows)->select();
    	$this->assign('page',$show);// 赋值分页输出
    	$admin_db = D('admin');
    	$tab_db = D('tab');
    	$floor_db = D('floor');
    	$floorList = $floor_db->select();
    	foreach($list as $k => $v){
    		$tabInfo = getTabInfoByTabId($v['t_id']);
    		$list[$k]['tabNum'] = $tabInfo['num'];
    		foreach($floorList as $kk => $vv){
    			if($tabInfo['floor_num'] == $vv['id']){
    				$adminids = $vv['adminid'];
    			}
    		}
    		
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
    	$allPrice = $orderform_db->where($whereNew)->sum('price');//总计
    	$allPrice = $allPrice ? $allPrice : 0;
    	$this->assign('allPrice',$allPrice);
    	$this->assign('list',$list);
        $this->display('list');
    }
    
    
    /**
     * 总流水
     * @author Tony
     */
    public function all(){
    	$searchStartTime = $_REQUEST['searchStartTime'] ? $_REQUEST['searchStartTime'] : 0;
    	$searchEndTime = $_REQUEST['searchEndTime'] ? $_REQUEST['searchEndTime'] : 0;
    	$where = '';
    	if($searchStartTime || $searchEndTime){
    		$startTime = strtotime($searchStartTime);
    		$endTime = strtotime($searchEndTime);
    		$where .= 'createtime >'.$startTime.' and createtime <'.$endTime  ;
    	}
    	$nowPage = isset($_GET['p'])?$_GET['p']:1;
    	$orderform_db = D('orderform');
    	import("ORG.Util.Page");// 导入分页类
        //筛选管理员权限
        $adminid=$this->adminid;
        $whereNew=$this->getWhere($adminid,$where,'t_id');
    	$count      = $orderform_db->where($whereNew)->count();// 查询满足要求的总记录数
    	$this->assign('count',$count);
    	$Page       = new Page($count,7);// 实例化分页类 传入总记录数和每页显示的记录数
    	//     	print_r($Page);die;
    	$Page->setNowPage($nowPage);
    	$show       = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $orderform_db->where($whereNew)->order('createtime desc')->page($nowPage.','.$Page->listRows)->select();
    	$this->assign('page',$show);// 赋值分页输出
    	$admin_db = D('admin');
    	$tab_db = D('tab');
    	$floor_db = D('floor');
    	$floorList = $floor_db->select();
    	foreach($list as $k => $v){
    		$tabInfo = getTabInfoByTabId($v['t_id']);
    		$list[$k]['tabNum'] = $tabInfo['num'];
    		foreach($floorList as $kk => $vv){
    			if($tabInfo['floor_num'] == $vv['id']){
    				$adminids = $vv['adminid'];
    			}
    		}
    		
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
    	$allPrice = $orderform_db->where($whereNew)->sum('price');//总计
    	$allPrice = $allPrice ? $allPrice : 0;
    	$this->assign('allPrice',$allPrice);
    	$this->assign('list',$list);
        $this->display('alllist');
    }
    
    /**
     * 单条数据详情
     * @author Tony
     */
    public function detail(){
    	$targetid = intval($this->_get('id'));
    	$table_id = intval($this->_get('t_id'));
    	$tab_db = D('tab');
    	$tabInfo = $tab_db->where('id='.$table_id)->field('num')->find();
    	$tabNum = $tabInfo['num'];
    	$this->assign('tabNum',$tabNum);
    	if($targetid){
       		$nowPage = isset($_GET['p'])?$_GET['p']:1;
    		$stream_db = D('stream');
    		$where['o_id'] = $targetid;
    		import("ORG.Util.Page");// 导入分页类
    		$count      = $stream_db->where($where)->count();// 查询满足要求的总记录数
    		$this->assign('count',$count);
    		$Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
    		//     	print_r($Page);die;
    		$Page->setNowPage($nowPage);
    		$show       = $Page->show();// 分页显示输出
    		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    		$list = $stream_db->where($where)->page($nowPage.','.$Page->listRows)->select();
    		$this->assign('page',$show);// 赋值分页输出
    		$allPrice = $stream_db->where($where)->sum('price*amount');//总计
    		$allPrice = $allPrice ? $allPrice : 0;
    		$this->assign('allPrice',$allPrice);
    		
    		foreach($list as $k => $v){
    			$list[$k]['allPrice'] = $v['price']*$v['amount'];
    			if($list[$k]['allPrice'] && strpos($list[$k]['allPrice'],'.') === false){
    				$list[$k]['allPrice'].='.00';
    			}
    		}
	    	$this->assign('list',$list);
	    	$this->display('detail');
    	}
    }
}