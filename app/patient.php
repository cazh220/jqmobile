<?php 
/**
 * 医生类
 * 
 */
require_once('./common.inc.php');

class patient extends Action {
	static $credits = 30;
	
	/**
	 * 默认执行的方法
	 */
	public function doDefault(){	
		
		$page = $this->app->page();
		$page->value('user',$_SESSION);
		$page->params['template'] = 'user.php';
		$page->output();
	}
	
	//编辑技工录入
	public function doTechRecord()
	{
		$qrcode = $_GET['qrcode'];
		importModule("PatientInfo","class");
		$obj_patient = new PatientInfo;
		$patient = $obj_patient->get_patient($qrcode);
		
		
		$page = $this->app->page();
		$page->value('user',$_SESSION);
		$page->value('patient',$patient[0]);
		$page->params['template'] = 'patient_update.php';
		$page->output();
	}

	//病人录入
	public function doAddPatient()
	{

		$hospital	= trim($_POST['hospital']);
		$doctor = trim($_POST['doctor']);
		$patient_name = trim($_POST['patient_name']);
		$sex = $_POST['gender'];
		$patient_age = intval($_POST['patient_age']);
		$tooth_position = trim($_POST['tooth_position']);
		//$production_unit = trim($_POST['production_unit']);
		//$create_time = trim($_POST['create_time']);
		//$recorder = trim($_POST['recorder']);
		$repaire_type = intval($_POST['repaire_type']);
		$repaire_pic = $_FILES['repaire_pic'];
		$user_id	= intval($_POST['user_id']);
		$qrcode     = trim($_POST['qrcode']);
	    
	    //上传图片
		$upload_pic = $this->_upload_pic();
		if (!empty($upload_pic))
		{
			//插入基本信息
			$data = array(
				'name'				=> $patient_name,
				'sex'   			=> $sex,
				'birthday' 			=> $patient_age,
				'hospital' 			=> $hospital,
				'doctor' 			=> $doctor,
				'tooth_position' 	=> $tooth_position,
				'production_unit' 	=> $_SESSION['company_name'],
				'create_time' 		=> date("Y-m-d H:i:s", time()),
				'operator' 			=> $_SESSION['realname'],
				'false_tooth' 		=> $repaire_type,
				'repaire_pic' 		=> $upload_pic,
				'security_code' 	=> $qrcode,
				'mobile'			=> $_SESSION['mobile'],
				'email'				=> $_SESSION['email'],
				'update_time'		=> date("Y-m-d H:i:s", time()),
				'credits'			=> self::$credits,
				'user_id'			=> $_SESSION['user_id']
			);
	
			importModule("PatientInfo","class");
			$obj_patient = new PatientInfo;
			$res = $obj_patient->insert_patient($data);

			if ($res) {
				$_SESSION['qrcode'] = $qrcode;
				header('Location: patient.php?do=recordsuccess&user_id='.$user_id);
			}
		}
		else 
		{
			echo json_encode(array('status'=>0, 'message'=>'failed'));
		}

	}
	
	//病人更新
	public function doUpdatePatient()
	{
		$hospital	= trim($_GET['hospital']);
		$doctor = trim($_GET['doctor']);
		$patient_name = trim($_GET['patient_name']);
		$sex = $_GET['sex'];
		$patient_age = intval($_GET['patient_age']);
		$tooth_position = trim($_GET['tooth_position']);
		$repaire_pic = $_GET['file'];
		$false_tooth = $_GET['false_tooth'];
		$user_id	= $_SESSION['user_id'];
		$qrcode     = trim($_GET['qrcode']);
	    
	    $data = array(
				'patient_name'		=> $patient_name,
				'sex'   			=> $sex,
				'patient_age' 		=> $patient_age,
				'hospital' 			=> $hospital,
				'doctor' 			=> $doctor,
				'tooth_position' 	=> $tooth_position,
				'false_tooth' 		=> $false_tooth,
				'repaire_pic' 		=> $repaire_pic,
				'security_code' 	=> $qrcode,
				'user_id'			=> $_SESSION['user_id']
			);
	    

	
		importModule("PatientInfo","class");
		$obj_patient = new PatientInfo;
		$res = $obj_patient->update_patient($data);

		if ($res) {
			exit(json_encode(array('status'=>true, 'message'=>'更新成功', url=>'user.php?do=ucenter&user_id='.$user_id.'&qrcode='.$qrcode)));
			//header('Location: user.php?do=ucenter&user_id='.$user_id."&qrcode="+$qrcode);
		}
		else
		{
			exit(json_encode(array('status'=>false, 'message'=>'更新失败')));
		}

	}
	
	//录入成功
	public function doRecordSuccess()
	{
		//积分详情
		importModule("userInfo","class");
		$obj_user = new userInfo;
		$left_credits = $obj_user->get_user_credits($_SESSION['user_id']);
		
		//获取最近录入的信息
		importModule("PatientInfo","class");
		$obj_patient = new PatientInfo;
		$patient = $obj_patient->patient_list(array('page'=>1, 'page_size'=>10));
		$data = array();
		if(!empty($patient))
		{
			foreach($patient as $key => $val)
			{
				$data[$key]['create_time'] = date("Y/m/d", strtotime($val['create_time']));
				$data[$key]['security_code'] = $val['security_code'];
				$data[$key]['hospital'] = $val['hospital'];
				$data[$key]['doctor'] = $val['doctor'];
			}
		}
		
		//print_r($data);die;
		$page = $this->app->page();
		$page->value('user',$_SESSION);
		$page->value('credits',self::$credits);
		$page->value('left_credits',$left_credits);
		$page->value('patient',$data);
		$page->params['template'] = 'card_success.php';
		$page->output();
	}
	
	//上传图片
	private function _upload_pic()
	{
		$ar_type  = explode('.',$_FILES['repaire_pic']['name']); 
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
		
		$res = $obj_upload->upload($_FILES['repaire_pic'],'./public/upload/data/', 1);

		if($res === false) {
			exit(json_encode(array('status'=>0, 'info'=>'文件上传失败')));
		}
		
		return $res;
	}

	
}
$app->run();
	
?>
