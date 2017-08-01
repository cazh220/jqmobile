<?php 
/**
 * 医生类
 * 
 */
require_once('./common.inc.php');

class patient extends Action {
	
	/**
	 * 默认执行的方法
	 */
	public function doDefault(){	
		
		$page = $this->app->page();
		$page->value('user',$_SESSION);
		$page->params['template'] = 'user.php';
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
				'name'	=> $patient_name,
				'sex'   => $sex,
				'birthday' => $patient_age,
				'hospital' => $hospital,
				'doctor' => $doctor,
				'tooth_position' => $tooth_position,
				'production_unit' => $_SESSION['company_name'],
				'create_time' => date("Y-m-d H:i:s", time()),
				'operator' => $_SESSION['realname'],
				'false_tooth' => $repaire_type,
				'repaire_pic' => $upload_pic,
				'security_code' => $qrcode,
				'mobile'	=> $_SESSION['mobile'],
				'email'		=> $_SESSION['email'],
				'update_time'	=> date("Y-m-d H:i:s", time()),
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
	
	//录入成功
	public function doRecordSuccess()
	{
		$page = $this->app->page();
		$page->value('user',$_SESSION);
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
