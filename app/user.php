<?php 
/**
 * 用户处理类
 * 
 * @package  	controller
 * @author 	    鲍(chenglin.bao@lyceem.com)
 * @copyright   2010-4-29
 */
require_once('./common.inc.php');

class user extends Action {
	
	/**
	 * 默认执行的方法(用户登录页面)
	 */
	public function doDefault(){	
		$page = $this->app->page();
		$page->params['template'] = 'login.php';
		$page->output();
	}
	
	/**
	 * 登录处理
	 */
	public function doLogin(){
		$this->s_sessionid = $_SESSION['sess_id'];
		
		if(empty($this->s_sessionid)){
			$this->app->redirect('user.php',0);
		}
	    
		import('util.Clean');
		
		//用户名
		$s_username = !empty($_POST['username']) ? Clean::htmlSafe($_POST['username']) : '';
		
		//密码
		$s_password = !empty($_POST['password']) ?  Clean::htmlSafe($_POST['password']) : '';

		if(empty($s_username) || empty($s_password)){
			exit(json_encode(array('status'=>false,'info'=>'帐号和密码不能为空！')));
		}

		importModule("userInfo","class");
		$obj_user = new userInfo;

		$res_login = $obj_user->findLogin($s_username,$s_password);
		
		if($res_login === false || !is_array($res_login)){
			$this->_log(array( __CLASS__ . '.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' result error : '.$res_login, date("Y-m-d H:i:s")));
			exit(json_encode(array('status'=>false,'info'=>'您输入的帐号信息不正确！')));
		}
		
		if($res_login['user_id'] == 0 || empty($res_login)){
			exit(json_encode(array('status'=>false,'info'=>'您输入的帐号信息不正确！')));
		}
		
		//获取客户端Ip
		import('util.Ip','class');
		$obj_ip = new Ip;
		
		//登录成功更新最后登录时间和IP
		$res = $obj_user->updateLoginInfo($res_login['user_id'],$obj_ip->get());
		
		if ($res === false) {
			$this->_log(array( __CLASS__ . '.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' update is fail : '.$res, date("Y-m-d H:i:s")));
		}

                reset($_SESSION);

		//登录成功，设置用户到session
		$_SESSION = $res_login;
		$_SESSION['login_time']  = time();
		
		exit(json_encode(array('status'=>true,'info'=>'登录成功！')));
	}

	//录入查询
	public function doRecordQuery()
	{
		$page = $this->app->page();
		$page->params['template'] = 'card_record.php';
		$page->output();
	}

	//忘记密码
	public function doFindPwd()
	{
		$page = $this->app->page();
		$page->params['template'] = 'findpwd.php';
		$page->output();
	}

	//修改密码
	public function doUpdatePwd()
	{
		$mobile = !empty($_POST['mobile']) ? trim($_POST['mobile']) : '';
		$vcode  = !empty($_POST['vcode']) ? trim($_POST['vcode']) : '';
		$pwd1   = !empty($_POST['password1']) ? trim($_POST['password1']) : '';
		$pwd2   = !empty($_POST['password2']) ? trim($_POST['password2']) : '';

		//确认密码是否一致
		

		//验证验证码是否一直
		


		print_r($_POST);
	}
	
	//绑定手机
	public function doBind_mobile()
	{
		import('util.RequestCurl');
		
		$page = $this->app->page();
		$page->params['template'] = 'register.php';
		$page->output();
	}
	
	//验证手机
	public function doValidateMobile()
	{
		$mobile = trim($_POST['mobile']);
		$vcode  = trim($_POST['vcode']);
		
		importModule("SmsCode","class");
		$obj_code = new SmsCode;
		
		$code = $obj_code->validate_code($mobile);
		
		if (!empty($code[0]['code']) && $code[0]['code'] == $vcode)
		{
			$page = $this->app->page();
			$page->params['template'] = 'register_t.php';
			$page->output();
		}
		else
		{
			echo "<script>history.back(-1);return false;</script>";
		}
	}
	
	//发短信息
	public function doSendSms()
	{
		$mobile = trim($_GET['mobile']);
		//生成验证码
		import('util.Vcode');
		$code = Vcode::generate_num();
		//写入数据库
		importModule("SmsCode","class");
		$obj_code = new SmsCode;
		$res = $obj_code->generate_code($mobile, $code);
		
		//调用接口发短信
		if($res === true)
		{
			import('util.SendSms');
			$result = SendSms::send_vcode($mobile, $code);
			if ($result->returnstatus == 'Success')
			{
				echo "<script>alert('动态码已下发');</script>";
			}
			print_r($result);
		}
		
	}
}
$app->run();
	
?>
