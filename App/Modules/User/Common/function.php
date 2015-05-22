<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id: common.php 2601 2012-01-15 04:59:14Z liu21st $

//公共函数
function toDate($time, $format = 'Y-m-d H:i:s') {
	if (empty ( $time )) {
		return '';
	}
	$format = str_replace ( '#', ':', $format );
	return date ($format, $time );
}

function getStatus($status, $imageShow = true) {
	switch ($status) {
		case 0 :
			$showText = '禁用';
			$showImg = '<IMG SRC="__PUBLIC__/Images/locked.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="禁用">';
			break;
		case 2 :
			$showText = '待审';
			$showImg = '<IMG SRC="__PUBLIC__/Images/prected.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="待审">';
			break;
		case - 1 :
			$showText = '删除';
			$showImg = '<IMG SRC="__PUBLIC__/Images/del.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="删除">';
			break;
		case 1 :
		default :
			$showText = '正常';
			$showImg = '<IMG SRC="__PUBLIC__/Images/ok.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="正常">';

	}
	return ($imageShow === true) ?  $showImg  : $showText;

}

function showStatus($status, $id) {
	switch ($status) {
		case 0 :
			$info = '<a href="javascript:resume(' . $id . ')">恢复</a>';
			break;
		case 2 :
			$info = '<a href="javascript:pass(' . $id . ')">批准</a>';
			break;
		case 1 :
			$info = '<a href="javascript:forbid(' . $id . ')">禁用</a>';
			break;
		case - 1 :
			$info = '<a href="javascript:recycle(' . $id . ')">还原</a>';
			break;
	}
	return $info;
}

/**
 * lijianqiu
 * 2013-04-18
 * 将消耗值的'-'去掉
 * @param $str
 * @return mixed
 */
function deletesign($data){
    $sign=substr($data,0,1);
    if($sign=='-'){
        $data=0 - $data;
        return $data;
    }else{
        return $data;
    }
}

/**
 * 过滤html标签
 * @author Tony
 * @param strring $str	字符串
 * @return string
 */
function htmlTagFiltration($str){
	if($str){
		$str=preg_replace("/\s+/"," ",$str);//过滤多余回车
		 
		$str=preg_replace("/<[ ]+/si","<",$str);//过滤<__("<"号后面带空格)
		 
		$str=preg_replace("/<\!–.*?–>/si","",$str);//注释
		 
		$str=preg_replace("/<(\!.*?)>/si","",$str);//过滤DOCTYPE
		 
		$str=preg_replace("/<(\/?html.*?)>/si","",$str);//过滤html标签
		 
		$str=preg_replace("/<(\/?br.*?)>/si","",$str);//过滤br标签
		 
		$str=preg_replace("/<(\/?head.*?)>/si","",$str);//过滤head标签
		 
		$str=preg_replace("/<(\/?meta.*?)>/si","",$str);//过滤meta标签
		 
		$str=preg_replace("/<(\/?body.*?)>/si","",$str);//过滤body标签
		 
		$str=preg_replace("/<(\/?link.*?)>/si","",$str);//过滤link标签
		 
		$str=preg_replace("/<(\/?form.*?)>/si","",$str);//过滤form标签
		 
		$str=preg_replace("/<(\/?a.*?)>/si","",$str);//过滤a标签 added by Tony
    	$str=preg_replace("/<(\/?table.*?)>/si","",$str);//过滤table标签 added by Tony
		 
		$str=preg_replace("/cookie/si","COOKIE",$str);//过滤COOKIE标签
		 
		$str=preg_replace("/<(applet.*?)>(.*?)<(\/applet.*?)>/si","",$str);//过滤applet标签
		 
		$str=preg_replace("/<(\/?applet.*?)>/si","",$str);//过滤applet标签
		 
		$str=preg_replace("/<(style.*?)>(.*?)<(\/style.*?)>/si","",$str);//过滤style标签
		 
		$str=preg_replace("/<(\/?style.*?)>/si","",$str);//过滤style标签
		 
		$str=preg_replace("/<(title.*?)>(.*?)<(\/title.*?)>/si","",$str);//过滤title标签
		 
		$str=preg_replace("/<(\/?title.*?)>/si","",$str);//过滤title标签
		 
		$str=preg_replace("/<(object.*?)>(.*?)<(\/object.*?)>/si","",$str);//过滤object标签
		 
		$str=preg_replace("/<(\/?objec.*?)>/si","",$str);//过滤object标签
		 
		$str=preg_replace("/<(noframes.*?)>(.*?)<(\/noframes.*?)>/si","",$str);//过滤noframes标签
		 
		$str=preg_replace("/<(\/?noframes.*?)>/si","",$str);//过滤noframes标签
		 
		$str=preg_replace("/<(i?frame.*?)>(.*?)<(\/i?frame.*?)>/si","",$str);//过滤frame标签
		 
		$str=preg_replace("/<(\/?i?frame.*?)>/si","",$str);//过滤frame标签
		 
		$str=preg_replace("/<(script.*?)>(.*?)<(\/script.*?)>/si","",$str);//过滤script标签
		 
		$str=preg_replace("/<(\/?script.*?)>/si","",$str);//过滤script标签
		 
		$str=preg_replace("/javascript/si","Javascript",$str);//过滤script标签
		 
		$str=preg_replace("/vbscript/si","Vbscript",$str);//过滤script标签
		 
		$str=preg_replace("/on([a-z]+)\s*=/si","On\\1=",$str);//过滤script标签
		 
		$str=preg_replace("/&#/si","&＃",$str);//过滤script标签，如javAsCript:alert()
	}
	return $str;
}


/**
 * 获取内容长度 （加密内容）【通过$type参数返回不同数据】
 * @param unknown $str	数据内容
 * @param string $type	默认为null,如果为hide：隐藏内容，如（...爱你的...）
 * @param string $charset	字符集
 * @return unknown|Ambigous <boolean, string, number>
 */
function getLength($str, $type=null, $charset='utf-8'){
	$result = false;
	if($str){
		htmlTagFiltration($str);
		$oldStr = $str;
		if($charset=='utf-8') $str = iconv('utf-8','gb2312',$str);
		$num = strlen($str);
		$cnNum = 0;
		for($i=0;$i<$num;$i++){
			if(ord(substr($str,$i+1,1))>127){
				$cnNum++;
				$i++;
			}
		}
		$enNum = $num-($cnNum*2);
		$number = ($enNum/2)+$cnNum;
		$result = $len = ceil($number);//内容的总字数
		
		if($type=='hide'){//把内容隐藏成类似于  ...数，据...
			$start= ceil($len/2)-2; //从内容的中间-2的部分开始截取
			$length = 3;	//截取3个字
			if(function_exists("mb_substr")){
					if(mb_strlen($oldStr, $charset) <= $length) return $oldStr;
					$slice = mb_substr($oldStr, $start, $length, $charset);
				}else{
					$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
					$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
					$re['gbk']          = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
					$re['big5']          = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
					preg_match_all($re[$charset], $oldStr, $match);
					if(count($match[0]) <= $length) return $oldStr;
					$slice = join("",array_slice($match[0], $start, $length));
				}
				$result = '...'.$slice.'...';
		}
	}
	return $result;
}

/**
 * 获得日志分类
 * @author Tony
 * @param string $type 数据类型 默认为null<br/>
 * @return Ambigous <boolean, unknown, mixed, string, NULL><br/>
 * 默认为null{返回数据:array([0]=>array([id0]=>idVal0,[name0]=>nameVal0),[1]=>array([id1]=>idVal1,[name1]=>nameVal1))},<br/>
 * 否则{返回数据:array([id0]=>array([id0]=>idVal0,[name0]=>nameVal0),[id2]=array([id2]=>idVal2,[name2]=>nameVal2))}
 */
function getDiaryCategory($type=null){
	$result = false;
	$From = D('diary_category');
	$info = $From->select();
	if(!$type){
		$result = $info;
	}else{
		foreach($info as $k=>$v){
			$result[$v['catid']] = $v;
		}
	}
	return $result;
}

/**
 * 加密手机号中间四位数字
 * @author Tony
 * @param unknown $value
 * @return Ambigous <boolean, mixed>
 */
function toEncryptByMobile($value){
	$result = false;
	if($value){
		$result = substr_replace($value,'****',3,4);
	}
	return $result;
}

/**
 * 获得收件箱中未读信件的数量
 * @author Tony
 * @param string $userid	用户id
 * @param string $type		获取类型：默认获取全部;1：人对人信件，2：系统信件
 * @return int
 */
function getInboxUnreadCount($userid,$type=null){
	$result = 0;
	if($userid){
		$message = D('message');
		$extra = '';
		if($type == 1){//人对人信件
			$extra = ' and issystem=0';
		}elseif($type ==2){//系统信件
			$extra = ' and issystem=1';
		}
		$where = 'touserid='.$userid.' and todeleted=0 and readflag=0'.$extra;
		$result = $message->where($where)->count();
	}
	return $result;
}

/**
 * 通过用户id获得到该用户的信息回复百分比
 * @author Tony
 * @param unknown $userid
 * @return number
 */
function getReplyMsgNumberByUserId($userid){
	$result = 0;
	if($userid){
		$message_db = D('message');
		$allCount = $message_db->where('touserid='.$userid)->count();
		$replyCount = $message_db->where('touserid='.$userid.' and replystatus=1')->count();
		$result = ceil(($replyCount/$allCount)*100);
	}
	return $result;
}

/**
 * 通过用户id获得到该用户的信息查看百分比
 * @author Tony
 * @param unknown $userid
 * @return number
 */
function getSeeMsgNumberByUserId($userid){
	$result = 0;
	if($userid){
		$message_db = D('message');
		$allCount = $message_db->where('touserid='.$userid)->count();
		$seeCount = $message_db->where('touserid='.$userid.' and readflag=1')->count();
		$result = ceil(($seeCount/$allCount)*100);
	}
	return $result;
}
/**
 * 获得推荐提问and答案模板
 * author Tony
 * @param unknown $mygender	当前用户性别
 * @return Ambigous <multitype:string multitype:string  >
 */
function getRecommendTextTemp($mygender,$all=null){
	$result = array();
	if($mygender == 1){
		$call = '她';
	}else{
		$call = '他';
	}
	$result = array(
				'0'=>array( 'question' => '你和'.$call.'见几次面，会确立恋爱关系？',
							'answer' => array('live'=>'第一次见就会爱上'.$call,'like'=>'多见几次之后','not'=>'还是算了吧'),
							'reply' => array('live'=>'在我的生命里因为有你才精彩。在我的生命里因为有你才有意义。在我的生命里没有你才无精打彩。在我的生命里没有你才感到无奈。所以我说只有你我在一起世人才能说这一对是天仙配。','like'=>'你好，很高兴认识你，希望能成为你想等待的那个人。')
				 ),
				'1'=>array( 'question' => '你想和'.$call.'一人一边耳机，一起听歌吗？',
							'answer' => array('live'=>'好浪漫','like'=>'好吧','not'=>'不要'),
							'reply' => array('live'=>'在吗？我在山东，我一般比较喜欢听音乐我觉得我们挺合适的。','like'=>'我住在山东，周末我会花很多时间听音乐希望和你成为朋友。')
				 ),
				'2'=>array( 'question' => '你愿意为'.$call.'洗内衣吗？',
							'answer' => array('live'=>'当然，应该做的','like'=>'偶尔洗洗还是可以的','not'=>'不会'),
							'reply' => array('live'=>'Hi，你好，很高兴认识你，我感觉我们俩挺合适的','like'=>'很高兴认识你！我在山东，期待你的回复。')
				 ),
				'3'=>array( 'question' => '你想陪'.$call.'一起看日落吗?',
							'answer' => array('live'=>'非常想','like'=>'还可以吧','not'=>'不想去'),
							'reply' => array('live'=>'很高兴认识你，我喜欢看日落，你愿意接受我的邀请吗？','like'=>'我们彼此了解一下。期待你的来信。')
				 ),
				'4'=>array( 'question' => '如果'.$call.'刚刚和前任分手，你会怎么办?',
							'answer' => array('live'=>'这是个机会，好好把握','like'=>'默默的关心'.$call,'not'=>'劝'.$call.'和前任和好'),
							'reply' => array('live'=>'你好，很高兴认识你，希望能成为你想等待的那个人','like'=>'你开心，我陪你开心，你难过，我陪你难过，我会默默关注你的，直到你开始关注我')
				 ),
				'5'=>array( 'question' => '你敢不敢和'.$call.'一起去看恐怖电影？',
							'answer' => array('live'=>'抱着'.$call.'一起看','like'=>'会考虑','not'=>'让'.$call.'自己去吧'),
							'reply' => array('live'=>'你好，我在山东，周末喜欢看电影，我觉的我们挺合适的','like'=>'你好，我在山东，我的兴趣是看电影，期待你的回信。')
				 ),
				'6'=>array( 'question' => '如果'.$call.'在QQ上跟你搭讪，你会？',
							'answer' => array('live'=>'好激动呐','like'=>'先聊聊看','not'=>'直接拉黑'),
							'reply' => array('live'=>'人潮人海中，相遇是缘，相知是份，相爱即是缘分。你是那个人嘛？','like'=>'先来个自我介绍吧！我在山东工作，我的兴趣是上网，希望和你成为朋友。')
				 ),
				'7'=>array( 'question' => '如果你们在一起，你敢把你的QQ密码告诉'.$call.'吗？',
							'answer' => array('live'=>'既然在一起就相互信任','like'=>'两个人应该有各自的空间','not'=>'凭什么啊'),
							'reply' => array('live'=>'看过你的资料，感觉我们挺合适的，相识也是一种缘分，希望能与你认识一下','like'=>'嗨，我现在在山东，我觉得我们挺合适的。')
				 ),
				'8'=>array( 'question' => $call.'是你要找的有缘人吗？',
							'answer' => array('live'=>'就是'.$call,'like'=>'让我再想想','not'=>'不是啊'),
							'reply' => array('live'=>'嗨，我现在在山东，我觉得我们挺合适的。','like'=>'这个世界上，总有一个人在等你，总有一个人会给你安心的幸福，总有一个人会陪你到老，这个人，要珍惜，要感恩。你是我要找的幸福吗？')
				 ),
				'9'=>array( 'question' => '如果到了世界末日，你想和'.$call.'在一起吗？',
							'answer' => array('live'=>'非常想','like'=>'会考虑和'.$call.'在一起','not'=>'我还是一个人吧'),
							'reply' => array('live'=>'亲爱的，相识即是有缘，别再错过，给个机会认识下吧！','like'=>'你好，我住在山东，希望你会记得我。')
				 ),
				'10'=>array( 'question' => '看'.$call.'的照片你觉得他的脾气会怎么样？',
							'answer' => array('live'=>'肯定是好脾气','like'=>'应该还好吧','not'=>'是个爆脾气'),
							'reply' => array('live'=>'你好，看到你的资料我觉得我们很合适，想跟你聊聊','like'=>'很高兴认识你！我在山东，期待你的回复。')
				 ),
				'11'=>array( 'question' => '你跟'.$call.'在公交车上相遇，敢去要电话吗？',
							'answer' => array('live'=>'当然敢','like'=>'鼓起勇气去要','not'=>'算了吧'),
							'reply' => array('live'=>'真心想和你相互了解，如若真心有意愿请给我回信！','like'=>'你好。我对你第一印象挺好的，希望我是你要找的那个他。如果你觉得可以的话能留下你的联系方式吗？我们彼此了解一下。期待你的来信。')
				 ),
				'12'=>array( 'question' => '你会带'.$call.'参加你的朋友聚会吗？',
							'answer' => array('live'=>'一定会带上','like'=>'会考虑看看','not'=>'不会'),
							'reply' => array('live'=>'相信你的出现不会太久,我情愿在叶子红遍的山谷为你守候.','like'=>'亲爱的，相识即是有缘，别再错过，给个机会认识下吧！')
				 ),
				'13'=>array( 'question' => '你觉得'.$call.'唱歌会好听吗？',
							'answer' => array('live'=>'绝对好听','like'=>'应该会好听','not'=>'我哪知道'),
							'reply' => array('live'=>'人潮人海中，相遇是缘，相知是份，相爱即是缘分。你是那个人嘛？','like'=>'你好，我住在山东，希望你会记得我。')
				 ),
				'14'=>array( 'question' => '你和'.$call.'交往后，你会告诉'.$call.'你以前的感情经历吗？',
							'answer' => array('live'=>'会坦白告诉'.$call,'like'=>'还是不说的好','not'=>'没这个必要'),
							'reply' => array('live'=>'关于爱情，愿得一人心，白首不相离。望早日预见你！','like'=>'真心想和你相互了解，如若真心有意愿请给我回信！')
				 ),
				'15'=>array( 'question' => '你愿意和'.$call.'一起去游乐园玩过山车吗？',
							'answer' => array('live'=>'当然，多刺激啊','like'=>'虽然怕，但是我会坚持','not'=>'还是算了吧'),
							'reply' => array('live'=>'你好，我在山东，周末有时间会去游乐园，我觉的我们很合适','like'=>'你好，我在山东，周末有时间会去游乐园，认识一下吧')
				 ),
				'16'=>array( 'question' => '你和'.$call.'交往后，你会告诉'.$call.'你以前的感情经历吗？',
							'answer' => array('live'=>'会坦白告诉'.$call,'like'=>'还是不说的好','not'=>'没这个必要'),
							'reply' => array('live'=>'真心想和你相互了解，如若真心有意愿请给我回信！','like'=>'能够遇见就是缘分，希望和你保持联系，期待你的回复')
				 ),
				'17'=>array( 'question' => '如果'.$call.'在朋友圈里发布了照片，你会？',
							'answer' => array('live'=>'迅速赞'.$call,'like'=>'看看而已','not'=>'不会理会'),
							'reply' => array('live'=>'遇到你是我的缘分，爱是一种责任，情是一种包容！','like'=>'你好，看到你的资料我觉得我们很合适，想跟你聊聊')
				 ),
				'18'=>array( 'question' => '你想陪'.$call.'一起看日落吗?',
							'answer' => array('live'=>'非常想','like'=>'还可以吧','not'=>'不想去'),
							'reply' => array('live'=>'很高兴认识你，我喜欢看日落，你愿意接受我的邀请吗？','like'=>'我们彼此了解一下。期待你的来信。')
				 ),
				'19'=>array( 'question' => '你会选择什么方式和'.$call.'表白？',
							'answer' => array('live'=>'当面说','like'=>'打电话','not'=>'不会表白'),
							'reply' => array('live'=>'弱水三千，只求唯一。无论好与坏，得之我幸，你，永远永远最美丽。','like'=>'阳光温热，岁月静好，你还不来，我怎敢老去？')
				 ),
				'20'=>array( 'question' => '你愿意和'.$call.'去吃大排档吗？',
							'answer' => array('live'=>'非常愿意','like'=>'看看钱包再说','not'=>'不愿意'),
							'reply' => array('live'=>'你好，很高兴认识你，我喜欢很多美食，我觉的我们挺合适的','like'=>'你好，很高兴认识你，我喜欢很多美食，期待你的回信')
				 ),
				'21'=>array( 'question' => '你和'.$call.'在电影院看电影，觉得不好看会中途离场吗？',
							'answer' => array('live'=>'一直陪'.$call.'看完','like'=>'和'.$call.'商量一起离开','not'=>'直接离开'),
							'reply' => array('live'=>'你好，我在山东，我的兴趣是看电影，我觉的我们挺合适的','like'=>'你好，我在山东，我的兴趣是看电影，期待你的回信。')
				 ),
				'22'=>array( 'question' => $call.'的这张照片拍的怎么样啊?',
							'answer' => array('live'=>'非常好看','like'=>'还好吧','not'=>'无语'),
							'reply' => array('live'=>'hi，你的照片拍的很好，我觉的我们挺合适的，认识一下吧','like'=>'hi，你的照片拍的很好，期待你的回信')
				 ),
				'23'=>array( 'question' => '你觉得'.$call.'笑起来的样子会好看吗？',
							'answer' => array('live'=>'肯定很好看','like'=>'这个不好说','not'=>'还是别笑了'),
							'reply' => array('live'=>'hi，你笑起来的样子一定会很好看，我觉的我们挺合适的，认识一下吧','like'=>'hi，你笑起来的样子一定会很好看，期待你的回信')
				 ),
				'24'=>array( 'question' => '你有可能和'.$call.'闪婚吗?',
							'answer' => array('live'=>'这个可以闪','like'=>'有可能吧','not'=>'绝对不可能'),
							'reply' => array('live'=>'遇到你是我的缘分，爱是一种责任，情是一种包容！','like'=>'亲爱的，相识即是有缘，别再错过，给个机会认识下吧！')
				 ),
				'25'=>array( 'question' => '如果你追'.$call.'，你觉得把握大吗？',
							'answer' => array('live'=>'很容易吧','like'=>'应该可以','not'=>'呸'),
							'reply' => array('live'=>'人潮人海中，相遇是缘，相知是份，相爱即是缘分。你是那个人嘛？','like'=>'很高兴认识你！我在山东，期待你的回复。')
				 ),
				'26'=>array( 'question' => '你想接'.$call.'下班吗？',
							'answer' => array('live'=>'想啊，多等会都可以','like'=>'可以，但是不能让我等太久','not'=>'不想，谢谢'),
							'reply' => array('live'=>'相信你的出现不会太久,我情愿在叶子红遍的山谷为你守候.','like'=>'Hello，我在山东，想和你聊聊。')
				 ),
				'27'=>array( 'question' => '看到'.$call.'你有什么感觉？',
							'answer' => array('live'=>'心跳的很快','like'=>'有一点感觉','not'=>'一点感觉都没有'),
							'reply' => array('live'=>'可以认识一下吗？我很有责任心，我觉得我们蛮有缘分的。','like'=>'Hello，我住在山东，希望和你成为朋友。')
				 ),
				'28'=>array( 'question' => $call.'在路上遇到你，向你微笑你会怎么想？',
							'answer' => array('live'=>'肯定是对我有意思','like'=>'应该对我有点感觉','not'=>$call.'是花痴'),
							'reply' => array('live'=>'不可否认爱情的轰轰烈烈，但我更喜欢享受平平淡淡中的甜蜜。希望可以和你做朋友','like'=>'Hello，我住在山东，我觉得我们蛮有缘分的。')
				 ),
				'29'=>array( 'question' => '你和'.$call.'一起拍一部爱情剧，你会？',
							'answer' => array('live'=>'假戏真做','like'=>'慢慢磨合','not'=>'呸'),
							'reply' => array('live'=>'一颗真心，一辈子终老，相识是缘分，期待你的回信！','like'=>'当你在网络的茫茫人海中寻觅累了，请你暂停匆匆的脚步，或许这里有你不错的选择。')
				 ),
				'30'=>array( 'question' => '你觉得和'.$call.'交往多久会考虑结婚？',
							'answer' => array('live'=>'和'.$call.'闪婚我都可以','like'=>'半年或者一年吧','not'=>'不会考虑和'.$call.'结婚'),
							'reply' => array('live'=>'茫茫人海我们相识，相遇，相知，我们约会吧！','like'=>'感觉你很不错，你觉的我会是你的未来吗？')
				 ),
				'31'=>array( 'question' => '你看到'.$call.'的微博后，你会?',
							'answer' => array('live'=>'立即关注','like'=>'先看看','not'=>'不会关注'),
							'reply' => array('live'=>'Hi，我在山东，我觉得我们挺合适的。','like'=>'很高兴认识你！我住在山东，期待你的回复。')
				 ),
				'32'=>array( 'question' => '如果'.$call.'参加江苏卫视《非诚勿扰》你会怎样？',
							'answer' => array('live'=>'直接爆灯','like'=>'观察一下','not'=>'灭灯'),
							'reply' => array('live'=>'嗨，都说缘分天注定，我觉得缘分就在你我之间，一言一语间，不再陌生。','like'=>'简单的你简单的我，为何在茫茫人海中不能早点相遇。')
				 ),
				'33'=>array( 'question' => '如果'.$call.'非常喜欢吃“榴莲”你会怎么样？',
							'answer' => array('live'=>'我也超爱吃的','like'=>'忍住陪'.$call.'吃','not'=>'受不了'),
							'reply' => array('live'=>'Hi，我在山东工作，我的兴趣是烹饪，我觉得我们蛮有缘分的。','like'=>'Hi，我在山东工作，我的兴趣是烹饪，期待你的回信')
				 ),
				'34'=>array( 'question' => $call.'是很喜欢养狗，对此你怎么看？',
							'answer' => array('live'=>'太巧了，我也喜欢养狗','like'=>'我可以为了'.$call.'养狗','not'=>'我讨厌狗'),
							'reply' => array('live'=>'你我相识相知相爱，你就是我的另一半，亲爱的我等你遇见我，一起走向幸福','like'=>'可以认识一下么？我在山东工作，我觉得我们挺合适的。')
				 ),
				'35'=>array( 'question' => '你会请'.$call.'吃很贵的冰淇淋吗？',
							'answer' => array('live'=>'再贵也请','like'=>'要想想','not'=>'不会'),
							'reply' => array('live'=>'希望在这里能找到一个一起到白头的伴侣，我们有这个缘分吗？','like'=>'在茫茫人海相识是一种缘分，不知道我们是不是有缘。期待我们能相识，期待我的缘分')
				 ),
				'36'=>array( 'question' => '如果'.$call.'是你的相亲对象，见过面后你会？',
							'answer' => array('live'=>'抓紧联系','like'=>'可以在谈谈','not'=>'不再联系'),
							'reply' => array('live'=>'弱水三千，只求唯一。无论好与坏，得之我幸，你，永远永远最美丽。','like'=>'能够遇见就是缘分，希望和你保持联系，期待你的回复')
				 ),
				'37'=>array( 'question' => '你觉得你和'.$call.'有夫妻相吗？',
							'answer' => array('live'=>'嗯，真的超级般配','like'=>'听你这么一说，还真有点','not'=>'完全没有'),
							'reply' => array('live'=>'阳光温热，岁月静好，你还不来，我怎敢老去？','like'=>'真心想和你相互了解，如若真心有意愿请给我回信！')
				 ),
				'38'=>array( 'question' => '你觉得'.$call.'会喜欢喝以下哪种饮料？',
							'answer' => array('live'=>'王老吉','like'=>'加多宝','not'=>'不知道'),
							'reply' => array('live'=>'亲爱的，相识即是有缘，别再错过，给个机会认识下吧！','like'=>'很高兴认识你！我住在山东，我一般比较喜欢烹饪希望你会记得我。')
				 ),
				'39'=>array( 'question' => '如果'.$call.'过生日，你会送他什么样的礼物？',
							'answer' => array('live'=>'香吻一个','like'=>'自己手工制作礼物','not'=>'什么也不送'),
							'reply' => array('live'=>'用心跳诠释！希望我能让你的心有不一样的感觉！','like'=>'很高兴认识你！我在山东，期待你的回复。')
				 ),
				'40'=>array( 'question' => '你会经常关注'.$call.'的QQ动态吗？',
							'answer' => array('live'=>'必须关注','like'=>'会偶尔看下','not'=>'不会关注'),
							'reply' => array('live'=>'看过你的资料，感觉我们挺合适的，相识也是一种缘分，希望能与你认识一下','like'=>'我们彼此了解一下。期待你的来信。')
				 ),
				'41'=>array( 'question' => '如果'.$call.'参加浙江卫视《转身遇到他》你会怎样?',
							'answer' => array('live'=>'立即转椅子','like'=>'会考虑下','not'=>'不转椅子'),
							'reply' => array('live'=>'关于爱情，愿得一人心，白首不相离。望早日预见你！','like'=>'先来个自我介绍吧！我在山东，希望和你成为朋友。')
				 ),
				'42'=>array( 'question' => '你更喜欢什么样的相处方式和'.$call.'相处？',
							'answer' => array('live'=>'两个有说不完的话','like'=>'在一起安安静静的','not'=>'不想和'.$call.'相处'),
							'reply' => array('live'=>'我是缘，你就是我的份，我的路上需要你，让我牵着你好吗','like'=>'哈喽，我在山东，我们可以进一步了解彼此。')
				 ),
				'43'=>array( 'question' => $call.'做你的另一半如何？',
							'answer' => array('live'=>'好主意','like'=>'试试看','not'=>'我咧个去'),
							'reply' => array('live'=>'你好，我来这里就是找到人生中的她，我想你就是，记得我就在这里。','like'=>'你好，很高兴认识你，希望能成为你想等待的那个人')
				 ),
			);
	if(!$all){
		$count = count($result);
		$number = mt_rand(0,$count);
		$result = $result[$number];
	}
	return $result;
	
}

?>