<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_app_class('wechat_paybank', '', 0);
pc_base::load_app_class('wechat_paymentchange', '', 0);

class ali {
	function __construct() {


	}


//=======================================支付宝支付 DEMO====================================

	/**
	* 例子页面_首页
	*/
	public function demo() 
	{

		include template('zypay', 'ali_demo');
	}

	/**
	* 例子页面_网页支付
	*/
	public function page() 
	{

		include template('zypay', 'ali_page');
	}

	/**
	* 例子页面_H5支付
	*/
	public function wap() 
	{


		include template('zypay', 'ali_wap');
	}

	/**
	* 例子页面_刷卡支付
	*/
	public function f2f() 
	{


		include template('zypay', 'ali_f2f');
	}



	/**
	* 例子页面_网页支付-同步回调
	*/
	public function return_url() 
	{
		/* *
		 * 功能：支付宝页面跳转同步通知页面
		 * 版本：2.0
		 * 修改日期：2017-05-01
		 * 说明：
		 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

		 *************************页面功能说明*************************
		 * 该页面可在本机电脑测试
		 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
		 */
//		require_once 'classes/alipay/page/config.php';
//		require_once 'classes/alipay/page/pagepay/service/AlipayTradeService.php';
        require_once 'classes/alipay/wap/wappay/service/AlipayTradeService.php';
        require_once 'classes/alipay/wap/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';
        require_once 'classes/alipay/wap/config.php';

		$arr=$_GET;
//        var_dump($_GET);
        unset($arr["m"]);
        unset($arr["c"]);
        unset($arr["a"]);
//        unset($arr["XDEBUG_SESSION_START"]);
		$alipaySevice = new AlipayTradeService($config);
		$result = $alipaySevice->check($arr);

		/* 实际验证过程建议商户添加以下校验。
		1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
		2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
		3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
		4、验证app_id是否为该商户本身。
		*/
		//var_dump($result);
	
		if($result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代码
			
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
		    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

			//商户订单号
			//支付宝交易号
			//echo "验证成功<br />支付宝交易号：".$trade_no;
            include template('zyorder', 'order_list');
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else {
		    //验证失败
            include template('zyorder', 'order_list');
		}
	}

	/**
	* 例子页面_网页支付-异步回调
	*/
	public function notify_url() 
	{

		/* *
		 * 功能：支付宝服务器异步通知页面
		 * 版本：2.0
		 * 修改日期：2017-05-01
		 * 说明：
		 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

		 *************************页面功能说明*************************
		 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
		 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
		 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
		 */

        require_once 'classes/alipay/wap/wappay/service/AlipayTradeService.php';
        require_once 'classes/alipay/wap/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';
        require_once 'classes/alipay/wap/config.php';


		$arr=$_POST;
		//echo $_POST;
        unset($arr["m"]);
        unset($arr["c"]);
        unset($arr["a"]);
//        unset($arr["XDEBUG_SESSION_START"]);
		$alipaySevice = new AlipayTradeService($config);
        $arr['fund_bill_list'] = str_replace('\\','',$arr['fund_bill_list']);
        $alipaySevice->writeLog(var_export($_POST,true));
		$result = $alipaySevice->check($arr);

		/* 实际验证过程建议商户添加以下校验。
		1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
		2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
		3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
		4、验证app_id是否为该商户本身。
		*/
		if($result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代

			
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			
		    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			
			//商户订单号

			$out_trade_no = $_POST['out_trade_no'];

			//支付宝交易号

			$trade_no = $_POST['trade_no'];

			//交易状态
			$trade_status = $_POST['trade_status'];


		    if($_POST['trade_status'] == 'TRADE_FINISHED') {

				//判断该笔订单是否在商户网站中已经做过处理
					//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
					//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
					//如果有做过处理，不执行商户的业务程序
						
				//注意：
				//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
		    }
		    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                    $order_db = pc_base::load_model('zy_order_model');
                    $ordergoods_db = pc_base::load_model('zy_order_goods_model');
                    $goods_specs_db = pc_base::load_model('goods_specs_model');
                    $goods_db = pc_base::load_model('goods_model');
                    $info = $order_db->get_one(array("ordersn"=>$out_trade_no));
                    $goodsInfo = $ordergoods_db->select(array("order_id"=>$info["order_id"]), "id,goods_id, is_count,is_xlCount,  specid, goods_num");
                    foreach($goodsInfo as $k=>$v)
                    {
                        $ordergoods_db->update(array("is_xlCount"=>1), array("id"=>$v["id"]));
                        if($v["specid"] && $v["is_xlCount"] == 0)
                            $specidGoods[] = $v;
                        elseif($v["is_xlCount"] == 0)
                            $notSpecidGoods[] = $v;
                    }
                    if(isset($specidGoods))
                    {
                        foreach($specidGoods as $k=>$v)
                        {
                            $goods_specs_db->update(array("salenum"=>"+=".$v["goods_num"]), array("goodsid"=>$v["goods_id"], "specid"=>$v["specid"]));
                            $goods_db->update(array("salesnum"=>"+=".$v["goods_num"]), array("id"=>$v["goods_id"]));
                        }
                    }
                    if(isset($notSpecidGoods))
                    {
                        foreach($notSpecidGoods as $k=>$v)
                        {
                            $goods_db->update(array("salesnum"=>"+=".$v["goods_num"]), array("id"=>$v["goods_id"]));
                        }
                    }

                    $data=[
                        'userid'=>$info["userid"],
                        'oid'=>$info["order_id"],
                    ];

                    //更新积分 分销佣金
                    $url = APP_PATH."index.php?m=zypoints&c=api&a=api_update_points";
                    $return = json_decode(_crul_post($url,$data),true);

                    if($info["try_status"] == 0)
                        $order_db->update(array("pay_type"=>1,"aliTradeNo"=>$trade_no, "status"=>'2'), array("ordersn"=>$out_trade_no));
                    else
                        $order_db->update(array("pay_type"=>1,"aliTradeNo"=>$trade_no, "status"=>'4'), array("ordersn"=>$out_trade_no));

		    }
			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            //write_log('支付宝支付异步回调：'.json_encode($_POST));
            //echo "success";	//请不要修改或删除
		}else {
		    //验证失败
		    echo "fail";

		}

	}



//==============支付宝支付-电脑网页 DEMO==============


	/**
	* 例子页面_网页支付-支付
	*/
	public function page_pay() 
	{
		require_once 'classes/alipay/page/config.php';
		require_once 'classes/alipay/page/pagepay/service/AlipayTradeService.php';
		require_once 'classes/alipay/page/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';

		if(!trim($_POST['WIDout_trade_no']) || !trim($_POST['WIDsubject']) || !trim($_POST['WIDtotal_amount'])){

			exit('error');
		}



		    //商户订单号，商户网站订单系统中唯一订单号，必填
		    $out_trade_no = trim($_POST['WIDout_trade_no']);

		    //订单名称，必填
		    $subject = trim($_POST['WIDsubject']);

		    //付款金额，必填
		    $total_amount = trim($_POST['WIDtotal_amount']);

		    //商品描述，可空
		    $body = trim($_POST['WIDbody']);

			//构造参数
			$payRequestBuilder = new AlipayTradePagePayContentBuilder();
			$payRequestBuilder->setBody($body);
			$payRequestBuilder->setSubject($subject);
			$payRequestBuilder->setTotalAmount($total_amount);
			$payRequestBuilder->setOutTradeNo($out_trade_no);
            var_dump($config);
			$aop = new AlipayTradeService($config);

			/**
			 * pagePay 电脑网站支付请求
			 * @param $builder 业务参数，使用buildmodel中的对象生成。
			 * @param $return_url 同步跳转地址，公网可以访问
			 * @param $notify_url 异步通知地址，公网可以访问
			 * @return $response 支付宝返回的信息
		 	*/
			$response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);

			//输出表单
			var_dump(1111);

	}

	/**
	* 例子页面_网页支付-交易查询
	*/
	public function page_query() 
	{
		
		require_once 'classes/alipay/page/config.php';
		require_once 'classes/alipay/page/pagepay/service/AlipayTradeService.php';
		require_once 'classes/alipay/page/pagepay/buildermodel/AlipayTradeQueryContentBuilder.php';

		if(!trim($_POST['WIDTQout_trade_no']) || !trim($_POST['WIDTQtrade_no']) ){


		    //商户订单号，商户网站订单系统中唯一订单号
		    $out_trade_no = trim($_POST['WIDTQout_trade_no']);

		    //支付宝交易号
		    $trade_no = trim($_POST['WIDTQtrade_no']);
		    //请二选一设置
		    //构造参数
			$RequestBuilder = new AlipayTradeQueryContentBuilder();
			$RequestBuilder->setOutTradeNo($out_trade_no);
			$RequestBuilder->setTradeNo($trade_no);

			$aop = new AlipayTradeService($config);
			
			/**
			 * alipay.trade.query (统一收单线下交易查询)
			 * @param $builder 业务参数，使用buildmodel中的对象生成。
			 * @return $response 支付宝返回的信息
		 	 */
			$response = $aop->Query($RequestBuilder);
			var_dump($response);
			exit('error');
		}

	}

	/**
	* 例子页面_网页支付-退款
	*/
	public function page_refund() 
	{
		require_once 'classes/alipay/page/config.php';
		require_once 'classes/alipay/page/pagepay/service/AlipayTradeService.php';
		require_once 'classes/alipay/page/pagepay/buildermodel/AlipayTradeRefundContentBuilder.php';

		if(!trim($_POST['WIDTRout_trade_no']) ){

			exit('error');
		}

		    //商户订单号，商户网站订单系统中唯一订单号
		    $out_trade_no = trim($_POST['WIDTRout_trade_no']);

		    //支付宝交易号
		    $trade_no = trim($_POST['WIDTRtrade_no']);
		    //请二选一设置

		    //需要退款的金额，该金额不能大于订单金额，必填
		    $refund_amount = trim($_POST['WIDTRrefund_amount']);

		    //退款的原因说明
		    $refund_reason = trim($_POST['WIDTRrefund_reason']);

		    //标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传
		    $out_request_no = trim($_POST['WIDTRout_request_no']);

		    //构造参数
			$RequestBuilder=new AlipayTradeRefundContentBuilder();
			$RequestBuilder->setOutTradeNo($out_trade_no);
			$RequestBuilder->setTradeNo($trade_no);
			$RequestBuilder->setRefundAmount($refund_amount);
			$RequestBuilder->setOutRequestNo($out_request_no);
			$RequestBuilder->setRefundReason($refund_reason);

			$aop = new AlipayTradeService($config);
			
			/**
			 * alipay.trade.refund (统一收单交易退款接口)
			 * @param $builder 业务参数，使用buildmodel中的对象生成。
			 * @return $response 支付宝返回的信息
			 */
			$response = $aop->Refund($RequestBuilder);
			var_dump($response);;
	}

	/**
	* 例子页面_网页支付-退款查询
	*/
	public function page_refundquery() 
	{
		require_once 'classes/alipay/page/config.php';
		require_once 'classes/alipay/page/pagepay/service/AlipayTradeService.php';
		require_once 'classes/alipay/page/pagepay/buildermodel/AlipayTradeFastpayRefundQueryContentBuilder.php';

		    //商户订单号，商户网站订单系统中唯一订单号
		    $out_trade_no = trim($_POST['WIDRQout_trade_no']);

		    //支付宝交易号
		    $trade_no = trim($_POST['WIDRQtrade_no']);
		    //请二选一设置

		    //请求退款接口时，传入的退款请求号，如果在退款请求时未传入，则该值为创建交易时的外部交易号，必填
		    $out_request_no = trim($_POST['WIDRQout_request_no']);

		    //构造参数
			$RequestBuilder=new AlipayTradeFastpayRefundQueryContentBuilder();
			$RequestBuilder->setOutTradeNo($out_trade_no);
			$RequestBuilder->setTradeNo($trade_no);
			$RequestBuilder->setOutRequestNo($out_request_no);

			$aop = new AlipayTradeService($config);
			
			/**
			 * 退款查询   alipay.trade.fastpay.refund.query (统一收单交易退款查询)
			 * @param $builder 业务参数，使用buildmodel中的对象生成。
			 * @return $response 支付宝返回的信息
			 */
			$response = $aop->refundQuery($RequestBuilder);
			var_dump($response);
	}

	/**
	* 例子页面_网页支付-交易关闭
	*/
	public function page_close() 
	{
		require_once 'classes/alipay/page/config.php';
		require_once 'classes/alipay/page/pagepay/service/AlipayTradeService.php';
		require_once 'classes/alipay/page/pagepay/buildermodel/AlipayTradeCloseContentBuilder.php';

		if(trim($_POST['WIDTCout_trade_no']) || trim($_POST['WIDTCtrade_no']) ){



		    //商户订单号，商户网站订单系统中唯一订单号
		    $out_trade_no = trim($_POST['WIDTCout_trade_no']);

		    //支付宝交易号
		    $trade_no = trim($_POST['WIDTCtrade_no']);
		    //请二选一设置

			//构造参数
			$RequestBuilder=new AlipayTradeCloseContentBuilder();
			$RequestBuilder->setOutTradeNo($out_trade_no);
			$RequestBuilder->setTradeNo($trade_no);

			$aop = new AlipayTradeService($config);

			/**
			 * alipay.trade.close (统一收单交易关闭接口)
			 * @param $builder 业务参数，使用buildmodel中的对象生成。
			 * @return $response 支付宝返回的信息
			 */
			$response = $aop->Close($RequestBuilder);
			var_dump($response);

		}

	}


//==============支付宝支付-电脑网页 DEMO==============



//==============支付宝支付-手机网页 DEMO==============

	/**
	* 例子页面_手机网页-手机网站支付
	*/
	public function wap_pay(){

		if($_POST){

			require_once 'classes/alipay/wap/wappay/service/AlipayTradeService.php';
			require_once 'classes/alipay/wap/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';
			require_once 'classes/alipay/wap/config.php';
			if(empty($_POST["sn"]) || empty($_POST["orderid"]))
                returnAjaxData("-1", "参数错误");
			$sn = $_POST["sn"];
            $orderid = $_POST["orderid"];
//            $orderid = "233";
//            $sn = "1560395643";
            $order_db = pc_base::load_model('zy_order_model');
            $info = $order_db->get_one(array("ordersn"=>$sn, "order_id"=>$orderid));
            if(empty($info))
                returnAjaxData("-1","没有该订单信息");

            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = $info["ordersn"];

            //订单名称，必填
            $subject = "惠集信购商品";

            //付款金额，必填
//            $total_amount = 0.01;
            $total_amount = $info["totalprice"];

            //商品描述，可空
           $body = "惠集信购商品";

            //超时时间
            $timeout_express="1m";

            $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setOutTradeNo($out_trade_no);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setTimeExpress($timeout_express);
            $s = $config;
            $payResponse = new AlipayTradeService($config);
            $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);
            return;


		}

	}

	/**
	* 例子页面_手机网页-订单查询
	*/
	public function wap_query(){

		if ($_POST) {
			
			require_once 'classes/alipay/wap/wappay/service/AlipayTradeService.php';
			require_once 'classes/alipay/wap/wappay/buildermodel/AlipayTradeQueryContentBuilder.php';
			require_once 'classes/alipay/wap/config.php';
			if (!empty($_POST['WIDout_trade_no']) || !empty($_POST['WIDtrade_no'])){

			    //商户订单号和支付宝交易号不能同时为空。 trade_no、  out_trade_no如果同时存在优先取trade_no
			    //商户订单号，和支付宝交易号二选一
			    $out_trade_no = trim($_POST['WIDout_trade_no']);

			    //支付宝交易号，和商户订单号二选一
			    $trade_no = trim($_POST['WIDtrade_no']);

			    $RequestBuilder = new AlipayTradeQueryContentBuilder();
			    $RequestBuilder->setTradeNo($trade_no);
			    $RequestBuilder->setOutTradeNo($out_trade_no);

			    $Response = new AlipayTradeService($config);
			    $result=$Response->Query($RequestBuilder);
			    return ;
			}

		}

		include template('zypay', 'wap_query');		
	}

	/**
	* 例子页面_手机网页-订单退款
	*/
	public function wap_refund(){

		if ($_POST) {

			require_once 'classes/alipay/wap/wappay/service/AlipayTradeService.php';
			require_once 'classes/alipay/wap/wappay/buildermodel/AlipayTradeRefundContentBuilder.php';
			require_once 'classes/alipay/wap/config.php';
			if (!empty($_POST['WIDout_trade_no']) || !empty($_POST['WIDtrade_no'])){

//			    exit('1');
			    //商户订单号和支付宝交易号不能同时为空。 trade_no、  out_trade_no如果同时存在优先取trade_no
			    //商户订单号，和支付宝交易号二选一
			    $out_trade_no = trim($_POST['WIDout_trade_no']);

			    //支付宝交易号，和商户订单号二选一
			    $trade_no = trim($_POST['WIDtrade_no']);

			    //退款金额，不能大于订单总金额
			    $refund_amount=trim($_POST['WIDrefund_amount']);

			    //退款的原因说明
			    $refund_reason=trim($_POST['WIDrefund_reason']);

			    //标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传。
			    $out_request_no=trim($_POST['WIDout_request_no']);

			    $RequestBuilder = new AlipayTradeRefundContentBuilder();
			    $RequestBuilder->setTradeNo($trade_no);
			    $RequestBuilder->setOutTradeNo($out_trade_no);
			    $RequestBuilder->setRefundAmount($refund_amount);
			    $RequestBuilder->setRefundReason($refund_reason);
			    $RequestBuilder->setOutRequestNo($out_request_no);
                unset($config["notify_url"]);
			    $Response = new AlipayTradeService($config);
			    $result=$Response->Refund($RequestBuilder);
			    return ;
			}
		}

		//include template('zypay', 'wap_refund');
	}

	/**
	* 例子页面_手机网页-订单退款查询
	*/
	public function wap_refundquery(){

		if($_POST){
			require_once 'classes/alipay/wap/wappay/service/AlipayTradeService.php';
			require_once 'classes/alipay/wap/wappay/buildermodel/AlipayTradeFastpayRefundQueryContentBuilder.php';
			require_once 'classes/alipay/wap/config.php';
			if (!empty($_POST['WIDout_trade_no']) || !empty($_POST['WIDtrade_no'])&&!empty($_POST['WIDout_request_no'])){

			    //商户订单号和支付宝交易号不能同时为空。 trade_no、  out_trade_no如果同时存在优先取trade_no
			    //商户订单号，和支付宝交易号二选一
			    $out_trade_no = trim($_POST['WIDout_trade_no']);
			    //支付宝交易号，和商户订单号二选一
			    $trade_no = trim($_POST['WIDtrade_no']);
			    //请求退款接口时，传入的退款请求号，如果在退款请求时未传入，则该值为创建交易时的外部交易号
			    $out_request_no = trim($_POST['WIDout_request_no']);

			    $RequestBuilder = new AlipayTradeFastpayRefundQueryContentBuilder();
			    $RequestBuilder->setTradeNo($trade_no);
			    $RequestBuilder->setOutTradeNo($out_trade_no);
			    $RequestBuilder->setOutRequestNo($out_request_no);

			    $Response = new AlipayTradeService($config);
			    $result=$Response->refundQuery($RequestBuilder);
			    return ;
			}
			
		}

		include template('zypay', 'wap_refundquery');		
	}

	/**
	* 例子页面_手机网页-账单查询
	*/
	public function wap_datadownioad(){

		if($_POST){
			require_once 'classes/alipay/wap/wappay/service/AlipayTradeService.php';
			require_once 'classes/alipay/wap/wappay/buildermodel/AlipayDataDataserviceBillDownloadurlQueryContentBuilder.php';
			require_once 'classes/alipay/wap/config.php';
			if (!empty($_POST['WIDbill_type']) && !empty($_POST['WIDbill_date'])){
				//账单类型，商户通过接口或商户经开放平台授权后其所属服务商通过接口可以获取以下账单类型：trade、signcustomer；
				//trade指商户基于支付宝交易收单的业务账单；signcustomer是指基于商户支付宝余额收入及支出等资金变动的帐务账单；
			    $bill_type = trim($_POST['WIDbill_type']);
			    //账单时间：日账单格式为yyyy-MM-dd，月账单格式为yyyy-MM。
			    $bill_date = trim($_POST['WIDbill_date']);
			    
			    $RequestBuilder = new AlipayDataDataserviceBillDownloadurlQueryContentBuilder();
			    $RequestBuilder->setBillType($bill_type);
			    $RequestBuilder->setBillDate($bill_date);
			    $Response = new AlipayTradeService($config);
			    $result=$Response->downloadurlQuery($RequestBuilder);
			    return ;
			}
		}

		include template('zypay', 'wap_datadownioad');		
	}



//==============支付宝支付-手机网页 DEMO==============




//==============支付宝支付-刷卡支付 DEMO==============

	/**
	* 例子页面_刷卡支付-条码支付
	*/
	public function f2f_barpay(){
		if($_POST){

			require_once 'classes/alipay/f2f/f2fpay/model/builder/AlipayTradePayContentBuilder.php';
			require_once 'classes/alipay/f2f/f2fpay/service/AlipayTradeService.php';

			if (!empty($_POST['out_trade_no'])&& trim($_POST['out_trade_no'])!="") {
			    // (必填) 商户网站订单系统中唯一订单号，64个字符以内，只能包含字母、数字、下划线，
			    // 需保证商户系统端不能重复，建议通过数据库sequence生成，
			    //$outTradeNo = "barpay" . date('Ymdhis') . mt_rand(100, 1000);
			    $outTradeNo = $_POST['out_trade_no'];

			    // (必填) 订单标题，粗略描述用户的支付目的。如“XX品牌XXX门店消费”
			    $subject = $_POST['subject'];

			    // (必填) 订单总金额，单位为元，不能超过1亿元
			    // 如果同时传入了【打折金额】,【不可打折金额】,【订单总金额】三者,则必须满足如下条件:【订单总金额】=【打折金额】+【不可打折金额】
			    $totalAmount = $_POST['total_amount'];

			    // (必填) 付款条码，用户支付宝钱包手机app点击“付款”产生的付款条码
			    $authCode = $_POST['auth_code']; //28开头18位数字

			    // (可选,根据需要使用) 订单可打折金额，可以配合商家平台配置折扣活动，如果订单部分商品参与打折，可以将部分商品总价填写至此字段，默认全部商品可打折
			    // 如果该值未传入,但传入了【订单总金额】,【不可打折金额】 则该值默认为【订单总金额】- 【不可打折金额】
			    //String discountableAmount = "1.00"; //

			    // (可选) 订单不可打折金额，可以配合商家平台配置折扣活动，如果酒水不参与打折，则将对应金额填写至此字段
			    // 如果该值未传入,但传入了【订单总金额】,【打折金额】,则该值默认为【订单总金额】-【打折金额】
			    $undiscountableAmount = "0.01";

			    // 卖家支付宝账号ID，用于支持一个签约账号下支持打款到不同的收款账号，(打款到sellerId对应的支付宝账号)
			    // 如果该字段为空，则默认为与支付宝签约的商户的PID，也就是appid对应的PID
			    $sellerId = "";

			    // 订单描述，可以对交易或商品进行一个详细地描述，比如填写"购买商品2件共15.00元"
			    $body = "购买商品2件共15.00元";

			    //商户操作员编号，添加此参数可以为商户操作员做销售统计
			    $operatorId = "test_operator_id";

			    // (可选) 商户门店编号，通过门店号和商家后台可以配置精准到门店的折扣信息，详询支付宝技术支持
			    $storeId = "test_store_id";

			    // 支付宝的店铺编号
			    $alipayStoreId = "test_alipay_store_id";

			    // 业务扩展参数，目前可添加由支付宝分配的系统商编号(通过setSysServiceProviderId方法)，详情请咨询支付宝技术支持
			    $providerId = ""; //系统商pid,作为系统商返佣数据提取的依据
			    $extendParams = new ExtendParams();
			    $extendParams->setSysServiceProviderId($providerId);
			    $extendParamsArr = $extendParams->getExtendParams();

			    // 支付超时，线下扫码交易定义为5分钟
			    $timeExpress = "5m";

			    // 商品明细列表，需填写购买商品详细信息，
			    $goodsDetailList = array();

			    // 创建一个商品信息，参数含义分别为商品id（使用国标）、名称、单价（单位为分）、数量，如果需要添加商品类别，详见GoodsDetail
			    $goods1 = new GoodsDetail();
			    $goods1->setGoodsId("good_id001");
			    $goods1->setGoodsName("XXX商品1");
			    $goods1->setPrice(3000);
			    $goods1->setQuantity(1);
			    //得到商品1明细数组
			    $goods1Arr = $goods1->getGoodsDetail();

			    // 继续创建并添加第一条商品信息，用户购买的产品为“xx牙刷”，单价为5.05元，购买了两件
			    $goods2 = new GoodsDetail();
			    $goods2->setGoodsId("good_id002");
			    $goods2->setGoodsName("XXX商品2");
			    $goods2->setPrice(1000);
			    $goods2->setQuantity(1);
			    //得到商品1明细数组
			    $goods2Arr = $goods2->getGoodsDetail();

			    $goodsDetailList = array($goods1Arr, $goods2Arr);

			    //第三方应用授权令牌,商户授权系统商开发模式下使用
			    $appAuthToken = "";//根据真实值填写

			    // 创建请求builder，设置请求参数
			    $barPayRequestBuilder = new AlipayTradePayContentBuilder();
			    $barPayRequestBuilder->setOutTradeNo($outTradeNo);
			    $barPayRequestBuilder->setTotalAmount($totalAmount);
			    $barPayRequestBuilder->setAuthCode($authCode);
			    $barPayRequestBuilder->setTimeExpress($timeExpress);
			    $barPayRequestBuilder->setSubject($subject);
			    $barPayRequestBuilder->setBody($body);
			    $barPayRequestBuilder->setUndiscountableAmount($undiscountableAmount);
			    $barPayRequestBuilder->setExtendParams($extendParamsArr);
			    $barPayRequestBuilder->setGoodsDetailList($goodsDetailList);
			    $barPayRequestBuilder->setStoreId($storeId);
			    $barPayRequestBuilder->setOperatorId($operatorId);
			    $barPayRequestBuilder->setAlipayStoreId($alipayStoreId);

			    $barPayRequestBuilder->setAppAuthToken($appAuthToken);

			    // 调用barPay方法获取当面付应答
			    $barPay = new AlipayTradeService($config);
			    $barPayResult = $barPay->barPay($barPayRequestBuilder);

			    switch ($barPayResult->getTradeStatus()) {
			        case "SUCCESS":
			            echo "支付宝支付成功:" . "<br>--------------------------<br>";
			            print_r($barPayResult->getResponse());
			            break;
			        case "FAILED":
			            echo "支付宝支付失败!!!" . "<br>--------------------------<br>";
			            if (!empty($barPayResult->getResponse())) {
			                print_r($barPayResult->getResponse());
			            }
			            break;
			        case "UNKNOWN":
			            echo "系统异常，订单状态未知!!!" . "<br>--------------------------<br>";
			            if (!empty($barPayResult->getResponse())) {
			                print_r($barPayResult->getResponse());
			            }
			            break;
			        default:
			            echo "不支持的交易状态，交易返回异常!!!";
			            break;
			    }
			    return;
			}

		}
		include template('zypay', 'f2f_barpay');		
	}

	/**
	* 例子页面_刷卡支付-二维码支付
	*/
	public function f2f_qrpay(){
		if($_POST){
			require_once 'classes/alipay/f2f/f2fpay/model/builder/AlipayTradePrecreateContentBuilder.php';
			require_once 'classes/alipay/f2f/f2fpay/service/AlipayTradeService.php';

			if (!empty($_POST['out_trade_no'])&& trim($_POST['out_trade_no'])!=""){
				// (必填) 商户网站订单系统中唯一订单号，64个字符以内，只能包含字母、数字、下划线，
				// 需保证商户系统端不能重复，建议通过数据库sequence生成，
				//$outTradeNo = "qrpay".date('Ymdhis').mt_rand(100,1000);
				$outTradeNo = $_POST['out_trade_no'];

				// (必填) 订单标题，粗略描述用户的支付目的。如“xxx品牌xxx门店当面付扫码消费”
				$subject = $_POST['subject'];

				// (必填) 订单总金额，单位为元，不能超过1亿元
				// 如果同时传入了【打折金额】,【不可打折金额】,【订单总金额】三者,则必须满足如下条件:【订单总金额】=【打折金额】+【不可打折金额】
				$totalAmount = $_POST['total_amount'];


				// (不推荐使用) 订单可打折金额，可以配合商家平台配置折扣活动，如果订单部分商品参与打折，可以将部分商品总价填写至此字段，默认全部商品可打折
				// 如果该值未传入,但传入了【订单总金额】,【不可打折金额】 则该值默认为【订单总金额】- 【不可打折金额】
				//String discountableAmount = "1.00"; //

				// (可选) 订单不可打折金额，可以配合商家平台配置折扣活动，如果酒水不参与打折，则将对应金额填写至此字段
				// 如果该值未传入,但传入了【订单总金额】,【打折金额】,则该值默认为【订单总金额】-【打折金额】
				$undiscountableAmount = "0.01";

				// 卖家支付宝账号ID，用于支持一个签约账号下支持打款到不同的收款账号，(打款到sellerId对应的支付宝账号)
				// 如果该字段为空，则默认为与支付宝签约的商户的PID，也就是appid对应的PID
				//$sellerId = "";

				// 订单描述，可以对交易或商品进行一个详细地描述，比如填写"购买商品2件共15.00元"
				$body = "购买商品2件共15.00元";

				//商户操作员编号，添加此参数可以为商户操作员做销售统计
				$operatorId = "test_operator_id";

				// (可选) 商户门店编号，通过门店号和商家后台可以配置精准到门店的折扣信息，详询支付宝技术支持
				$storeId = "test_store_id";

				// 支付宝的店铺编号
				$alipayStoreId= "test_alipay_store_id";

				// 业务扩展参数，目前可添加由支付宝分配的系统商编号(通过setSysServiceProviderId方法)，系统商开发使用,详情请咨询支付宝技术支持
				$providerId = ""; //系统商pid,作为系统商返佣数据提取的依据
				$extendParams = new ExtendParams();
				$extendParams->setSysServiceProviderId($providerId);
				$extendParamsArr = $extendParams->getExtendParams();

				// 支付超时，线下扫码交易定义为5分钟
				$timeExpress = "5m";

				// 商品明细列表，需填写购买商品详细信息，
				$goodsDetailList = array();

				// 创建一个商品信息，参数含义分别为商品id（使用国标）、名称、单价（单位为分）、数量，如果需要添加商品类别，详见GoodsDetail
				$goods1 = new GoodsDetail();
				$goods1->setGoodsId("apple-01");
				$goods1->setGoodsName("iphone");
				$goods1->setPrice(3000);
				$goods1->setQuantity(1);
				//得到商品1明细数组
				$goods1Arr = $goods1->getGoodsDetail();

				// 继续创建并添加第一条商品信息，用户购买的产品为“xx牙刷”，单价为5.05元，购买了两件
				$goods2 = new GoodsDetail();
				$goods2->setGoodsId("apple-02");
				$goods2->setGoodsName("ipad");
				$goods2->setPrice(1000);
				$goods2->setQuantity(1);
				//得到商品1明细数组
				$goods2Arr = $goods2->getGoodsDetail();

				$goodsDetailList = array($goods1Arr,$goods2Arr);

				//第三方应用授权令牌,商户授权系统商开发模式下使用
				$appAuthToken = "";//根据真实值填写

				// 创建请求builder，设置请求参数
				$qrPayRequestBuilder = new AlipayTradePrecreateContentBuilder();
				$qrPayRequestBuilder->setOutTradeNo($outTradeNo);
				$qrPayRequestBuilder->setTotalAmount($totalAmount);
				$qrPayRequestBuilder->setTimeExpress($timeExpress);
				$qrPayRequestBuilder->setSubject($subject);
				$qrPayRequestBuilder->setBody($body);
				$qrPayRequestBuilder->setUndiscountableAmount($undiscountableAmount);
				$qrPayRequestBuilder->setExtendParams($extendParamsArr);
				$qrPayRequestBuilder->setGoodsDetailList($goodsDetailList);
				$qrPayRequestBuilder->setStoreId($storeId);
				$qrPayRequestBuilder->setOperatorId($operatorId);
				$qrPayRequestBuilder->setAlipayStoreId($alipayStoreId);

				$qrPayRequestBuilder->setAppAuthToken($appAuthToken);


				// 调用qrPay方法获取当面付应答
				$qrPay = new AlipayTradeService($config);
				$qrPayResult = $qrPay->qrPay($qrPayRequestBuilder);

				//	根据状态值进行业务处理
				switch ($qrPayResult->getTradeStatus()){
					case "SUCCESS":
						echo "支付宝创建订单二维码成功:"."<br>---------------------------------------<br>";
						$response = $qrPayResult->getResponse();
						$qrcode = $qrPay->create_erweima($response->qr_code);
						echo $qrcode;
						print_r($response);
						
						break;
					case "FAILED":
						echo "支付宝创建订单二维码失败!!!"."<br>--------------------------<br>";
						if(!empty($qrPayResult->getResponse())){
							print_r($qrPayResult->getResponse());
						}
						break;
					case "UNKNOWN":
						echo "系统异常，状态未知!!!"."<br>--------------------------<br>";
						if(!empty($qrPayResult->getResponse())){
							print_r($qrPayResult->getResponse());
						}
						break;
					default:
						echo "不支持的返回状态，创建订单二维码返回异常!!!";
						break;
				}
				return ;
			}
			
		}
		include template('zypay', 'f2f_qrpay');		
	}

	/**
	* 例子页面_刷卡支付-订单查询
	*/
	public function f2f_query(){
		if($_POST){
			require_once 'classes/alipay/f2f/f2fpay/service/AlipayTradeService.php';

			if (!empty($_POST['out_trade_no'])&& trim($_POST['out_trade_no'])!=""){
			    ////获取商户订单号
			    $out_trade_no = trim($_POST['out_trade_no']);

			    //第三方应用授权令牌,商户授权系统商开发模式下使用
			    $appAuthToken = "";//根据真实值填写

			    //构造查询业务请求参数对象
			    $queryContentBuilder = new AlipayTradeQueryContentBuilder();
			    $queryContentBuilder->setOutTradeNo($out_trade_no);

			    $queryContentBuilder->setAppAuthToken($appAuthToken);


			    //初始化类对象，调用queryTradeResult方法获取查询应答
			    $queryResponse = new AlipayTradeService($config);
			    $queryResult = $queryResponse->queryTradeResult($queryContentBuilder);

			    //根据查询返回结果状态进行业务处理
			    switch ($queryResult->getTradeStatus()){
			        case "SUCCESS":
			            echo "支付宝查询交易成功:"."<br>--------------------------<br>";
			            print_r($queryResult->getResponse());
			            break;
			        case "FAILED":
			            echo "支付宝查询交易失败或者交易已关闭!!!"."<br>--------------------------<br>";
			            if(!empty($queryResult->getResponse())){
			                print_r($queryResult->getResponse());
			            }
			            break;
			        case "UNKNOWN":
			            echo "系统异常，订单状态未知!!!"."<br>--------------------------<br>";
			            if(!empty($queryResult->getResponse())){
			                print_r($queryResult->getResponse());
			            }
			            break;
			        default:
			            echo "不支持的查询状态，交易返回异常!!!";
			            break;
			    }
			    return ;
			}
		}
		include template('zypay', 'f2f_query');		
	}

	/**
	* 例子页面_刷卡支付-订单退款
	*/
	public function f2f_refund(){
		if($_POST){
			require_once 'classes/alipay/f2f/f2fpay/model/builder/AlipayTradeRefundContentBuilder.php';
			require_once 'classes/alipay/f2f/f2fpay/service/AlipayTradeService.php';

			if (!empty($_POST['out_trade_no'])&& trim($_POST['out_trade_no'])!=""){
				
				$out_trade_no = trim($_POST['out_trade_no']);
				$refund_amount = trim($_POST['refund_amount']);
				$out_request_no = trim($_POST['out_request_no']);

				//第三方应用授权令牌,商户授权系统商开发模式下使用
				$appAuthToken = "";//根据真实值填写
				
				//创建退款请求builder,设置参数
				$refundRequestBuilder = new AlipayTradeRefundContentBuilder();
					$refundRequestBuilder->setOutTradeNo($out_trade_no);
					$refundRequestBuilder->setRefundAmount($refund_amount);
					$refundRequestBuilder->setOutRequestNo($out_request_no);

					$refundRequestBuilder->setAppAuthToken($appAuthToken);

				//初始化类对象,调用refund获取退款应答
				$refundResponse = new AlipayTradeService($config);
				$refundResult =	$refundResponse->refund($refundRequestBuilder);

				//根据交易状态进行处理
				switch ($refundResult->getTradeStatus()){
					case "SUCCESS":
						echo "支付宝退款成功:"."<br>--------------------------<br>";
						print_r($refundResult->getResponse());
						break;
					case "FAILED":
						echo "支付宝退款失败!!!"."<br>--------------------------<br>";
						if(!empty($refundResult->getResponse())){
							print_r($refundResult->getResponse());
						}
						break;
					case "UNKNOWN":
						echo "系统异常，订单状态未知!!!"."<br>--------------------------<br>";
						if(!empty($refundResult->getResponse())){
							print_r($refundResult->getResponse());
						}
						break;
					default:
						echo "不支持的交易状态，交易返回异常!!!";
						break;
				}
				return ;
			}
		}
		include template('zypay', 'f2f_refund');		
	}


//==============支付宝支付-刷卡支付 DEMO==============





//=======================================支付宝支付 DEMO====================================
















}
?>