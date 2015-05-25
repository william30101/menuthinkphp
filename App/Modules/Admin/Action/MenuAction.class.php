<?php
/**
 * 后台菜谱类
 * @author huailong
 *
 */
class MenuAction extends CommAction {
    protected $img;//图片上传
    /**
     * 首页
     * @author huailong
     */
    public function index(){
        import('ORG.Util.Page');
        $db=M('menustype');
        $count=$db->where('parentid=0')->count();
        $nowPage = $_GET['p'] ? $_GET['p'] : 1 ;
        $nowParent = $db->where('parentid=0')->order('id asc')->limit($nowPage-1,1)->select();
        $parentId = $nowParent[0]['id'];
        $page=new Page($count,1);
	    $page->config['header'] = '页';
        $newArr=$db->where('parentid='.$parentId)->select();
        $newArr[] = $nowParent[0];
    	$page->setNowPage($nowPage);
        $this->page=$page->show();
        $newArr=getMenuType($newArr);
        foreach($newArr as $k=>$v){
            $arr='';
            if($v['parentid']==0){
                $child=getChilds($newArr,$v['id']);
                $childId=implode(',',$child);
                $arr=M('menu')->where('menutypeid in ('.$childId.')')->count();
                $newArr[$k]['m']=$arr;
            }else{
                $arr=M('menu')->where('menutypeid ='.$v['id'])->count();
                $newArr[$k]['m']=$arr;
            }


        }
        $this->arr=$newArr;
        $this->display('index');
    }


    /**
     * 分类列表
     * @author huailong
     */
    public function typeList(){
           $this->redirect(GROUP_NAME.'/Menu/index');
    }
    /**
     * 添加分类
     * @author huailong
     */
    public function addType(){
        $title='添加分类';
        $this->assign('title',$title);
        $this->list= M('menustype')->where('parentid=0')->select();
    	$this->display('addlist');
    }
    public function addTypeChannel(){
        //echo '?g='.GROUP_NAME.'&m=Menu&a=index';die;
        if(!IS_POST) halt('您访问的网页不存在');
        $data=array();
       $db= D('Menustype');
        if(!empty($_POST['picname'])){
            $data['img']=$_POST['picname'];
        }elseif($_FILES['fileField']['size'] > 0){
            $this->_upload();
            $data['img'].=$this->img;
        }else{
            $this->error('请上传图片');die;
        }
        if(empty($_POST['id'])){
            if($_POST['name']==''){
                $this->error('分类的名字不能为空');
            }
            $data['name']=I('name');
            $data['parentid']=I('select','intval');
            $data['createtime']=time();
            $re=$db->create();
            if(!$re){
                $this->error($db->getError());
            }else{
               if($db->add($data)){
                   $this->success('添加成功',U(GROUP_NAME.'/Menu/index'));
               }else{
                   $this->error('添加分类失败,请重试');
               }
            }
        }else{
            $id=$_POST['id'];
            $data['name']=I('name');
            $data['parentid']=I('select','intval');
            $data['updatetime']=time();
            $re=$db->where('id='.$id)->save($data);
            if($re !== false){
                $this->success('菜谱分类修改成功','__APP_PATH__/index.php?g=Admin&m=Menu&a=index');
            }else{
                $this->error('菜谱分类修改失败，请重新添加');
            }
        }

    }
    /*
     * 修改分类
     */
    public function editType(){
        $id=$_GET['id'];
        $db=M('menustype');
        $this->list= $db->where('parentid=0')->select();
        $this->m=$db->find($id);
        $this->display('addlist');
    }
    //删除菜的分类
    public function  delType(){
        $id=$_GET['id'];
        $arr=array();
        $type_db=M('menustype');
        $typeInfo=$type_db->field('id,parentid')->select();
        $typeIdArr=getChilds($typeInfo,$id);
        $str=implode(',',$typeIdArr);
        if(strpos($str,',')){
            $this->error('本分类下还有子分类，请先删除本分类下所有子分类');
        }else{
            $arr=M('menu')->where('menutypeid='.$id)->select();
            if(empty($arr)){
                if($type_db->where('id='.$id)->delete()){
                    $this->success('删除成功','index.php/'.GROUP_NAME.'/Menu/typeList');
                }else{
                    $this->error('删除失败请重试');
                }
            }else{
                $this->error('本栏目下还有菜，请删除本栏目下所有菜');
            }
        }

}
    /**
     * 菜谱列表
     * @author huailong
     */
    public function menuList(){
        $menuList_db=M('menustype');
       $this->list= $menuList_db->where('parentid=0')->select();
       $list=getMenuType($menuList_db->select());
        $db=M('menu');
        if(!IS_POST){

        $nowPage = isset($_GET['p'])?$_GET['p']:1;
        import("ORG.Util.Page");
        $count=$db->count();
        $page=new Page($count,7);
        $page->setNowPage($nowPage);
        $menu=$db->page($nowPage.','.$page->listRows)->select();
        $NewM=array();

        foreach($menu as $mm){
            foreach($list as $v){
                    if($mm['menutypeid']==$v['id']){
                        $mm['dname'].=$v['dname'];
                        $NewM[]=$mm;
                    }
                }

            }
            $this->menu=$NewM;
        }else{
            if(empty($_POST['ss']) && !empty($_POST['id']))
            {
                $listId=getChilds($list,$_POST['id']);
                $listId=implode(',',$listId);
                $count=$db->field('id')->where( 'menutypeid in ('.$listId.')')->select();
                $count=count($count);
                $nowPage = isset($_GET['p'])?$_GET['p']:1;
                import("ORG.Util.Page");

                $page=new Page($count,7);
                $page->setNowPage($nowPage);
                $menu=$db->where( 'menutypeid in ('.$listId.')')->page($nowPage.','.$page->listRows)->select();
                $NewM=array();
                foreach($menu as $mm){
                    foreach($list as $v){
                        if($mm['menutypeid']==$v['id']){
                            $mm['dname'].=$v['dname'];
                            $NewM[]=$mm;
                        }
                    }

                }
                $this->menu=$NewM;
            }else if(!empty($_POST['ss']) && empty($_POST['id'])){
                $count=$db->field('id')->where("name like '%".$_POST['ss']."%'")->group('u_id')->select();
                $count=count($count);
                $nowPage = isset($_GET['p'])?$_GET['p']:1;
                import("ORG.Util.Page");
                $page=new Page($count,7);
                $page->setNowPage($nowPage);
                $menu=$db->where("name like '%".$_POST['ss']."%'")->page($nowPage.','.$page->listRows)->select();
                $NewM=array();
                foreach($menu as $mm){
                    foreach($list as $v){
                        if($mm['menutypeid']==$v['id']){
                            $mm['dname'].=$v['dname'];
                            $NewM[]=$mm;
                        }
                    }
                }
                $this->menu=$NewM;
            }else if(!empty($_POST['ss']) && !empty($_POST['id'])){
                $listId=getChilds($list,$_POST['id']);
                $listId=implode(',',$listId);
                $count=$db->field('id')->where( 'menutypeid in ('.$listId.") and name like '%".$_POST['ss']."%'")->select();
                $count=count($count);
                $nowPage = isset($_GET['p'])?$_GET['p']:1;
                import("ORG.Util.Page");
                $page=new Page($count,7);
                $page->setNowPage($nowPage);
                $menu=$db->where( 'menutypeid in ('.$listId.") and name like '%".$_POST['ss']."%'")->page($nowPage.','.$page->listRows)->select();
                $NewM=array();
                foreach($menu as $mm){
                    foreach($list as $v){
                        if($mm['menutypeid']==$v['id']){
                            $mm['dname']=$v['dname'];
                            $NewM[]=$mm;
                        }
                    }
                }
                $this->menu=$NewM;
            }else{
                $this->redirect(GROUP_NAME.'/Menu/menuList');
            }
        }

        //$arr=M('menu')->select();;
        $this->page=$page->show();
    	$this->display('menulist');
    }
    
    /*
     * 修改菜
     * 修改菜谱（包括添加）modify by Tony
     */
    public function editMenu(){
        $targetid = $_REQUEST['id'];
        $method = $this->isPost();
        $data=array();
        if($method){
        	if(!empty($_POST['picname'])){
        		$data['img']=$_POST['picname'];
        	}elseif($_FILES['fileField']['size'] > 0){
        		$this->_upload();
        		$data['img'].=$this->img;
        	}else{
        		$this->error('请上传图片');die;
        	}
            $men=D('Menu');
        	$data['name']=I('name');
        	$data['price']=I('price');
        	if ($this->_post('content')) {
        		if (get_magic_quotes_gpc()) {
        			$htmlData = stripslashes($this->_post('content'));
        		} else {
		        	$data['content']=$this->_post('content');
        		}
        	}
        	$data['recommend'] = I('recommend');
        	$data['menutypeid']=I('select');
        	$data['unit']=$_POST['unit'];
        	if($targetid){
        		$str = '修改';
	        	$data['updatetime']=time();
                $result=$men->create();
                if(!$result){
                    $this->error($men->getError());
                }else{
                    $re = $men->where('id='.$targetid)->save($data);
                }

        	}else{
        		$str = '添加';
        		$data['createtime']=time();
                $result=$men->create();
                if(!$result){
                    $this->error($men->getError());
                }else{
        		$re =$men->add($data);
                }
        	}
	        if($re !== false){
	            $this->success($str.'成功！',"__APP_PATH__/index.php?g=Admin&m=Menu&a=menuList");
	        }else{
	           $this->error($str.'失败！');
	        }
        }else{
        	$title = '添加';
        	if($targetid){
		        $m=M('menu')->find($targetid);
		        $this->m=$m;
		        $title = '修改';
        	}else{
                $info['name'] = $titlee = $_REQUEST['title'];
                $info['img'] = $imgee = $_REQUEST['img'];
                $this->m=$info;
            }
            $menu_db=M('menustype');
	        $list=getMenuType($menu_db ->select(),'┠');
            $this->list=$list;
	       // P($arr);
	        $this->assign('title',$title);
	        $this->display('editMenu');
        }
    }

    /*
     * 菜的删除类
     */
    public function  delMenu(){
    	$ids = !empty($_REQUEST['id'])?$_REQUEST['id']:'';
    	$this->delApi('menu', $ids, "id");
    }
    /**
     * 热卖菜
     * @author huailong
     */
    public function hotMenu(){
        $db=M('stream');
        $post=$this->isPost();
        if(!$post){
            $nowPage = isset($_GET['p'])?$_GET['p']:1;
            import("ORG.Util.Page");
            $count=$db->field('id')->group('u_id')->select();;
            $count=count($count);
            $page=new Page($count,7);
            $page->setNowPage($nowPage);
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
            $hot=getOne($arrNew);

            $hot=m_sort($hot,'amount');
            $this->list= M('menustype')->where('parentid=0')->page($nowPage.','.$page->listRows)->select();
            //P($this->list);
            $list= M('menustype')->select();
            $list=getMenuType($list);
            $NewM=array();
            foreach($hot as $mm){
                foreach($list as $v){
                    if($mm['menutypeid']==$v['id']){
                        $mm['dname']=$v['dname'];
                        $NewM[]=$mm;
                    }
                }
            }
            $this->hot=$NewM;
    }else{
           if(empty($_POST['ss']) && !empty($_POST['id'])){
               $list= M('menustype')->select();
               $list=getChilds($list,$_POST['id']);
                   $list=implode(',',$list);
                   $count=$db->field('id')->where( 'menutypeid in ('.$list.')')->group('u_id')->select();
                   $count=count($count);
                   $nowPage = isset($_GET['p'])?$_GET['p']:1;
                   import("ORG.Util.Page");

                   $page=new Page($count,7);
                   $page->setNowPage($nowPage);
                   //查找所有点菜的id
                   $a=$db ->field('u_id')->where( 'menutypeid in ('.$list.')')->page($nowPage.','.$page->listRows)->group('u_id')->select();
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
               $hot=getOne($arrNew);

               $hot=m_sort($hot,'amount');
               $this->list= M('menustype')->where('parentid=0')->page($nowPage.','.$page->listRows)->select();
               //P($this->list);
               $list= M('menustype')->select();
               $list=getMenuType($list);
               $NewM=array();
               foreach($hot as $mm){
                   foreach($list as $v){
                       if($mm['menutypeid']==$v['id']){
                           $mm['dname']=$v['dname'];
                           $NewM[]=$mm;
                       }
                   }
               }

               $this->hot=$NewM;
           }else if(!empty($_POST['ss']) && !empty($_POST['id'])){
               $count=$db->field('id')->where( "menutypeid =".$_POST['id']." and uname like '%".$_POST['ss']."%'")->group('u_id')->select();
               $count=count($count);
               $nowPage = isset($_GET['p'])?$_GET['p']:1;
               import("ORG.Util.Page");
               $page=new Page($count,7);
               $page->setNowPage($nowPage);
               //查找所有点菜的id
               $a=$db ->field('u_id')->where( "menutypeid =".$_POST['id']." and uname like '%".$_POST['ss']."%'")->page($nowPage.','.$page->listRows)->group('u_id')->select();
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
               $hot=getOne($arrNew);

               $hot=m_sort($hot,'amount');
               $this->list= M('menustype')->where('parentid=0')->page($nowPage.','.$page->listRows)->select();
               //P($this->list);
               $list= M('menustype')->select();
               $list=getMenuType($list);
               $NewM=array();
               foreach($hot as $mm){
                   foreach($list as $v){
                       if($mm['menutypeid']==$v['id']){
                           $mm['dname'].=$v['dname'];
                           $NewM[]=$mm;
                       }
                   }
               }

               $this->hot=$NewM;
           }else if(!empty($_POST['ss']) && empty($_POST['id'])){
               $count=$db->field('id')->where("uname like '%".$_POST['ss']."%'")->group('u_id')->select();
               $count=count($count);
               $nowPage = isset($_GET['p'])?$_GET['p']:1;
               import("ORG.Util.Page");
               $page=new Page($count,7);
               $page->setNowPage($nowPage);
               //查找所有点菜的id
               $a=$db ->field('u_id')->where("uname like '%".$_POST['ss']."%'")->page($nowPage.','.$page->listRows)->group('u_id')->select();
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

               $hot=getOne($arrNew);

               $hot=m_sort($hot,'amount');
               $this->list= M('menustype')->where('parentid=0')->page($nowPage.','.$page->listRows)->select();
               //P($this->list);
               $list= M('menustype')->select();
               $list=getMenuType($list);
               $NewM=array();
               foreach($hot as $mm){
                   foreach($list as $v){
                       if($mm['menutypeid']==$v['id']){
                           $mm['dname']=$v['dname'];
                           $NewM[]=$mm;
                       }
                   }
               }
               $this->hot=$NewM;
           }else{
                   $this->redirect(GROUP_NAME.'/Menu/hotMenu');
               }
        }

        $this->page=$page->show();
    	$this->display('hotMenu');
    }
    /**
     * 素材管理
     * @author huailong
     * 搜索素材页（内附着搜索页）	modify by Tony
     */
    public function material(){
    	$title = $_REQUEST['title'];
        $material_db = D('material');
    	import("ORG.Util.Page");// 导入分页类
    	$nowPage = isset($_GET['p'])?$_GET['p']:1;
    	$where = '1';
    	if($title){
    		$where .= " and name like '%".$title."%'";
    		$wherePar = '&title='.$title;
    		$Page->parameter = $wherePar;
    	}
    	$count      = $material_db->where($where)->count();// 查询满足要求的总记录数
    	$this->assign('count',$count);
    	$Page       = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
    	//     	print_r($Page);die;
    	$Page->setNowPage($nowPage);
    	$Page->parameter = $wherePar;
    	$show       = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $material_db->where($where)->order('id desc')->page($nowPage.','.$Page->listRows)->select();
    	$this->assign('page',$show);// 赋值分页输出
    	foreach($list as $k => $v){
    		if(strpos($v['img'],'|')){
    			$list[$k]['img'] = explode('|',$v['img']);
    		}
    	}
    	$this->assign('list',$list);
    	$this->display('material');
    }
    
    /**
     * 搜索素材页（内附着搜索页）	modify by Tony
     */
    public function getMaterial(){
    	$title = $_REQUEST['title'];
    	$material_db = D('material');
    	if($title){
    		$nowPage = isset($_GET['p'])?$_GET['p']:1;
    		$where = "name like '%".$title."%'";
    		$wherePar = '&title='.$title;
    		import("ORG.Util.Page");// 导入分页类
    		$count      = $material_db->where($where)->count();// 查询满足要求的总记录数
    		$this->assign('count',$count);
    		$Page       = new Page($count,2);// 实例化分页类 传入总记录数和每页显示的记录数
    		//     	print_r($Page);die;
    		$Page->setNowPage($nowPage);
    		$Page->parameter = $wherePar;
    		$show       = $Page->show();// 分页显示输出
    		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    		$list = $material_db->where($where)->order('id desc')->page($nowPage.','.$Page->listRows)->select();
    		$this->assign('page',$show);// 赋值分页输出
    		foreach($list as $k => $v){
    			if(strpos($v['img'],'|')){
    				$list[$k]['imgArray'] = explode('|',$v['img']);
    			}
    		}
    		$this->assign('list',$list);
    		$tpl = 'showMaterial';
    	}else{
    		$tpl = 'getMaterial';
    	}
    	$this->display($tpl);
    }

    public  function addMaterial(){
        if(isset($_POST['title'])){
            if(empty($_POST['title'])){
                $this->error('菜的名字不能为空');
            }
            else{
                if(!empty($_FILES)){
                    $this->_upload();
                    $mater_db=M('material');
                    $materInfo=$mater_db->where('name='.$_POST['title'])->find();
                    if(!empty($materInfo)){
                        $img=$materInfo['img'].'|'.$this->img;
                        $res=$mater_db->where('id='.$materInfo['id'])->save(array('img'=>$img));
                        if($res !== false){
                            $this->success('添加成功',U(GROUP_NAME.'/Menu/material'));
                        }else{
                            $this->error('添加失败');
                        }
                    }else{
                        $data['img']=$this->img;
                        $data['name']=$_POST['title'];
                        if($mater_db->add($data)){
                            $this->success('添加成功',U(GROUP_NAME.'/Menu/material'));
                        }else{
                            $this->error('添加失败');
                        }
                    }

                }else{
                    $this->error('请上传图片');
                }

            }
        }

        $this->display('addmaterial');
    }
    //删除素材库的内容
    public function delMaterial(){
        $id=$_GET['id'];
        $re=M('material')->where('id='.$id)->delete();
        if($re !== false){
            $this->success('删除成功');
        }else{
            $this->error('删除失败，请重试');
        }
    }

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
                $img=$img.'|'.substr($v['savepath'],1).$v['savename'];
            }
            $img=substr($img,1);
            $this->img=$img;
        //保存当前数据对象
//        $data['image'] = $_POST['image'];
//        $data['create_time'] = time();
//        P($data);

        }
    }
}
