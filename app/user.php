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
	
	//注册
	public function doRegister()
	{
		$realname = $_POST['realname'];
		$pwd1	= $_POST['password1'];
		$pwd2	= $_POST['password2'];
		$user_type = $_POST['typer'];
		$email = $_POST['email'];
		$company_name = $_POST['company_name'];
		$job = $_POST['job'];
		$create_time = $_POST['create_time'];
		$employee_num = $_POST['employee_num'];
		$province = $_POST['province'];
		$city = $_POST['city'];
		$district = $_POST['district'];
		$address = $_POST['address'];
		$dec = $_POST['addinfo'];
		$files = $_FILES['cfile'];
		
		
		$name = time().'.jpg';
		$file_path = "E:/mobile/jqmobile/app/public/upload/".$name;
        
		if(move_uploaded_file($_FILES['cfile']['tmp_name'], $file_path))
		{
			//插入基本信息
			$data = array(
				'realname'	=> $realname,
				'password'	=> $pwd1,
				'user_type'	=> $user_type=='techer' ? 1 :2,
				'email'	    => $email,
				'company_name'	=> $company_name,
				'job'		=> $job,
				'create_time' => $create_time,
				'employee_num' => $employee_num,
				'province'	=> $province,
				'city'		=> $city,
				'district'	=> $district,
				'address' => $address,
				'dec'	=> $dec,
				'pic'	=> $name
			); 
			
			importModule("userInfo","class");
			$obj_user = new userInfo;
			$user_id = $obj_user->insert_user($data);

			if ($user_id) {
				header('Location: user.php?do=ucenter&user_id='.$user_id);
			}
		}
		else 
		{
			echo json_encode(array('status'=>0, 'message'=>'failed'));
		}
		
	}

	//用户中心
	public function doUcenter()
	{
		$user_id = $_GET['user_id'];
		importModule("userInfo","class");
		$obj_user = new userInfo;

		$user = $obj_user->get_user_detail($user_id);

		$page = $this->app->page();
		$page->value('user',$user[0]);
		$page->params['template'] = 'user.php';
		$page->output();
	}

	//质保卡积分录入
	public function doPatientIn()
	{
		$user_id = $_GET['user_id'];
		$page = $this->app->page();
		//$page->value('user',$user[0]);
		$page->params['template'] = 'patient.php';
		$page->output();
	}

	//医生录入
	public function doDoctorIn()
	{
		$user_id = $_GET['user_id'];
		$page = $this->app->page();
		//$page->value('user',$user[0]);
		$page->params['template'] = 'doctor.php';
		$page->output();
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

		//验证验证码
		//todo

		importModule("userInfo","class");
		$obj_user = new userInfo;
		$res = $obj_user->update_pwd($mobile, $pwd1);
		
		if ($res) {
			header('Location: user.php?do=ucenter&user_id='.$user_id);
		}

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
		
		importModule("AreaInfo","class");
		$obj_area = new AreaInfo;
		$province = $obj_area->get_province();
		
		//获取 省份
		$page = $this->app->page();
		$page->value('province',$province);
		$page->params['template'] = 'register_t.php';
		$page->output();
			/*
		if (!empty($code[0]['code']) && $code[0]['code'] == $vcode)
		{
			$page = $this->app->page();
			$page->params['template'] = 'register_t.php';
			$page->output();
		}
		else
		{
			echo "<script>history.back(-1);return false;</script>";
		}*/
	}
	
	//协议
	public function doViewXy()
	{
		$page = $this->app->page();
		$page->params['template'] = 'xy.php';
		$page->output();
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
