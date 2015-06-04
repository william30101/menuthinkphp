<?php
/**
 * Created by PhpStorm.
 * User: jwz
 * Date: 14-2-25
 * Time: 涓婂崍10:41
 * 前台调用类
 */

class IndexAction extends Action{
    protected $tabId;//桌的id
    protected $tabNum;//桌的号码
    protected $uname;//用户名字
    protected $uid;//用户的id
    public function __construct(){
        parent::__construct();
        $this->uid   = session('uid');
        $this->tabId=session('tab');
        $this->tabNum=session('tabNum');
        $this->adminName=session('user');
        $this->uname=session('uname');
        $this->user=session('user');
        $this->assign('tab',$this->tabId);
        $this->assign('tabNum',$this->tabNum);
        $this->assign('uname',$this->uname);
    }


    //首页视图
    public function index(){
        $this->Img=$this->logoImg();
        if($this->tabId && $this->uname && $this->uid){
            $status=M('tab')->field('status')->where('id='.$this->tabId)->find();
            //判断用户是否下过订单
            $userInfo=M('temp')->where('uname='.$this->uname.' and check>0 and t_id='.$this->tabId)->find();
            if($userInfo && $status['status']==3){
                $this->redirect(GROUP_NAME.'/Index/menu');

            }
        }
        else
        {
            if(session('tab')){
                $this->tabCookie=1;
            }


        }
        $this->display();
    }



    //选桌方法
    public function androidGetData(){                                          
          $tab_db = D('tab');
          $admin_db = D('admin');
          $adminid= '1';
          $list=$tab_db->where('id=5')->select();;


          $this->ajaxReturn($list,'JSON');
//          $this->ajaxReturn($list);
//	echo 'list='.$list['num'];
//	$data['name'] = array("id' => $list[1],$list[2] => $list[3]);
//	$this->ajaxReturn($data);


      }

	public function xuanzhuo(){
        $id=$_GET['id'];
        $floor=M('floor')->select();
        $arr=getChilds($floor,$id);
        $arr=implode(',',$arr);
        $tab=M('tab')->where('floor_num in('.$arr.')')->select();
        foreach($tab as $k=>$v){
            switch ($v['status']){
                case 1:
                    $tab[$k]['name']='空閒';
                    break;
                case 2:
                    $tab[$k]['name']='預定';
                    break;
                case 3:
                    $tab[$k]['name']='使用';
                    break;
                case 4:
                    $tab[$k]['name']='整理';
                    break;
            }
        }
        $this->arr=$tab;

        $this->display('xuanzhuo');
    }
    //自动判断用户id和自动添加用户名
    public function tabPost(){
        $this->clear($_GET['tab']);
        $tab=M('tab')->where('id='.$_GET['tab'])->find();
        if($tab['status'] == 2 ){
            $this->error('本座已被預訂請重新選桌');
        }else if($tab['status'] == 3){
            $this->error('本桌正在使用中，請重新選桌');
        }else if($tab['status'] == 4){
            $this->error('本桌正在整理中，請等待');
        }else{
            $user_db= M('user');
            $id=$user_db->where(array('t_id'=>$_GET['tab']))->order('num desc')->find();
            $arr['t_id']=$_GET['tab'];
            $arr['num']=$id['num']+=1;
            $c_name='客人'.$id['num'];
            $arr['uname']=$c_name;
            session('uname', $c_name);
            $re=$user_db->add($arr);
            if($re !== false)
            {
//                           $admin_db=M('admin');
//                           $floor_db=M('floor');
//                           $floor=$floor_db->select();
//                           $adminid=$floor_db->where('id='.$tab['floor_num'])->find();
//                           if($adminid['adminid']==0){
//                                $parent=getParent($floor,$adminid['id']);
//                               $adminid=$floor_db->where('id='.$parent[0])->find();
//                           }
//                            $nameArr=$admin_db->where('id in ('.$adminid['adminid'].')')->field('nickname')->select();
//
//                            $nameArr=getOne($nameArr);
//                            $name=implode(',',$nameArr);
                session('uid',$arr['num']);
//                           cookie('admin_id',$adminid['id'],$time,'/');
                session('tabNum',$tab['num']);
                session('tab',$tab['id']);
//                           cookie('user',$name,$time,'/');
                $this->redirect(GROUP_NAME.'/Index/menu');
            }
            else
            {
                // $this->redirect(GROUP_NAME.'/Index/xuanzhuo');
                echo 11;
            }

        }
    }

    //读取cookie中的值
    public function menu(){

        $this->isCookieId();
        $this->Img=$this->logoImg();
        $db=M('menustype');
        $num=M('temp')->field('sum(amount) as a')->where('t_id='.$this->tabId)->select();
        $this->assign('num',$num[0]['a']);
        $this->list=$db->where('parentid=0')->select();
        $this->display('menu');
    }
    public function menuList(){
        $this->isCookieId();
        $db=M('menustype');
        $id=$_GET['menuid'];
        $list=$db->select();
        $arr=getChilds($list,$id);
        $arr=substr(implode(',',$arr),0,-2);
        $this->re=$db->where('id in ('.$arr.')')->select();
        $listName=$db->where('id='.$id)->field('name')->find();
        $this->listName=$listName['name'];
        //$tpl='';
        $this->display('menulist');

    }
    public function greensList(){
        $this->isCookieId();
        $listName=$_GET['listName'];
        $id=$_GET['id'];
        $list=M('menustype')->field('id,name')->where('id='.$_GET['id'])->find();
        $this->list=$listName.'>>'.$list['name'];
        $list =M('menu')->where('menutypeid='.$id)->select();
        foreach($list as $k => $v){
        	if($v['content']){
        		$list[$k]['content'] = htmlspecialchars_decode($v['content']);
        	}
        }
        $this->menu= $list;

        $this->display('greenlist');
    }
    //菜的详细列表
    public function green(){
        $this->isCookieId();

        $id=$_GET['id'];
        $this->list=$_GET['list'];
        $info = M('menu')->field('id,name,img,content,price,menutypeid')->where('id='.$id)->find();
        $info['content'] = htmlspecialchars_decode($info['content']);
        $this->green=$info;
        $this->display('green');
    }
    //菜加入临时菜单
    public function addGreens(){
        $this->isCookieId();

        if(IS_POST){
            //设置点菜的cookie
            $temp_db=M('temp');
            $tabArr=M('tab')->where('id='.$this->tabId)->find();
            $data=array();
            if($tabArr['status']==3)
            {
                $data['status']=1;
                $tempArr=$temp_db->field('id,amount')->where('u_id='.$_POST['u_id'].' and status=1 and `check`=0 and t_id='.$this->tabId)->select();
            }else{
                $tempArr=$temp_db->field('id,amount')->where('u_id='.$_POST['u_id'].' and `check`=0 and  t_id='.$this->tabId)->select();
            }
            if(empty($tempArr)){
                $data['menustypeid']=$_POST['listId'];
                $data['t_id']=$this->tabId;
                $data['price']=$_POST['price'];
                $data['name']=$_POST['name'];
                $data['u_id']=$_POST['u_id'];
                $data['starttime']=time();
                $data['uname']=$this->uname;
                if($temp_db->add($data)){
                    echo 1;
                }else{
                    echo 2;
                }
            }else{
                if($tabArr['status']==3){
                    $tempA=$tempArr[0]['amount']+1;
                    $tempInfo=$temp_db->where('u_id='.$_POST['u_id'].' and status =1 and `check`=0 and t_id='.$this->tabId)->save(array('amount'=>$tempA));
                    if($tempInfo !== false){
                        echo 1;
                    }
                }else{
                    $tempA=$tempArr[0]['amount']+1;
                    $tempInfo=$temp_db->where('u_id='.$_POST['u_id'],' and `check`=0 and t_id'.$this->tabId)->save(array('amount'=>$tempA));
                    if($tempInfo !== false){
                        echo 1;
                    }

                }

            }



        }else{
            $this->error('你訪問的網頁不存在');
        }
    }
    //我点的菜单
    public function myMuenList(){
        $this->isCookieId();
        $db=M('temp');
        if(IS_POST){
            $db->where('id='.$_POST['id'])->delete();
            die;
        }
        $status=M('tab')->where('id='.$this->tabId)->find();
        if($status['status']==3){
            $this->status=1;
        }
        $list=$db->where('t_id='.$this->tabId)->select();
        $countInfo=$db->field('sum(amount) as a')->where('t_id='.$this->tabId)->select();
        $this->amout=$countInfo[0]['a'];
        $total=$db->field('sum(price*amount) as total')->where('t_id='.$this->tabId)->select();
        $this->total=$total[0]['total'];
        $tempInfo=$db->where('status=1 and check=0')->select();
        if(empty($tempInfo)){
            $this->status=1;
        }
        $mList=M('menustype')->field('id,name')->select();
        foreach($list as $k=>$v){
            foreach($mList as $m){
                if($m['id']==$v['menustypeid']){
                    $list[$k]['dname']=$m['name'];
                }
            }
            if($v['status']==1){
                $list[$k]['addto']='<font color="red">是</font>';
            }else{
                $list[$k]['addto']='否';
            }
            if($v['check']>0){
                $list[$k]['addVa']=1;
                $this->addVa=1;
            }else{
                $this->addVa=2;
            }
        }
        if(empty($list)){
            $tpl='empty';
        }else{
            $tpl='mymuenlist';
        }
        $this->myList=$list;
        $this->display($tpl);
    }

    //推荐菜的列表
    public function tuijian(){
        $this->isCookieId();
        $list=M('menustype')->field('id,name')->where('id='.$_GET['id'])->find();
        $this->list='推薦菜';
        $menuList = M('menu')->where('recommend=1')->select();
        foreach($menuList as $k => $v){
	        if($v['content']){
	        	$menuList[$k]['content'] = strip_tags(htmlspecialchars_decode($v['content']));
	        }
        }
        $this->menu= $menuList;
        $this->display('greenlist');
    }
    //菜数量的加减
    function  addOrJ(){
        $amount=$_POST['amount'];
        $id=$_POST['id'];
        $re=M('temp')->where('id='.$id)->save(array('amount'=>$amount));
        if($re !== false){
            echo 1;
        }else{
            echo 2;
        }
    }

    public function newGreens(){
        $this->isCookieId();
        $this->list='新加菜';
        $menuList=M('menu')->order('createtime')->limit(10)->select();
        foreach($menuList as $k => $v){
        	if($v['content']){
        		$menuList[$k]['content'] = strip_tags(htmlspecialchars_decode($v['content']));
        	}
        }
        $this->menu = $menuList;
        $this->display('greenlist');
    }

    //下订单
    public function check(){
        $this->isCookieId();
        if(IS_POST){
            $tab_db=M('tab');
            if(isset($_POST['test'])){
                $userArr=M('user')->where('t_id='.$this->tabId)->select();
                $count=count($userArr);
                if($count>1){
                    echo $count;
                }else{
                    echo 1;
                }
            }else{
                $nameId='';
                $temp_db=M('temp');
                //把临时表订单状态改为以下单
                $menuIdArr=$temp_db->where('t_id='.$this->tabId.' and `check`=0')->select();
                if($menuIdArr){
                    foreach($menuIdArr as $v){
                        $nameId=$nameId.','.$v['id'];
                    }
                    $nameId=substr($nameId,1);
                    $res=$temp_db->where('id in ('.$nameId.')')->save(array('check'=>1));
                }else{
                    $menuCount=$temp_db->where('t_id='.$this->tabId.' and `check`=1')->conut();
                    if($menuCount){
                        echo 2;
                    }else{
                        echo 3;
                    }
                }
                $data=array();
                $data['status']=3;
                $re=$tab_db->where('id='.$this->tabId)->save($data);
                if($re !== false){
                    $settle_db=M('settle');
                    $ress=$settle_db->where('t_id='.$this->tabId)->find();
                    if($ress){
                        $setRe= $settle_db->where('t_id='.$this->tabId)->save(array('createtime'=>time()));
                        if($setRe !== false){
                            echo 1;
                        }else{
                            echo 5;
                        }
                    }else{
                        $check_data=array();
                        $floorInfo=getMenuType(m('floor')->select(),'&nbsp;>>');
                        $tabI=$tab_db->where('id='.$this->tabId)->find();
                        foreach($floorInfo as $val){
                            if($val['id']==$tabI['floor_num']){
                                $check_data['floorname']=$val['dname'];
                            }
                        }
                        $check_data['num']=session('tabNum');
                        $check_data['t_id']=$this->tabId;
                        $check_data['createtime']=time();
                        if($settle_db->add($check_data)) {
                            echo 1;
                        }
                    }

                }
            }
        }else{
            $this->error('你訪問題頁面不存在');
        }


    }
    //删除所有信息如果半小时内没有下单
    public function delete(){
        $time=1800*1000;
        if((time()-$this->time)>$time)
        {
            $t_id=session('tab');
            M('user')->where(array('t_id'=>$t_id))->delete();
            M('temp')->where(array('t_id'=>$t_id))->delete();
            session('tab',null);
            session('uname',null);
            session('uid',null);
        }
    }
    //热卖菜
    public function hotMenu(){
        $this->isCookieId();
        $db=M('stream');
        //查找所有点菜的id
        $a=$db ->field('u_id')->group('u_id')->select();
        $a=getOne($a);
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
        $menuList=M('menu')->select();
        $hot=getOne($arrNew);
        $hot=m_sort($hot,'amount');
        $newHot=array();
        foreach($hot as $v){
            foreach($menuList as $m){
                if($v['u_id']==$m['id']){
                	if($m['content']){
                		$m['content'] = strip_tags(htmlspecialchars_decode($m['content']));
                	}
                    $newHot[]=$m;
                }
            }
        }
        $this->menu=$newHot;
        $this->display('greenlist');
    }
    public function logoImg(){
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
        return $get_config_info;
    }
    protected function isCookieId(){
        if(!$this->tabId || !$this->uname || !$this->uid){
            echo '<script type="text/javascript"> parent.location.href="index.php?g=User&m=Index&a=index"</script>';
        }else{
            $userInfo = D("user")->where('t_id='.$this->tabId.' and num='.$this->uid)->find();
            if(!$userInfo){
                session('uid',null);
                session('uname',null);
                echo '<script type="text/javascript"> parent.location.href="index.php?g=User&m=Index&a=index"</script>';
            }
        }
    }
    //结账
    public function checkOut(){
        $id=$this->tabId;
        $temp_db=M('temp');
        if(!session('tab')){
            echo 6;die;
        }
        if(isset($_POST['test'])){
            $tempInfo=$temp_db->where('t_id='.$this->tabId.' and `check`=0')->select();
            $temp=$temp_db->where('t_id='.$this->tabId.' and `check`=1')->select();
            $te=$temp_db->where('t_id='.$this->tabId.' and `check`=2')->select();
            if(!empty($tempInfo) && empty($temp)){
                echo 3;
            }else if(empty($temp) && empty($tempInfo) && empty($te)){
                echo 4;
            }else if(!empty($temp) && empty($te)){
                echo 7;

            }else{
                echo 1;
            }
        }else{
            $re=M('settle')->where('t_id='.$id)->save(array('type'=>1));
            if($re !== false){
                session(null);
                echo 1;
            }else{
                echo 5;
            }
        }



    }
    //半个小时不下订单删除临时表和用户表
    protected   function clear($tab){
        $temp_db=M('temp');
        $tempInfo=$temp_db->field('starttime,check')->where('t_id='.$tab)->order('starttime')->select();
        foreach( $tempInfo as $v){
            if($v['check']>0){
                $str=true;
            }else{
                $status=true;
            }
        }
        $time=time();
        $oldTime=$tempInfo[0]['starttime']+60*1000*30;
        if($time>$oldTime && $status && !$str ){
            $temp_db->where('t_id='.$tab)->delete();
            M('user')->where('t_id='.$tab)->delete();
        }
    }
}
