<?php 
/**
 * 用户处理类
 * 
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
		$mobile = $_POST['mobile'];
		$username = $_POST['username'];
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

		//上传图片
		$upload_pic = $this->_upload_pic();
		if (!empty($upload_pic))
		{
			import('util.Ip');
			$obj_ip = new Ip;
			$s_ip = $obj_ip->get();
			$s_ip = $s_ip ? $s_ip : $_SERVER['REMOTE_ADDR'];
			//插入基本信息
			$data = array(
				'mobile'	=> $mobile,
				'username'	=> $username,
				'realname'	=> $realname,
				'password'	=> $pwd1,
				'user_type'	=> $user_type,//1技工   2医生
				'email'	    => $email,
				'company_name'	=> $company_name,
				'job'		=> $job,
				'birthday' => $create_time.' 00:00:00',
				'employee_num' => $employee_num,
				'province'	=> $province,
				'city'		=> $city,
				'district'	=> $district,
				'address' => $address,
				'dec'	=> $dec,
				'pic'	=> $upload_pic,
				'create_time' => date("Y-m-d H:i:s", time()),
				'last_login'	=> date("Y-m-d H:i:s", time()),
				'last_ip'	=> $s_ip
			);
			
			
			importModule("userInfo","class");
			$obj_user = new userInfo;
			$user_id = $obj_user->insert_user($data);

			if ($user_id) {
				//注册成功
				$user_info = array(
					'username'	=> $username,
					'realname'	=> $realname,
					'mobile'	=> $mobile,
					'user_type'	=> $user_type,
					'email'		=> $email,
					'birthday'	=> $create_time.' 00:00:00',
					'company_name'	=> $company_name,
					'position'	=> $job,
					'total_credits'	=> 0,
					'exchanged_credits'	=> 0,
					'left_credits'	=> 0,
					'persons_num'	=> $employee_num,
					'create_time'	=> date("Y-m-d H:i:s", time()),
					'head_img'		=> $upload_pic,
					'user_id'		=> $user_id
				);
				$_SESSION = $user_info;
				$_SESSION['login_time']  = time();
				header('Location: user.php?do=ucenter&user_id='.$user_id);//进入会员中心
			}
		}
		else 
		{
			exit(json_encode(array('status'=>0, 'message'=>'failed')));
		}
		
		
	}

	//用户中心
	public function doUcenter()
	{
		//$user_id = $_GET['user_id'];
		$user_id = $_SESSION['user_id'];
		importModule("userInfo","class");
		$obj_user = new userInfo;

		$user = $obj_user->get_user_detail($user_id);
		//获取未读消息数量
		importModule("MessageInfo","class");
		$obj_message = new MessageInfo;
		$message_count = $obj_message->get_unread_count($user_id);
	    //var_dump($message_count);die;
		$page = $this->app->page();
		$page->value('user',$user[0]);
		$page->value('message_count',$message_count);
		$page->params['template'] = 'user.php';
		$page->output();
	}

	//质保卡积分录入
	public function doPatientIn()
	{
		$user_id = $_GET['user_id'];
		$qrcode = $_GET['qrcode'];
		/*
		if(empty($qrcode))
		{ 
			echo "<script>alert('未知防伪码');history.back();</script>";
		}*/

		$page = $this->app->page();
		$page->value('user_id',$user_id);
		$page->value('qrcode',$qrcode);
		$page->value('user',$_SESSION);
		$page->params['template'] = 'patient.php';
		$page->output();
	}

	//医生录入
	public function doDoctorIn()
	{
		$user_id = $_GET['user_id'];
		$qrcode = $_GET['qrcode'];
		//获取患者信息
		importModule("PatientInfo","class");
		$obj_patient = new PatientInfo;
		$patient = $obj_patient->get_patient($qrcode);

		$page = $this->app->page();
		//print_r($_SESSION);
		//print_r($patient[0]);
		$page->value('patient',$patient[0]);
		$page->value('doctor',$_SESSION);
		$page->value('qrcode',$qrcode);
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
		$mobile = trim($_GET['mobile']);
		$vcode  = trim($_GET['vcode']);
		
		importModule("SmsCode","class");
		$obj_code = new SmsCode;
		
		$code = $obj_code->validate_code($mobile);
		
		if (!empty($code[0]['code']) && $code[0]['code'] == $vcode)
		{
			exit(json_encode(array('status'=>true, 'message'=>'一致')));
		}
		else
		{
			exit(json_encode(array('status'=>false, 'message'=>'不一致')));
		}
		
	}
	
	//注册页面
	public function doShowRegister()
	{
		$mobile = $_GET['mobile'];
		//获取 省份
		importModule("AreaInfo","class");
		$obj_area = new AreaInfo;
		$province = $obj_area->get_province();
		
		$page = $this->app->page();
		$page->value('province',$province);
		$page->value('mobile',$mobile);
		$page->value('username',$mobile);
		$page->params['template'] = 'register_t.php';
		$page->output();
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
				exit(json_encode(array('status'=>true, 'message'=>'验证码已发送')));
			}
		}
		exit(json_encode(array('status'=>false, 'message'=>'验证码发送失败')));
		
	}
	
	
	//上传图片
	private function _upload_pic()
	{
		$ar_type  = explode('.',$_FILES['cfile']['name']); 
		$s_type   = strtolower($ar_type[1]);
		
		if(!in_array($s_type,array('jpg','png','bmp','gif'))){
			exit(json_encode(array('status'=>0, 'info'=>'文件类型不正确')));
		}
		
		if(!file_exists('public/upload/data/')) {
			if(!mkdir('data')) {
				exit(json_encode(array('status'=>0, 'info'=>'创建目录失败')));
			}
			
			chmod('data/',0777);
		}
		
		import('util.UploadFile');
		$obj_upload = new UploadFile;
		
		$res = $obj_upload->upload($_FILES['cfile'],'./public/upload/data/', 1);

		if($res === false) {
			exit(json_encode(array('status'=>0, 'info'=>'文件上传失败')));
		}
		
		return $res;
	}
	
	//设置
	public function doSetting()
	{
		$page = $this->app->page();
		$page->params['template'] = 'setting.php';
		$page->output();
	}
	
	//个人中心
	public function doMember()
	{
		$user_id = $_SESSION['user_id'];
		//获取个人信息
		importModule("userInfo","class");
		$obj_user = new userInfo;
		$user = $obj_user->get_user_detail($user_id);

		//获取 省份
		importModule("AreaInfo","class");
		$obj_area = new AreaInfo;
		$province = $obj_area->get_province();
		
		$user[0]['birthday'] = date("Y-m-d", strtotime($user[0]['birthday']));
		//获取省
		importModule("AreaInfo","class");
		$obj_area = new AreaInfo;
		$province = $obj_area->get_province();
		//获取市
		$city = $obj_area->get_city($user[0]['province']);
		//获取区域
		$district = $obj_area->get_district($user[0]['city']);
		
		$page = $this->app->page();
		$page->value('mine',$user[0]);
		$page->value('province',$province);
		$page->value('city',$city);
		$page->value('district',$district);
		$page->params['template'] = 'member.php';
		$page->output();
	}
	
	//修改个人信息
	public function doUpdateUser()
	{
		$realname = trim($_GET['realname']);
		$user_type = intval($_GET['user_type']);
		$email = trim($_GET['email']);
		$company_name = trim($_GET['company_name']);
		$address = trim($_GET['address']);
		$company_pic = trim($_GET['company_pic']);
		$info = trim($_GET['info']);
		$province = intval($_GET['province']);
		$city = intval($_GET['city']);
		$district = intval($_GET['district']);
		
		$data = array(
			'realname'		=> $realname,
			'user_type'		=> $user_type,
			'email'			=> $email,
			'company_name'	=> $company_name,
			'address'		=> $address,
			'company_pic'	=> $company_pic,
			'info'			=> $info,
			'province'		=> $province,
			'city'			=> $city,
			'district'		=> $district,
			'user_id'		=> $_SESSION['user_id']
		);
		
		importModule("userInfo","class");
		$obj_user = new userInfo;
		$res = $obj_user->update_user($data);
		
		if($res)
		{
			exit(json_encode(array('status'=>true, 'message'=>'更新会员资料成功')));
		}
		else
		{
			exit(json_encode(array('status'=>false, 'message'=>'更新会员资料失败')));
		}
	}
	
	//注销
	public function doLogOut()
	{
		unset($_SESSION);
		header("Location:user.php");
	}
	
	/**
	 * 数据更新失败记录日志，并标识操作失败
	 *
	 * @param Array $data
	 * @return false
	 */
	private function _log($data){
	    $log = $this->app->log(); 
	    $log->reset()->setPath("user")->setData($data)->write();	
	}
}
$app->run();
	
?>
