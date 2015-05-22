<?php
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    $modules[$i]['code']    = basename(__FILE__, '.class.php');
    $modules[$i]['name']    = L('alipay', '', 'pay');   
    $modules[$i]['desc']    = L('alipay_tip', '', 'pay');
    $modules[$i]['is_cod']  = '0';
    $modules[$i]['is_online']  = '1';
    $modules[$i]['author']  = '铭讯开发团队';
    $modules[$i]['website'] = 'http://www.alipay.com';
    $modules[$i]['version'] = '1.0.0';
    $modules[$i]['config']  = array(
     	array('name' => 'alipay_account','type' => 'text','value' => ''),
        array('name' => 'alipay_key','type' => 'text','value' => ''),
        array('name' => 'alipay_partner','type' => 'text','value' => ''),
        array('name' => 'service_type','type' => 'select','value' => '0'),
    );

    return;
}

import('ORG.Pay.pay_abstract');

class Alipay extends paymentabstract{
	public $is_wap=0;
	public function __construct($config = array()) {	
		if (!empty($config))
        {
            $this->set_config($config);
        }
        //var_dump($this->config);
		
	    if ($this->config['service_type']==1)
        {
            $this->config['service'] = 'trade_create_by_buyer';
        }
		elseif($this->config['service_type']==2){
            $this->config['service'] = 'create_direct_pay_by_user';
        }
        elseif($this->config['service_type']==3){
            $this->config['service'] = 'calipay.wap.trade.create.direct';
        }
        else {
            $this->config['service'] = 'create_partner_trade_by_buyer';
        }
        //echo "<Br>----------------------";
		if($config['is_wap']==1)
		{
			$this->config['gateway_url'] = 'http://wappaygw.alipay.com/service/rest.htm?';
            $this->config['merchant_url'] = 'http://www.dishes.com';
            //$this->config['gateway_method'] = 'get';
            $this->config['gateway_method'] = 'POST';

		}
		else
		{
			$this->config['gateway_url'] = 'https://www.alipay.com/cooperate/gateway.do?_input_charset=utf-8';
            $this->config['gateway_method'] = 'POST';
		}
		//$this->config['gateway_url'] = 'https://mapi.alipay.com/gateway.do?_input_charset=utf-8';

		$this->config['notify_url'] = return_url('alipay',1);
		$this->config['return_url'] = return_url('alipay');

        //默认支付方式
        //$this->config['paymethod'] = "bankPay";
        include_once SYSTEM_PATH.APP_PATH.'Common/alipay.func.php';
		//pc_base::load_app_func('alipay');
	}

	public function getpreparedata() {
        if($this->config['paymethod']=='bankPay')
        {
            return $this->getpreparedata_bank();
        }
        if($this->config['paymethod']=='creditPay')
        {
            return $this->getpreparedata_credit();
        }
        if($this->config['is_wap']==1)
        {
            return $this->getpreparedata_wap();
        }
		$prepare_data['service'] = $this->config['service'];
        $prepare_data['partner'] = $this->config['alipay_partner'];
		$prepare_data['payment_type'] = '1';
        $prepare_data['notify_url'] = $this->config['notify_url'];
        $prepare_data['return_url'] = $this->config['return_url'];
		$prepare_data['seller_email'] = $this->config['alipay_account'];
        //订单信息
        $prepare_data['out_trade_no'] = $this->order_info['id'];
        $prepare_data['quantity'] = $this->order_info['quantity'];
		$prepare_data['_input_charset'] = 'utf-8';

		
		// 商品信息
		$prepare_data['subject'] = $this->product_info['name'];
		$prepare_data['price'] = $this->product_info['price'];
		if (array_key_exists('url', $this->product_info)) $prepare_data['show_url'] = $this->product_info['url'];
		$prepare_data['body'] = $this->product_info['body'];

       // $prepare_data["total_fee"]	= $this->product_info['price'];

		// 物流信息
		if($this->config['service'] == 'create_partner_trade_by_buyer' || $this->config['service'] == 'trade_create_by_buyer') {
			$prepare_data['logistics_type'] = 'EXPRESS';
			$prepare_data['logistics_fee'] = '0.00';
			$prepare_data['logistics_payment'] = 'SELLER_PAY';
		}
		//买家信息
		$prepare_data['buyer_email'] = $this->order_info['buyer_email'];
		
		$prepare_data = arg_sort($prepare_data);

		// 数字签名
		$prepare_data['sign'] = build_mysign($prepare_data,$this->config['alipay_key'],'MD5');
		$prepare_data['sign_type'] = 'MD5';


		return $prepare_data;
	}


    public function  getpreparedata_credit()
    {
        $prepare_data["service"] = "create_direct_pay_by_user";
        $prepare_data["partner"] = $this->config['alipay_partner'];
        $prepare_data["payment_type"]	= 1;
        $prepare_data["notify_url"]	= $this->config['notify_url'];
        $prepare_data["return_url"]	= $this->config['return_url'];
        $prepare_data["seller_email"]	= $this->config['alipay_account'];
        $prepare_data["out_trade_no"]	= $this->order_info['id'];
        $prepare_data["subject"]	= $this->product_info['name'];
        $prepare_data["total_fee"]	= $this->product_info['price'];
        //$prepare_data["price"]	= $this->product_info['price'];
        $prepare_data["body"]	= $this->product_info['body'];
        $prepare_data["paymethod"]	= 'creditPay';
        $prepare_data["defaultbank"]	= $this->config['defaultbank'];

        //$prepare_data["show_url"]	= '';
        // //防钓鱼时间戳 若要使用请调用类文件submit中的query_timestamp函数
        //$prepare_data["anti_phishing_key"]	= '';
        //$prepare_data["exter_invoke_ip"]	= get_client_ip();
        $prepare_data["_input_charset"]	=  'utf-8';
        $prepare_data['quantity'] = $this->order_info['quantity'];
        //买家信息
        //$prepare_data['buyer_email'] = $this->order_info['buyer_email'];
        $prepare_data = arg_sort($prepare_data);
        // 数字签名
        $prepare_data['sign'] = build_mysign($prepare_data,$this->config['alipay_key'],'MD5');
        $prepare_data['sign_type'] = 'MD5';
        return $prepare_data;
    }


    public function  getpreparedata_bank()
    {
        $prepare_data["service"] = "create_direct_pay_by_user";
		$prepare_data["partner"] = $this->config['alipay_partner'];
		$prepare_data["payment_type"]	= 1;
		$prepare_data["notify_url"]	= $this->config['notify_url'];
		$prepare_data["return_url"]	= $this->config['return_url'];
		$prepare_data["seller_email"]	= $this->config['alipay_account'];
		$prepare_data["out_trade_no"]	= $this->order_info['id'];
		$prepare_data["subject"]	= $this->product_info['name'];
		$prepare_data["total_fee"]	= $this->product_info['price'];
		//$prepare_data["price"]	= $this->product_info['price'];
		$prepare_data["body"]	= $this->product_info['body'];
		$prepare_data["paymethod"]	= 'bankPay';
		$prepare_data["defaultbank"]	= $this->config['defaultbank'];

		//$prepare_data["show_url"]	= '';
        // //防钓鱼时间戳 若要使用请调用类文件submit中的query_timestamp函数
		//$prepare_data["anti_phishing_key"]	= '';
		//$prepare_data["exter_invoke_ip"]	= get_client_ip();
		$prepare_data["_input_charset"]	=  'utf-8';
        $prepare_data['quantity'] = $this->order_info['quantity'];
        //买家信息
        //$prepare_data['buyer_email'] = $this->order_info['buyer_email'];
        $prepare_data = arg_sort($prepare_data);
        // 数字签名
        $prepare_data['sign'] = build_mysign($prepare_data,$this->config['alipay_key'],'MD5');
        $prepare_data['sign_type'] = 'MD5';
        return $prepare_data;
    }

    /**
     * author Tony
     * 2014-01-20
     * 处理wap
     * @return mixed
     */
    public function  getpreparedata_wap()
    {
        //echo '<Br>-------------------------------';
        $prepare_data["service"] = "alipay.wap.auth.authAndExecute";
        $prepare_data["partner"] = $this->config['alipay_partner'];

        //$prepare_data["sec_id"] = trim($alipay_config['sign_type']);
        $prepare_data["sec_id"] = strtoupper('MD5');
		$prepare_data["format"]	= 'xml';
		$prepare_data["v"]	= '2.0';
		$prepare_data["req_id"]	= $this->order_info['id'];
        $req_data = '<direct_trade_create_req><notify_url>' . $this->config['notify_url'] .
                    '</notify_url><call_back_url>' . $this->config['return_url'] . '</call_back_url><seller_account_name>' . $this->config['alipay_account'] .
                    '</seller_account_name><out_trade_no>' .$this->order_info['id'] . '</out_trade_no><subject>' . $this->product_info['name'] .
                    '</subject><total_fee>' . $this->product_info['price'] . '</total_fee><merchant_url>' . $this->config['merchant_url'] .
                    '</merchant_url></direct_trade_create_req>';
		$prepare_data["req_data"]	= $req_data;


       // $prepare_data["_input_charset"]	=  'utf-8';
        //var_dump($prepare_data);

        //买家信息
        //$prepare_data['buyer_email'] = $this->order_info['buyer_email'];
        //$prepare_data = arg_sort($prepare_data);
        // 数字签名

        $prepare_data['sign'] = build_mysign($prepare_data,$this->config['alipay_key'],'MD5');
        $prepare_data['sign_type'] = 'MD5';
        return $prepare_data;
    }
// -----------------------------------------------------------------------------------------------------------------------------


    //----------------------------```````````````````````````````````````-------------------------------


    /**
	 * GET接收数据
	 * 状态码说明  （0 交易完成 1 交易失败 2 交易超时 3 交易处理中 4 交易未支付5交易取消6交易发生错误）
	 */
    public function receive() {
    	$receive_sign = $_GET['sign'];
    	$receive_data = $this->filterParameter($_GET);
    	$receive_data = arg_sort($receive_data);
    	if ($receive_data) {
			$verify_result = $this->get_verify('http://notify.alipay.com/trade/notify_query.do?partner=' . $this->config['alipay_partner'] . '&notify_id=' . $receive_data['notify_id']);
			if (preg_match('/true$/i', $verify_result))
			{
				$sign = '';
				$sign = build_mysign($receive_data,$this->config['alipay_key'],'MD5');				
				if ($sign != $receive_sign)
				{
					error_log(date('m-d H:i:s',time()).'| GET: signature is bad |'."\r\n", 3, CACHE_PATH.'pay_error_log.php');
					echo '签名错误';
					//showmessage(L('illegal_sign'));
					return false;
				}
				else
				{
					$return_data['order_id'] = $receive_data['out_trade_no'];
					$return_data['order_total'] = $receive_data['total_fee'];
					$return_data['price'] = $receive_data['price'];
					switch ($receive_data['trade_status'])
					{
						case 'WAIT_BUYER_PAY': $return_data['order_status'] = 3; break;
						case 'WAIT_SELLER_SEND_GOODS': $return_data['order_status'] = 3; break;
						case 'WAIT_BUYER_CONFIRM_GOODS': $return_data['order_status'] = 3; break;
						case 'TRADE_CLOSED': $return_data['order_status'] = 5; break;						
						case 'TRADE_FINISHED': $return_data['order_status'] = 0; break;
						case 'TRADE_SUCCESS': $return_data['order_status'] = 0; break;
						default:
							 $return_data['order_status'] = 5;						
					}			
					return $return_data;
				}

			}
			else
			{
				error_log(date('m-d H:i:s',time()).'| GET: illegality notice : flase |'."\r\n", 3, CACHE_PATH.'pay_error_log.php');
				echo '通知错误';
				//showmessage(L('通知错误'));
				return false;
			}
		} else {
			
			error_log(date('m-d H:i:s',time()).'| GET: no return |'."\r\n", 3, CACHE_PATH.'pay_error_log.php');
			echo '信息返回错误';
			//showmessage(L('信息返回错误'));
			return false;
		}   	
    }	

    /**
	 * POST接收数据
	 * 状态码说明  （0 交易完成 1 交易失败 2 交易超时 3 交易处理中 4 交易未支付 5交易取消6交易发生错误）
	 */
    public function notify() {
    	$receive_sign = $_POST['sign'];
    	$receive_data = $this->filterParameter($_POST);
    	$receive_data = arg_sort($receive_data);
    	if ($receive_data) {
			$verify_result = $this->get_verify('http://notify.alipay.com/trade/notify_query.do?service=notify_verify&partner=' . $this->config['alipay_partner'] . '&notify_id=' . $receive_data['notify_id']);
			if (preg_match('/true$/i', $verify_result))
			{
				$sign = '';
				$sign = build_mysign($receive_data,$this->config['alipay_key'],'MD5');				
				if ($sign != $receive_sign)
				{
					error_log(date('m-d H:i:s',time()).'| POST: signature is bad |'."\r\n", 3, CACHE_PATH.'pay_error_log.php');
					return false;
				}
				else
				{
					$return_data['order_id'] = $receive_data['out_trade_no'];
					$return_data['order_total'] = $receive_data['total_fee'];
					$return_data['price'] = $receive_data['price'];
					switch ($receive_data['trade_status']) {
						case 'WAIT_BUYER_PAY': $return_data['order_status'] = 3; break;
						case 'WAIT_SELLER_SEND_GOODS': $return_data['order_status'] = 3; break;
						case 'WAIT_BUYER_CONFIRM_GOODS': $return_data['order_status'] = 3; break;
						case 'TRADE_CLOSED': $return_data['order_status'] = 5; break;						
						case 'TRADE_FINISHED': $return_data['order_status'] = 0; break;
						case 'TRADE_SUCCESS': $return_data['order_status'] = 0; break;
						default:
							 $return_data['order_status'] = 5;
					}
					return $return_data;
				}

			}
			else
			{
				error_log(date('m-d H:i:s',time()).'|  POST: illegality notice : flase |'."\r\n", 3, CACHE_PATH.'pay_error_log.php');
				return false;
			}
		} else {
			
			error_log(date('m-d H:i:s',time()).'|  POST: no post return |'."\r\n", 3, CACHE_PATH.'pay_error_log.php');
			return false;
		}   	
    }
    	
    /**
     * 相应服务器应答状态
     * @param $result
     */
    public function response($result) {
    	if (FALSE == $result) echo 'fail';
		else echo 'success';
    }
    
    /**
     * 返回字符过滤
     * @param $parameter
     */
	private function filterParameter($parameter)
	{
		$para = array();
		foreach ($parameter as $key => $value)
		{
			if ('sign' == $key || 'sign_type' == $key || '' == $value || 'm' == $key  || 'a' == $key  || 'c' == $key   || 'code' == $key ) continue;
			else $para[$key] = $value;
		}
		return $para;
	}
}
?>