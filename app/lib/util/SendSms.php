<?php

/**
 * 发短信
 */
class SendSms {
	private static $user = 'dd1678';
	private static $password = '123456';
	private static $id = 15450;
	private static $api_url = 'http://www.qf106.com/sms.aspx?action=send';
	
	//发送验证码
	public function send_vcode($mobile, $code)
	{
		import('util.RequestCurl');
		$code = 1234;
		$param = '&userid=12&account='.self::$user.'&password='.self::$password.'&mobile='.$mobile.'&content='.$code.'&mobilenumber=1';
		$url = self::api_url.$param;
		$result = RequestCurl::curl_get($url);
		var_dump($result);
	}
	
	
}

?>