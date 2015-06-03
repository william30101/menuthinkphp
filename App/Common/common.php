<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-9-2
 * Time: 下午4:27
 * To change this template use File | Settings | File Templates.
 */
/**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function safe_replace($string) {
    $string = str_replace('%20','',$string);
    $string = str_replace('%27','',$string);
    $string = str_replace('%2527','',$string);
    $string = str_replace('*','',$string);
    $string = str_replace('"','&quot;',$string);
    $string = str_replace("'",'',$string);
    $string = str_replace('"','',$string);
    $string = str_replace(';','',$string);
    $string = str_replace('<','&lt;',$string);
    $string = str_replace('>','&gt;',$string);
    $string = str_replace("{",'',$string);
    $string = str_replace('}','',$string);
    $string = str_replace('\\','',$string);
    $string = remove_xss($string);
    return $string;
}

/**
 * xss过滤函数
 *
 * @param $string
 * @return string
 */
function remove_xss($string) {
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);

    $parm1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');

    $parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

    $parm = array_merge($parm1, $parm2);

    for ($i = 0; $i < sizeof($parm); $i++) {
        $pattern = '/';
        for ($j = 0; $j < strlen($parm[$i]); $j++) {
            if ($j > 0) {
                $pattern .= '(';
                $pattern .= '(&#[x|X]0([9][a][b]);?)?';
                $pattern .= '|(&#0([9][10][13]);?)?';
                $pattern .= ')?';
            }
            $pattern .= $parm[$i][$j];
        }
        $pattern .= '/i';
        $string = preg_replace($pattern, '', $string);
    }
    return $string;
}

function pager($select,$p,$display){
    R("Comm/Page/index",array($select,$p,$display));
}

function md5Encrypt($str){
    $key='*M3~A0!R9@C1"#H7%s2^o/f4-t?3';
    return md5(md5($str.$key).$key);
}
function get_time(){
    return date('Y-m-d H:i:s',time());
}

function logs($adminId){
	if($adminId){
	    $From = D('logs');
	    $data['adminid'] = $adminId;
	    $data['loginip'] = get_client_ip();
	    $data['logintime'] = NOW_TIME;
	    $From->add($data);
	}
}

/**
 * author Tony
 * 2013-09-09
 * 写入缓存，默认为文件缓存，不加载缓存配置。
 * @param $name 缓存名称
 * @param $data 缓存数据
 * @param $filepath 数据路径（模块名称） caches/cache_$filepath/
 * @param $type 缓存类型[file,memcache,apc]
 * @param $config 配置名称
 * @param $timeout 过期时间
 */
function setcache($name, $data, $filepath='', $type='file', $config='', $timeout=0) {
    import('ORG.Cache.Cache_factory');

    if($config) {
        import('ORG.Configs.Configs');
        $cacheconfig = Configs::load_config('cache');
        $cache = Cache_factory::get_instance($cacheconfig)->get_cache($config);
    } else {
        $cache = cache_factory::get_instance()->get_cache($type);
    }

    return $cache->set($name, $data, $timeout, '', $filepath);
}
/**
 * 读取缓存，默认为文件缓存，不加载缓存配置。
 * @param string $name 缓存名称
 * @param $filepath 数据路径（模块名称） caches/cache_$filepath/
 * @param string $config 配置名称
 */
function getcache($name, $filepath='', $type='file', $config='') {
    import('ORG.Cache.Cache_factory');
    if($config) {
        import('ORG.Configs.Configs');
        $cacheconfig = Configs::load_config('cache');
        $cache = cache_factory::get_instance($cacheconfig)->get_cache($config);
    } else {
        $cache = cache_factory::get_instance()->get_cache($type);
    }

    return $cache->get($name, '', '', $filepath);
}

/**
 * 删除缓存，默认为文件缓存，不加载缓存配置。
 * @param $name 缓存名称
 * @param $filepath 数据路径（模块名称） caches/cache_$filepath/
 * @param $type 缓存类型[file,memcache,apc]
 * @param $config 配置名称
 */
function delcache($name, $filepath='', $type='file', $config='') {
    import('ORG.Cache.Cache_factory');
    if($config) {
        import('ORG.Configs.Configs');
        $cacheconfig = Configs::load_config('cache');
        $cache = cache_factory::get_instance($cacheconfig)->get_cache($config);
    } else {
        $cache = cache_factory::get_instance()->get_cache($type);
    }
    return $cache->delete($name, '', '', $filepath);
}

/**
 * 读取缓存，默认为文件缓存，不加载缓存配置。
 * @param string $name 缓存名称
 * @param $filepath 数据路径（模块名称） caches/cache_$filepath/
 * @param string $config 配置名称
 */
function getcacheinfo($name, $filepath='', $type='file', $config='') {
    import('ORG.Cache.Cache_factory');
    if($config) {
        import('ORG.Configs.Configs');
        $cacheconfig = Configs::load_config('cache');
        $cache = cache_factory::get_instance($cacheconfig)->get_cache($config);
    } else {
        $cache = cache_factory::get_instance()->get_cache($type);
    }
    return $cache->cacheinfo($name, '', '', $filepath);
}


/**
 * 获得一个唯一id
 * @param string $prefix
 * @return string
 * author Tony
 */
function getOnlyStr($prefix=null){
	return substr(uniqid($prefix),0,10);
}


/**
 * 发送邮件
 * @author Tony
 * @param unknown $toEmail	发送邮件地址
 * @param unknown $title	邮件标题
 * @param unknown $content	邮件内容
 * @param unknown $verify	是否需要验证:true/false	默认为:true
 * @param string  $isHtml	是否为Html:true/false	默认为:true
 * @param string  $fromName	发件人名
 * @return boolean
 */
function sendEmail($toEmail, $title, $content, $verify=true, $isHtml=true, $fromName='铭讯网'){
	Vendor('PHPMailer.class#phpmailer');

	$mail=new PHPMailer();
	// 设置用户名和密码。
	//$mail->Username='dev@yujiaguo.com';
	//$mail->Password='jiaoyou123';
    $mail->Username='kefu@dishes.com';
    $mail->Password='qilu1308';
	// 设置PHPMailer使用SMTP服务器发送Email
	$mail->IsSMTP();
	// 设置邮件的字符编码，若不指定，则为'UTF-8'
	$mail->CharSet='UTF-8';
	// 添加收件人地址，可以多次使用来添加多个收件人
	$mail->AddAddress($toEmail);
	//设置发送为 Html
	$mail->IsHTML($isHtml);
	// 设置邮件正文
	$mail->Body= $content;
	// 设置邮件头的From字段。
	$mail->From= $mail->Username;
	// 设置发件人名字
	$mail->FromName= $fromName;
	// 设置邮件标题
	$mail->Subject= $title;
	// 设置SMTP服务器。
	$mail->Host='smtp.exmail.qq.com';
	// 设置为"需要验证"
	$mail->SMTPAuth=$verify;
	// 发送邮件。
	return($mail->Send());
}


/**
 * 发送简讯
 * @author Tony
 * @param unknown $mobiles	手机号, 如 array('159xxxxxxxx'),如果需要多个手机号群发,如 array('159xxxxxxxx','159xxxxxxx2') 
 * @param unknown $content	短信内容，每条为67个字(包括签名)
 * @param string $sendTime	定时发送时间，格式为 yyyymmddHHiiss, 即为 年年年年月月日日时时分分秒秒,例如:20090504111010 代表2009年5月4日 11时10分10秒
 * 							如果不需要定时发送，请为'' (默认)
 * @param string $addSerial	扩展号, 默认为 ''
 * @param string $charset	内容字符集, 默认UTF-8
 * @param number $priority	优先级, 默认5
 * @return boolean			操作结果状态
 * 
 */
function sendSMS($mobiles,$content,$sendTime='',$addSerial='',$charset='UTF-8',$priority=5){
    //echo "<Br>----mobile:".$mobiles ;
    //echo "<Br>----content:".$content ;
	set_time_limit(0);
	header("Content-Type: text/html; charset=UTF-8");
	/**
	 * 网关地址
	*/
	$gwUrl = 'http://sdkhttp.eucp.b2m.cn/sdk/SDKService';

    /*
        // 序列号,请通过亿美销售人员获取
        $serialNumber = '0SDK-EAA-0130-NDSMK';
        //密码,请通过亿美销售人员获取

        $password = '026591';

        //登录后所持有的SESSION KEY，即可通过gin方法时创建

        $sessionKey = '529699';
 */


        // 序列号,请通过亿美销售人员获取
        $serialNumber = '3SDK-SLK-0130-NCVMN';
        //密码,请通过亿美销售人员获取

        $password = '163904';

        //登录后所持有的SESSION KEY，即可通过login方法时创建

        $sessionKey = '218698';

	/**
	 * 连接超时时间，单位为秒
	 */
	$connectTimeOut = 2;
	 
	/**
	 * 远程信息读取超时时间，单位为秒
	 */
	$readTimeOut = 10;
	 
	/**
	 $proxyhost		可选，代理服务器地址，默认为 false ,则不使用代理服务器
	 $proxyport		可选，代理服务器端口，默认为 false
	 $proxyusername	可选，代理服务器用户名，默认为 false
	 $proxypassword	可选，代理服务器密码，默认为 false
	 */
	$proxyhost = false;
	$proxyport = false;
	$proxyusername = false;
	$proxypassword = false;
	 
	Vendor('PHPSMSer.include.Client');
	$client = new Client($gwUrl,$serialNumber,$password,$sessionKey,$proxyhost,$proxyport,$proxyusername,$proxypassword,$connectTimeOut,$readTimeOut);
	/**
	 * 发送向服务端的编码，如果本页面的编码为UTF-8，请使用UTF-8
	*/
	$client->setOutgoingEncoding("UTF-8");
	
	//登录
	$statusCode = $client->login();
	//注册企业信息
	$eName = "济南铭讯科技有限公司";
	$linkMan = "张先森";
	$phoneNum = "0531-66725888";
	$mobile = "13060601010";
    $mobile = "15863185186";
	$email = "196551515@qq.com";
	$fax = "0531-66725888";
	$address = "济南市历城区华龙路1825号嘉恒大厦B座2-2105室";
	$postcode = "250101";

	$statusCode = $client->registDetailInfo($eName,$linkMan,$phoneNum,$mobile,$email,$fax,$address,$postcode);

	//发送简讯
	$statusCode = $client->sendSMS($mobiles,$content.'【铭讯】',$sendTime,$addSerial,$charset);
	
// 	$balance = $client->getBalance();
// 	return "余额:".$balance;die;
	return $statusCode;die;

	return $statusCode=="0" ? true : false;
}

/**
 * author Tony
 * 2013-09-26
 * 两个浮点变量是否相等
 * @param $f1
 * @param $f2
 * @param int $precision
 * @return bool
 */
function floatcmp($f1,$f2,$precision = 10) {// are 2 floats equal
    $e = pow(10,$precision);
    $i1 = intval($f1 * $e);
    $i2 = intval($f2 * $e);
    return ($i1 == $i2);
}

/**
 * author Tony
 * 2013-09-26
 * 一个浮点变量是否大于别一个浮点
 * @param $big
 * @param $small
 * @param int $precision
 * @return bool
 */
function floatgtr($big,$small,$precision = 10) {// is one float bigger than another
    $e = pow(10,$precision);
    $ibig = intval($big * $e);
    $ismall = intval($small * $e);
    return ($ibig > $ismall);
}

/**
 * author Tony
 * 2013-09-26
 * 一个浮点变量是否大于等于另一个
 * @param $big
 * @param $small
 * @param int $precision
 * @return bool
 */
function floatgtre($big,$small,$precision = 10) {// is on float bigger or equal to another
    $e = pow(10,$precision);
    $ibig = intval($big * $e);
    $ismall = intval($small * $e);
    return ($ibig >= $ismall);
}

/**
 * author Tony
 * 2013-09-28
 * @param $big
 * @return bool
 */
function out_check($info) {
    echo  '{"info":"'.$info['info'].'","status":"'.$info['status'].'"}';
}
/**
 * 通过user表中字段名和值获取一条用户信息（该字段值必须是唯一的）
 * @author Tony
 * @param unknown $field	字段名
 * @param unknown $val		字段值
 * @return Ambigous <boolean, mixed, NULL, multitype:, unknown, string>
 */
function getUserInfoByData($field,$val){
		$result = false;
		$arr = array('userid','username','email','phone');
		if(in_array($field, $arr)){
			$User = D('user');
			$result = $User->where($field.'='.$val)->limit('1')->select();
		}
		return $result;
}

/**
 * 通过user表中字段名和值获取与该字段值相似的所有信息
 * @author Tony
 * @param unknown $field	字段名
 * @param unknown $val		字段值
 * @return Ambigous <boolean, mixed, string, NULL, unknown>
 */
function getUserInfosLikeData($field,$val){
	$result = false;
	if($field){
		$User = D('user');
        $where[$field] = array('like',"%$val%");
		$result = $User->where($where)->select();
	}
	return $result;
}

/**
 * 通过id获取partner_profile表中单条数据
 * @author Tony
 * @param unknown $id 数据id
 * @return Ambigous <boolean, mixed, NULL, multitype:, unknown, string>
 */
function getUserProfileById($id){
	$result = false;
	if($id){
		$From = D('partner_profile');
		$result = $From->where('partnerid='.$id)->find();
	}
	return $result;
}

/**
 * 通过用户id获得该用户的资料信息
 * @author Tony
 * @param unknown $userid
 * @return Ambigous <multitype:, mixed, boolean, NULL, unknown, string, number, Exception>
 */
function getUserProfileByUserId($userid){
	$result = array();
	if($userid){
		$From = D('partner_profile');
		$result = $From->where('userid='.$userid)->find();
	}
	return $result;
}

/**
 * 通过用户id获得该用户的参数
 * @author Tony
 * @param unknown $userid
 * @return Ambigous <multitype:, mixed, boolean, NULL, unknown, string, number, Exception>
 */
function getUserParamsByUserId($userid){
	$result = array();
	if($userid){
		$From = D('partner_params');
		$result = $From->where('userid='.$userid)->find();
	}
	return $result;
}
/**
 * 功能：循环检测并创建文件夹
 * 参数：$path 文件夹路径
 * author Tony
 */
function createDir($path){
	if (!file_exists($path)){
		createDir(dirname($path));
		mkdir($path, 0777);
	}
}


/**
 * 校验验证码
 * author Tony
 */
function checkVerify($code_name='verify',$code_val=''){
    if($code_name==''&& $code_val==''){
        return false;
    }
    $verify = md5($code_val);
    $sessionVerify = session($code_name);
    if($verify == $sessionVerify){
        return true;
    }else{
        return false;
    }
}


function authcode_key()
{
    return md5('#$^%&GHFkikkh%^*)(_+|}kll');
}

/**
 * @param $string 明文 或 密文
 * @param string DECODE表示解密,其它表示加密
 * @param string 密匙
 * @param int 密文有效期
 * @return string
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
    $ckey_length = 4;
    // 密匙
    $key = md5($key ? $key : authcode_key());
    // 密匙a会参与加解密
    $keya = md5(substr($key, 0, 16));
    // 密匙b会用来做数据完整性验证
    $keyb = md5(substr($key, 16, 16));
    // 密匙c用于变化生成的密文
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    // 参与运算的密匙
    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    // 产生密匙簿
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // 核心加解密部分
    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // 从密匙簿得出密匙进行异或，再转成字符
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if($operation == 'DECODE') {
        // substr($result, 0, 10) == 0 验证数据有效性
        // substr($result, 0, 10) - time() > 0 验证数据有效性
        // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
        // 验证数据有效性，请看未加密明文的格式
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}


/**
 * 中文截取，支持gb2312,gbk,utf-8,big5
 * @author Tony
 * @param string $str 要截取的字串
 * @param int $start 截取起始位置
 * @param int $length 截取长度
 * @param string $charset utf-8|gb2312|gbk|big5 编码
 * @param $suffix 是否加尾缀
 */
function csubstr($str, $start=0, $length, $suffix=true , $charset="utf-8"){
	if(function_exists("mb_substr")){
		if(mb_strlen($str, $charset) <= $length) return $str;
		$slice = mb_substr($str, $start, $length, $charset);
	}else{
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']          = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']          = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		if(count($match[0]) <= $length) return $str;
		$slice = join("",array_slice($match[0], $start, $length));
	}
	if($suffix) return $slice."...";
	return $slice;
}

/**
 * author Tony
 * 2013-10-15
 * 在线交易订单支付处理函数
 * 函数功能：根据支付接口传回的数据判断该订单是否已经支付成功；
 * 返回值：如果订单已经成功支付，返回true，否则返回false；
 * @param $ordid
 * @return bool
 */
function checkorderstatus($ordid){
    $Ord=M('payment_log');
    $ordstatus=$Ord->where('ordid='.$ordid)->getField('ordstatus');
    if($ordstatus==1){
        return true;
    }else{
        return false;
    }
}

/**
 * author Tony
 * 2013-10-15
 * 处理支付订单函数
 * 更新订单状态，写入订单支付后返回的数据
 * @param $parameter
 */
function orderhandle($parameter){
    $ordid=$parameter['out_trade_no'];
    $data['payment_trade_no']      =$parameter['trade_no'];
    $data['payment_trade_status']  =$parameter['trade_status'];
    $data['payment_notify_id']     =$parameter['notify_id'];
    $data['payment_notify_time']   =$parameter['notify_time'];
    $data['payment_buyer_email']   =$parameter['buyer_email'];
    $data['ordstatus']             =1;
    $Ord=M('payment_log');
    $Ord->where('ordid='.$ordid)->save($data);
}

/**
 * author Tony
 * 2013-10-22
 * 处理传过到的图片地址
 * @param string $id
 * @return string
 */
function user_photo_url($photourl='')
{
    $syste_upload_url =C('UPLOAD_URL');
    $str = '';
    if($photourl!=''){
        $str =$syste_upload_url.$photourl;
    }
    else{
        $str='/Public/User/Default/images/zwzp_f.jpg';
    }
    return $str;
}

function diary_show_url($id='')
{
    $str = '';
    if($id!=''){
        $str ='/diary/show-'.$id.'.html';
    }
    return $str;
}


/**
 * 将字符串转换为数组
 *
 * @param	string	$data	字符串
 * @return	array	返回数组格式，如果，data为空，则返回空数组
 */
function string2array($data) {
    if($data == '') return array();
    @eval("\$array = $data;");
    return $array;
}
/**
 * 将数组转换为字符串
 *
 * @param	array	$data		数组
 * @param	bool	$isformdata	如果为0，则不使用new_stripslashes处理，可选参数，默认为1
 * @return	string	返回字符串，如果，data为空，则返回空
 */
function array2string($data, $isformdata = 1) {
    if($data == '') return '';
    if($isformdata) $data = new_stripslashes($data);
    return addslashes(var_export($data, TRUE));
}

/**
 * 判断email格式是否正确
 * @param $email
 */
function is_email($email) {
    return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

/**
 * 实现 escape 解码
 * @author Tony
 * @param unknown $str
 * @return Ambigous <string, unknown>
 */
function unescape($str){
	$ret = '';
	$len = strlen($str);
	for ($i = 0; $i < $len; $i++){
		if ($str[$i] == '%' && $str[$i+1] == 'u'){
			$val = hexdec(substr($str, $i+2, 4));
			if ($val < 0x7f) $ret .= chr($val);
			else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));
			else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));
			$i += 5;
		}
		else if ($str[$i] == '%'){
			$ret .= urldecode(substr($str, $i, 3));
			$i += 2;
		}
		else $ret .= $str[$i];
	}
	return $ret;
}

/**
 * 仿select下拉框设计	出生日期
 * @author Tony
 * @param unknown $year		年
 * @param unknown $month	月
 * @param unknown $day		日
 * @param unknown $yearId	年id/name
 * @param unknown $monthId	月id/name
 * @param unknown $dayId	日id/name
 * @param string  $width	下拉框的宽度，如：'90'为'width:90px;'
 * @param string  $default	默认显示的，如：'请选择'
 * @return string
 */
function T_ymd($year, $month, $day, $yearId, $monthId, $dayId) {
	$string   = '<div class="za-item-selector" >';
	$yearVal = '请选择';
	$monthVal = '请选择';
	$dayVal = '请选择';
	if($year && $month && $day){
		$yearVal = $year;
		$monthVal = $month;
		$dayVal = $day;
	}
	$string .= '<input class="T_year " id="'.$yearId.'"  type="hidden" name="'.$yearId.'"  value="'.$year.'">';
	$string .= '<input class="T_month" id="'.$monthId.'" type="hidden" name="'.$monthId.'" value="'.$month.'">';
	$string .= '<input class="T_day"   id="'.$dayId.'"   type="hidden" name="'.$dayId.'"   value="'.$day.'">';
	//从90后到30后
	$yearStr =  yearXXToStr(9);
	$yearStr .= yearXXToStr(8);
	$yearStr .= yearXXToStr(7);
	$yearStr .= yearXXToStr(6);
	$yearStr .= yearXXToStr(5);
	$yearStr .= yearXXToStr(4);
	$yearStr .= yearXXToStr(3);
	$dynamicWidth = '';
	
	$string .= '<dl class="year-selector"><dt>'.$yearVal.'</dt><dd style="display: none;">'.$yearStr.'</dd></dl><span>年</span>';
	$string .= '<dl class="month-selector"><dt>'.$monthVal.'</dt><dd style="display: none;"><p><a data="1" href="javascript:;">1</a><a data="2" href="javascript:;">2</a><a data="3" href="javascript:;">3</a><a data="4" href="javascript:;">4</a><a data="5" href="javascript:;">5</a><a data="6" href="javascript:;">6</a><a data="7" href="javascript:;">7</a><a data="8" href="javascript:;">8</a><a data="9" href="javascript:;">9</a><a data="10" href="javascript:;">10</a><a data="11" href="javascript:;">11</a><a data="12" href="javascript:;">12</a></p></dd></dl><span>月</span>';
	$string .= '<dl class="day-selector"><dt>'.$dayVal.'</dt><dd style="display: none;"><p>请先选择年月</p></dd></dl><span>日</span>';
	$string .= '</div><span id="T_ymd_birthday_info"></span>';
	return $string;
}

/**
 * 私有方法，生成年份字符串
 * @author Tony
 * @param unknown $year	年份，如90后为：9、80后为：8 ...
 * @return string
 */
function yearXXToStr($year){
	$string = '';
	if($year){
		$yearStr = '<p><i>'.$year.'0后:</i>';
		for($i=0;$i<=9;$i++){
			if($year == 9 && $i > 6){
				$yearStr .= '';
			}else{
				$yearStr .= '<a data="19'.$year.$i.'" href="javascript:;">19'.$year.$i.'</a>';
			}
		}
		$string = $yearStr.'</p>';
	}
	return $string;
}


/**
 * 仿select下拉框设计	多词下拉框铺满
 * @author Tony
 * @param unknown $array		词语集合
 * @param unknown $ids			以，分开的id字符串
 * @param unknown $name			input名称
 * @param string  $width	下拉框的宽度，如：'90'为'width:90px;'
 * @param string  $unfoldWidth 下拉框展开的宽度，如：'90'为'width:90px;'
 * @param string  $singleOrMore	单选或多选，默认1为多选
 * @param string  $default	默认显示的，如：'请选择'
 * @param unknown $must		是否必填，不等于''或0为必填，否则相反
 * @param string  $suffix	后缀
 * @return string
 */
function T_wordsMeeting($array = array(), $ids = '',$name = '', $width='90',$unfoldWidth='270', $singleOrMore = '1', $default = '请选择',$must='', $suffix='') {
	if(!$unfoldWidth){
		$unfoldWidth = '270';
	}
	if(!$must){
		$must = 0;
	}else{
		$must = 1;
	}
	if(!$singleOrMore){
		$singleOrMore = 0;
	}else{
		$singleOrMore = 1;
	}
	$string   = '<div class="sel wordsMeetingClass" must="'.$must.'" singleOrMore="'.$singleOrMore.'" style=" float: left;width:'.$width.'px;">';
	if($ids){
		$contentStr = '';
		$idArr = explode(',',$ids);
		foreach($array as $k => $v){
			foreach($idArr as $vv){
				if($k == $vv){
					$contentStr .= $array[$vv].',';
				}
			}
		}
		$contentStr = trim($contentStr,',');
		$string .= '<p class="selcon"><span name="spanDis" style="height:23px;overflow:hidden;width:'.($width-40).'px;">'.$contentStr.'</span><i></i></p>';
	}elseif($default){
		$string .= '<p class="selcon"><span name="spanDis" style="height:23px;overflow:hidden;width:'.($width-40).'px;">'.$default.'</span><i></i></p>';
	}


	$string .= '<input id="'.$name.'" type="hidden" name="'.$name.'" class="nameInput" value="'.$ids.'">';
	$valStr .= '<p class="hover">';
	foreach($array as $k => $v){
		if($idArr){
			$is_on='';
			foreach($idArr as $vv){
				if($k == $vv){
					$is_on=' on';
				}
			}
			$valStr .= '<a class="wordBorder'.$is_on.'" data="'.$k.'">'.$v.'</a>';
		}else{
			$valStr .= '<a class="wordBorder" data="'.$k.'">'.$v.'</a>';
		}
	}
	$valStr .= '</p>';
	$valStr .= '<span style="float:right;margin:0 2% 2%;"><input type="button" class="sure guide_correcticon01" value="确定"><input type="button" data="'.$default.'" style="margin-left:5px;" class="empty guide_correcticon02" value="清空"></span>';
	$string .= '<div id="'.$name.'_val" class="down-ads" style="width:'.$unfoldWidth.'px;display: none;">'.$valStr.'</div>';
	$string .= '</div><span id="'.$name.'_info"></span>';
	return $string;
}


/**
 * 
 * @param unknown $array	词语集合
 * @param unknown $oneVal	第一个值
 * @param unknown $oneName	第一个input的name名
 * @param unknown $twoVal	第二个值
 * @param unknown $twoName	第二个input的name名
 * @param string $width		下拉框的宽度，如：'90'为'width:90px;'
 * @param string $unfoldWidth	下拉框展开的宽度，如：'90'为'width:90px;'
 * @param string $default	默认显示的，如：'请选择'
 * @param string $press		内容中需要添加的一个值，如：不限
 * @param string $must		是否必填，不等于''或0为必填，否则相反
 * @param string $suffix	后缀
 * @return string
 */
function T_smallToLarge($array = array(), $oneVal,$oneName,$twoVal,$twoName, $width='90',$unfoldWidth='270', $default = '请选择',$press='', $must='', $suffix=''){
	if(!$unfoldWidth){
		$unfoldWidth = '270';
	}
	if(!$must){
		$must = 0;
	}else{
		$must = 1;
	}
	//如果第一个值大于第二个值，进行位置调换
	if($oneVal > $twoVal && $twoVal !=0 && $twoVal !=''){
		$temp = $oneVal;
		$oneVal = $twoVal;
		$twoVal = $temp;		
	}elseif(!twoVal){
		$twoVal  = 0;
	}
	$string   = '<div class="sel smallToLargeClass" must="'.$must.'" style=" float: left;width:'.$width.'px;">';
	$oneData = $twoData = '';
	if($oneVal && $twoVal){
		$contentStr = '';
		foreach($array as $k => $v){
			if($k == $oneVal){
				$contentStr .= $oneData = $v;
			}
			if($k == $twoVal){
				$twoData = $v;
				$contentStr .= ' 至 '.$v;
			}
		}
		$string .= '<p class="selcon"><span name="spanDis" style="height:23px;overflow:hidden;width:'.($width-40).'px;">'.$contentStr.'</span><i></i></p>';
	}elseif($default){
		$string .= '<p class="selcon"><span name="spanDis" style="height:23px;overflow:hidden;width:'.($width-40).'px;">'.$default.'</span><i></i></p>';
	}
	$string .= '<input type="hidden" class="oneSwitchInput" value="'.$oneData.'" data="'.$oneVal.'">';
	$string .= '<input type="hidden" class="twoSwitchInput" value="'.$twoData.'" data="'.$twoVal.'">';
	$string .= '<input type="hidden" class="reminderName" value="'.$oneName.$twoName.'">';
	$string .= '<input id="'.$oneName.'" type="hidden" name="'.$oneName.'" class="oneInput" value="'.$oneVal.'">';
	$string .= '<input id="'.$twoName.'" type="hidden" name="'.$twoName.'" class="twoInput" value="'.$twoVal.'">';
	$valStr .= '<p class="hover">';
	foreach($array as $k => $v){
		if($k == $oneVal || $k == $twoVal){
			$valStr .= '<a class="wordBorder on" data="'.$k.'">'.$v.'</a>';
		}else{
			$valStr .= '<a class="wordBorder" data="'.$k.'">'.$v.'</a>';
		}
	}
	$valStr .= '<a class="wordBorder" data="0">'.$press.'</a>';
	$valStr .= '</p>';
	$valStr .= '<span style="float:right;margin:0 2% 2%;"><input type="button" class="sure guide_correcticon01" value="确定"><input type="button" data="'.$default.'" style="margin-left:5px;" class="empty guide_correcticon02" value="清空"></span>';
	$string .= '<div class="down-ads" style="width:'.$unfoldWidth.'px;display: none;">'.$valStr.'</div>';
	if($suffix){
		$string .= '</div><span style="float: left; padding:5px 5px 0 5px;">'.$suffix.'</span>';
	}else{
		$string .= '</div><span style="float: left;"></span>';
	}
	$string .= '<span id="'.$oneName.$twoName.'_info"></span>';
	return $string;
}

/**
 *
 * @param unknown $array	词语集合
 * @param unknown $oneVal	第一个值
 * @param unknown $oneName	第一个input的name名
 * @param unknown $twoVal	第二个值
 * @param unknown $twoName	第二个input的name名
 * @param string $width		下拉框的宽度，如：'90'为'width:90px;'
 * @param string $unfoldWidth	下拉框展开的宽度，如：'90'为'width:90px;'
 * @param string $default	默认显示的，如：'请选择'
 * @param string $press		内容中需要添加的一个值，如：不限
 * @param string $must		是否必填，不等于''或0为必填，否则相反
 * @param string $suffix	后缀
 * @return string
 */
function T_radio($array = array(), $id,$inputName, $width='200'){
	$string   = '<div class="radioClass" style="width:'.$width.'px;">';
	foreach($array as $k => $v){
		if($k == $id){
			$string .= '<span class="surroundRadio now" style="float: left;"><input style="margin-left:5px;" type="radio" name="'.$inputName.'" checked class="inputRadio" value="'.$k.'" data="'.$v.'"><span style="margin:5px;" >'.$v.'</span></span>';
		}else{
			$string .= '<span class="surroundRadio" style="float: left;"><input style="margin-left:5px;" type="radio" name="'.$inputName.'" class="inputRadio" value="'.$k.'" data="'.$v.'"><span style="margin:5px;" >'.$v.'</span></span>';
		}
	}
	$string .= '<span class="inputRadio_info"></span>';
	$string .= '</div>';
	return $string;
}


/**
 *
 * @param unknown $array	词语集合
 * @param unknown $id		值
 * @param unknown $inputName	input的name名
 * @param string $width		下拉框的宽度，如：'90'为'width:90px;'
 * @param string $unfoldWidth	下拉框展开的宽度，如：'90'为'width:90px;'
 * @param string $prefix	前缀，如：'学历'
 * @param string $press		内容中需要添加的一个值，如：不限
 * @param string $must		是否必填，不等于''或0为必填，否则相反
 * @param string $suffix	后缀
 * @return string
 */
function T_Q_box($array = array(), $id,$inputName, $unfoldWidth='73',$prefix=null){
	$string   = '<div class="box" ><div class="item">'.$prefix.'</div><div class="ipt_box">';
	$string  .= '<div class="box_value_bg">
	<input type="text" class="box_value" value="'.$array[$id].'">
			<input type="hidden" id="'.$inputName.'" name="'.$inputName.'" class="hidden_value" value="'.$id.'">
		    <input type="hidden" class="li_hidden_value" value="'.$id.'">
	</div><ul style="width:'.$unfoldWidth.'px;display:none;">';
	foreach($array as $k => $v){
		if($k == $id){
			$string .= '<li id="li_'.$k.'" class="hover" value="'.$k.'">'.$v.'</li>';
		}else{
			$string .= '<li id="li_'.$k.'" value="'.$k.'">'.$v.'</li>';
		}
	}
	$string .= '</ul>';
	$string .= '</div></div>';
	return $string;
}

/**
 *
 * @param unknown $array	词语集合
 * @param unknown $id		值
 * @param unknown $inputName	input的name名
 * @param string $width		下拉框的宽度，如：'90'为'width:90px;'
 * @param string $unfoldWidth	下拉框展开的宽度，如：'90'为'width:90px;'
 * @param string $prefix	前缀，如：'学历'
 * @param string $press		内容中需要添加的一个值，如：不限
 * @param string $must		是否必填，不等于''或0为必填，否则相反
 * @param string $suffix	后缀
 * @return string
 */
function T_Q_box_sel($array = array(), $id,$inputName, $unfoldWidth='73',$prefix=null){
	$string   = '<div class="box"><div class="item">'.$prefix.'</div><div class="ipt_box"><select id="'.$inputName.'" name="'.$inputName.'" style="width:95px; height:23px; color:#4199D7;">';
	foreach($array as $k => $v){
		if($k == $id){
			$string .= '<option style="color:#4199D7;" selected="selected" value="'.$k.'">'.$v.'</option>';
		}else{
			$string .= '<option style="color:#4199D7;" value="'.$k.'">'.$v.'</option>';
		}
	}	
	$string .= '</select></div></div>';
	return $string;
}


/**
 *getConstellation 根据出生生日取得星座
 *
 *@param String $brithday 用于得到星座的日期 格式为yyyy-mm-dd
 *
 *@param Array $format 用于返回星座的名称
 *
 *@return String
 */
function getConstellation($birthday, $format=null)
{
    //$pattern = '/^d{4}-d{1,2}-d{1,2}$/';
    $pattern = '/^\d{4}-\d{1,2}-\d{1,2}$/';
    if (!preg_match($pattern, $birthday, $matchs)){
        return null;
    }
    $date = explode('-', $birthday);
    $year = $date[0];
    $month = intval($date[1]);
    $day   = intval($date[2]);
    if ($month <1 || $month>12 || $day < 1 || $day >31){
        return null;
    }
    //设定星座数组
    //1#白羊座|2#金牛座|3#双子座|4#巨蟹座|5#狮子座|6#处女座|7#天秤座|8#天蝎座|9#射手座|10#摩羯座|11#水瓶座|12#双鱼座
    $constellations = array(
        '摩羯座', '水瓶座', '双鱼座', '白羊座', '金牛座', '双子座',
        '巨蟹座','狮子座', '处女座', '天秤座', '天蝎座', '射手座');
    $constellations_list = array(
        '摩羯座'=>4, '水瓶座'=>11, '双鱼座'=>12, '白羊座'=>1, '金牛座'=>2, '双子座'=>3,
        '巨蟹座'=>10,'狮子座'=>5, '处女座'=>6, '天秤座'=>7, '天蝎座'=>8, '射手座'=>9);
    /*或 ‍‍$constellations = array(
          'Capricorn', 'Aquarius', 'Pisces', 'Aries', 'Taurus', 'Gemini',
          'Cancer','Leo', 'Virgo', 'Libra', 'Scorpio', 'Sagittarius',);
    */
    //设定星座结束日期的数组，用于判断
    $enddays = array(19, 18, 20, 20, 20, 21, 22, 22, 22, 22, 21, 21);
    //如果参数format被设置，则返回值采用format提供的数组，否则使用默认的数组
    if ($format != null){
        $values = $format;
    }else{
        $values = $constellations;
    }
    //根据月份和日期判断星座
    switch ($month)
    {
        case 1:
            if ($day <= $enddays[0]){
                $constellation = $values[0];
            }else{
                $constellation = $values[1];
            }
            break;
        case 2:
            if ($day <= $enddays[1]){
                $constellation = $values[1];
            }else{
                $constellation = $values[2];
            }
            break;
        case 3:
            if ($day <= $enddays[2]){
                $constellation = $values[2];
            }else{
                $constellation = $values[3];
            }
            break;
        case 4:
            if ($day <= $enddays[3]){
                $constellation = $values[3];
            }else{
                $constellation = $values[4];
            }
            break;
        case 5:
            if ($day <= $enddays[4]){
                $constellation = $values[4];
            }else{
                $constellation = $values[5];
            }
            break;
        case 6:
            if ($day <= $enddays[5]){
                $constellation = $values[5];
            }else{
                $constellation = $values[6];
            }
            break;
        case 7:
            if ($day <= $enddays[6]){
                $constellation = $values[6];
            }else{
                $constellation = $values[7];
            }
            break;
        case 8:
            if ($day <= $enddays[7]){
                $constellation = $values[7];
            }else{
                $constellation = $values[8];
            }
            break;
        case 9:
            if ($day <= $enddays[8]){
                $constellation = $values[8];
            }else{
                $constellation = $values[9];
            }
            break;
        case 10:
            if ($day <= $enddays[9]){
                $constellation = $values[9];
            }else{
                $constellation = $values[10];
            }
            break;
        case 11:
            if ($day <= $enddays[10]){
                $constellation = $values[10];
            }else{
                $constellation = $values[11];
            }
            break;
        case 12:
            if ($day <= $enddays[11]){
                $constellation = $values[11];
            }else{
                $constellation = $values[0];
            }
            break;
    }
    $return_str = '';
    if($constellation!=''){
        foreach($constellations_list as $key=>$val){
            if($constellation == $key){
                $return_str = $val;
            }
        }
    }
    //return $constellations_list;
    return $return_str;
}

/**
 * author Tony
 * 通过生日得到生肖
 * @param $birthday
 * @return string
 */
function birthday2BornTag($birthday){
    $year = substr($birthday,0,4);
    $bornTagarray = array("猴", "鸡", "狗", "猪", "鼠", "牛", "虎", "兔", "龙", "蛇","马", "羊");
    //1#鼠|2#牛|3#虎|4#兔|5#龙|6#蛇|7#马|8#羊|9#猴|10#鸡|11#狗|12#猪
    $bornTag_list = array(
        "猴"=>'9',"鸡"=>'10',"狗"=>'11',"猪"=>'12',"鼠"=>'1',"牛"=>'2',"虎"=>'3',"兔"=>'4',"龙"=>'5',"蛇"=>'6',"马"=>'7',"羊"=>'8');
    $index = $year%12;
    $bornTag = $bornTagarray[$index];
    $return_str = '';
    if($bornTag!='')
    {
        foreach($bornTag_list as $key=>$val)
        {
            if($bornTag == $key)
            {
                $return_str = $val;
            }
        }
    }
    //return $bornTag;
    return $return_str;
}


/**
 * author Tony
 * 2013-12-17
 * 生成验证字符串
 * @param string $confirm_str
 * @return string
 */
function make_confirm($confirm_str='')
{
    $str = date("YmdHis" ).str_pad( mt_rand( 1, 99999 ), 5, "0", STR_PAD_LEFT ).md5($confirm_str);
    $str = substr(md5($str),0,10);
    return $str;
}

/**
 *  使用特定function对数组中所有元素做处理
 *  @author Tony
 *  @param  string  &$array     要处理的字符串
 *  @param  string  $function   要执行的函数
 *  @return boolean $apply_to_keys_also     是否也应用到key上
 *  @access public
 *
 */
function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
	static $recursive_counter = 0;
	if (++$recursive_counter > 1000) {
		die('possible deep recursion attack');
	}
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			arrayRecursive($array[$key], $function, $apply_to_keys_also);
		} else {
			$array[$key] = $function($value);
		}

		if ($apply_to_keys_also && is_string($key)) {
			$new_key = $function($key);
			if ($new_key != $key) {
				$array[$new_key] = $array[$key];
				unset($array[$key]);
			}
		}
	}
	$recursive_counter--;
}

/**
 * 将数组转换为JSON字符串（兼容中文）
 * @author Tony
 * @param  array   $array      要转换的数组
 * @return string      转换得到的json字符串
 * @access public
 * @param unknown $array
 */
function toJson($array) {
	arrayRecursive($array, 'urlencode', true);
	$json = json_encode($array);
	return urldecode($json);
}

/**
 * 这个星期的星期一
 * @author Tony
 * @param number $timestamp	某个星期的某一个时间戳，默认为当前时间
 * @param string $is_return_timestamp	是否返回时间戳，否则返回时间格式
 * @return unknown
 */
function this_monday($timestamp=0,$is_return_timestamp=true){
	static $cache ;
	$id = $timestamp.$is_return_timestamp;
	if(!isset($cache[$id])){
		if(!$timestamp) $timestamp = time();
		$monday_date = date('Y-m-d', $timestamp-86400*date('w',$timestamp)+(date('w',$timestamp)>0?86400:-/*6*86400*/518400));
		if($is_return_timestamp){
			$cache[$id] = strtotime($monday_date);
		}else{
			$cache[$id] = $monday_date;
		}
	}
	return $cache[$id];
}

/**
 * 这个星期的星期天
 * @author Tony
 * @param number $timestamp	某个星期的某一个时间戳，默认为当前时间
 * @param string $is_return_timestamp	是否返回时间戳，否则返回时间格式
 * @return number
 */
function this_sunday($timestamp=0,$is_return_timestamp=true){
	static $cache ;
	$id = $timestamp.$is_return_timestamp;
	if(!isset($cache[$id])){
		if(!$timestamp) $timestamp = time();
		$sunday = this_monday($timestamp) + /*6*86400*/518400;
		if($is_return_timestamp){
			$cache[$id] = $sunday;
		}else{
			$cache[$id] = date('Y-m-d',$sunday);
		}
	}
	return $cache[$id];
}

/**
 * 上周一
 * @author Tony
 * @param number $timestamp	某个星期的某一个时间戳，默认为当前时间
 * @param string $is_return_timestamp	是否返回时间戳，否则返回时间格式
 * @return number
 */
function last_monday($timestamp=0,$is_return_timestamp=true){
	static $cache ;
	$id = $timestamp.$is_return_timestamp;
	if(!isset($cache[$id])){
		if(!$timestamp) $timestamp = time();
		$thismonday = this_monday($timestamp) - /*7*86400*/604800;
		if($is_return_timestamp){
			$cache[$id] = $thismonday;
		}else{
			$cache[$id] = date('Y-m-d',$thismonday);
		}
	}
	return $cache[$id];
}

/**
 * 上个星期天
 * @author Tony
 * @param number $timestamp	某个星期的某一个时间戳，默认为当前时间
 * @param string $is_return_timestamp	是否返回时间戳，否则返回时间格式
 * @return number
 */
function last_sunday($timestamp=0,$is_return_timestamp=true){
	static $cache ;
	$id = $timestamp.$is_return_timestamp;
	if(!isset($cache[$id])){
		if(!$timestamp) $timestamp = time();
		$thissunday = this_sunday($timestamp) - /*7*86400*/604800;
		if($is_return_timestamp){
			$cache[$id] = $thissunday;
		}else{
			$cache[$id] = date('Y-m-d',$thissunday);
		}
	}
	return $cache[$id];

}

/**
 * 这个月的第一天
 * @author Tony 
 * @param number $timestamp	某个星期的某一个时间戳，默认为当前时间
 * @param string $is_return_timestamp	是否返回时间戳，否则返回时间格式
 * @return unknown
 */
function month_firstday($timestamp = 0, $is_return_timestamp=true){
	static $cache ;
	$id = $timestamp.$is_return_timestamp;
	if(!isset($cache[$id])){
		if(!$timestamp) $timestamp = time();
		$firstday = date('Y-m-d', mktime(0,0,0,date('m',$timestamp),1,date('Y',$timestamp)));
		if($is_return_timestamp){
			$cache[$id] = strtotime($firstday);
		}else{
			$cache[$id] = $firstday;
		}
	}
	return $cache[$id];
}

//这个月的第一天
// @$timestamp ，某个月的某一个时间戳，默认为当前时间
// @is_return_timestamp ,是否返回时间戳，否则返回时间格式
/**
 * 这个月的最后一天
 * @author Tony
 * @param number $timestamp	某个星期的某一个时间戳，默认为当前时间
 * @param string $is_return_timestamp	是否返回时间戳，否则返回时间格式
 * @return unknown
 */
function month_lastday($timestamp = 0, $is_return_timestamp=true){
	static $cache ;
	$id = $timestamp.$is_return_timestamp;
	if(!isset($cache[$id])){
		if(!$timestamp) $timestamp = time();
		$lastday = date('Y-m-d', mktime(0,0,0,date('m',$timestamp),date('t',$timestamp),date('Y',$timestamp)));
		if($is_return_timestamp){
			$cache[$id] = strtotime($lastday);
		}else{
			$cache[$id] = $lastday;
		}
	}
	return $cache[$id];
}

/**
 * 上个月的第一天
 * @author Tony
 * @param number $timestamp
 * @param string $is_return_timestamp
 * @return unknown
 */
function lastmonth_firstday($timestamp = 0, $is_return_timestamp=true){
	static $cache ;
	$id = $timestamp.$is_return_timestamp;
	if(!isset($cache[$id])){
		if(!$timestamp) $timestamp = time();
		$firstday = date('Y-m-d', mktime(0,0,0,date('m',$timestamp)-1,1,date('Y',$timestamp)));
		if($is_return_timestamp){
			$cache[$id] = strtotime($firstday);
		}else{
			$cache[$id] = $firstday;
		}
	}
	return $cache[$id];
}

/**
 * 上个月的最后一天
 * @author Tony
 * @param number $timestamp
 * @param string $is_return_timestamp
 * @return unknown
 */
function lastmonth_lastday($timestamp = 0, $is_return_timestamp=true){
	static $cache ;
	$id = $timestamp.$is_return_timestamp;
	if(!isset($cache[$id])){
		if(!$timestamp) $timestamp = time();
		$lastday = date('Y-m-d', mktime(0,0,0,date('m',$timestamp)-1, date('t',lastmonth_firstday($timestamp)),date('Y',$timestamp)));
		if($is_return_timestamp){
			$cache[$id] = strtotime($lastday);
		}else{
			$cache[$id] =  $lastday;
		}
	}
	return $cache[$id];
}

/**
 * 时间输出类型
 * @author Tony
 * @param unknown $cur_time
 * @return string
 */
function time_ago($cur_time){
	$agoTime = time() - $cur_time;    
	if ( $agoTime <= 60 ) {        
		return $agoTime.'秒前';    
	}elseif( $agoTime <= 3600 && $agoTime > 60 ){        
		return intval($agoTime/60) .'分钟前';    
	}elseif ( date('d',$cur_time) == date('d',time()) && $agoTime > 3600){ 
		return '今天 '.date('H:i',$cur_time);    
	}elseif( date('d',$cur_time+86400) == date('d',time()) && $agoTime < 172800){ 
		return '昨天 '.date('H:i',$cur_time);    
	}else{        
		return date('Y年m月d日 H:i',$cur_time);    
	}
}

/*
 * 组合无限分类转一位数组为多维数组
 * @param array $cate需要处理的数组
 * @param  string $html 子类的html
 * @param int  $id  父类的id
 *
 */
function getMenuType($cate,$html='&nbsp>>',$id=0,$level=0,$dname=''){
    $arr=array();
    foreach ($cate as $v){
        if($v['parentid']==$id){
            $v['level']=$level+1;
            $v['html']=!empty($level) ? $html :'';
            $v['dname']=$dname.$v['html'].$v['name'];
            $arr[]=$v;
            $arr=array_merge($arr,getMenuType($cate,$html,$v['id'],$v['level'],$v['dname']));
        }
    }
    return $arr;
}

/**
 * 通过桌号自增id获得当前桌数据
 * @author Tony
 * @param unknown $tabId	桌号自增id
 */
function getTabInfoByTabId($tabId){
	$result = array();
	if($tabId){
		$From = D('tab');
		$result = $From->where('id='.$tabId)->field('id,num,floor_num')->find();
	}
	return $result;
}
/*
 * 给一个子类的pid找出所有顶级父类的id
 *  @param array $cate 分类的数组
 *  @param  int $id  子类的父类id
 */
function getParent ($cate,$id){
    $arr=array();
    foreach ($cate as $v){
        if($v['id']==$id){
            if($v['parentid']==0)
            {
                $arr[]=$v['id'];
               // break;
            }else{
                $arr=getParent($cate,$v['parentid']);
            }

        }

    }
    return $arr;
}

/**返回所有父类的集合如果没有父类返回自己
 * @param $cate array需要处理的数组
 * @param $id 子类的parnetid
 * @return array
 */
function getParents ($cate,$id){
    $arr=array();
    foreach ($cate as $v){
        if($v['id']==$id){
            $arr[]=$v['id'];
            $arr=array_merge(getParents($cate,$v['parentid']),$arr);
        }

    }
    $arr[]=$id;
    return $arr;
}

/**返回所有子类的集合没有子类返回自己
 * @param $cate
 * @param $id 父类的id
 * @return array
 */
function getChilds ($cate,$id){
    $arr=array();
    foreach ($cate as $v){
        if($v['parentid']>0){
            if($v['parentid']==$id){
                $arr[]=$v['id'];
                $arr=array_merge($arr,getChilds($cate,$v['id']));
            }
        }

    }
    $arr[]=$id;
    $arr= array_unique($arr);
    return $arr;
}
//三维数组转化为一位数组
function getOne($arr){
    $arrNew=array();
    foreach($arr as $v){
        foreach($v as $s){
            $arrNew[]=$s;
        }

    }
    return $arrNew;
}

//按数组里某个值对数组进行排序
/*
 * @param  $arr array 数组
 * ·param $fi
 */
function m_sort($arr,$field){
    $len=count($arr);
    for($i=0;$i<$len;$i++){
        $a=array();
        if($arr[$i][$field]<$arr[$i+1][$field]){
            $a=$arr[$i];
            $arr[$i]=$arr[$i+1];
            $arr[$i+1]=$a;
        }
    }
    return $arr;
}
function P($arr){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}


function duo($cate,$pid=0,$level=0){
    $arr=array();
    foreach($cate as $v){
        if($v['parentid']==$pid){
            $v['level']=$level+1;
            $v['child']=duo($cate,$v['id']);
            $arr[]=$v;

        }
    }
    return $arr;
}


/**
    * 导出数据为excel表格
    *@param $data    一个二维数组,结构如同从数据库查出来的数组
    *@param $title   excel的第一行标题,一个数组,如果为空则没有标题
    *@param $filename 下载的文件名
    *@examlpe 
    $stu = M ('User');
    $arr = $stu -> select();
    exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
*/
 function exportexcel($data=array(),$title=array(),$filename='report'){
    header("Content-type:application/octet-stream");
    header("Accept-Ranges:bytes");
    header("Content-type:application/vnd.ms-excel");
    header("Content-Disposition:attachment;filename=".$filename.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    //导出xls 开始
    if (!empty($title)){
        foreach ($title as $k => $v) {
            $title[$k]=iconv("UTF-8", "GB2312",$v);
        }
        $title= implode("\t", $title);
        echo "$title\n";
    }
    if (!empty($data)){
        foreach($data as $key=>$val){
            foreach ($val as $ck => $cv) {
		if ($ck =='createtime' || $ck =='updatetime')
			$data[$key][$ck] = toDate($data[$key][$ck]);
		else
                	$data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
            }
            $data[$key]=implode("\t", $data[$key]);


        }
        echo implode("\n",$data);

    }
 }



