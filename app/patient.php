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
		$production_unit = trim($_POST['production_unit']);
		$create_time = trim($_POST['create_time']);
		$recorder = trim($_POST['recorder']);
		$repaire_type = intval($_POST['repaire_type']);
		$repaire_pic = $_FILES['repaire_pic'];

		

		$name = time().'.jpg';
		$file_path = "E:/mobile/jqmobile/app/public/upload/".$name;

		if(move_uploaded_file($_FILES['repaire_pic']['tmp_name'], $file_path))
		{
			//插入基本信息
			$data = array(
			'name'	=> $patient_name,
			'sex'   => $sex,
			'birthday' => $patient_age,
			'hospital' => $hospital,
			'doctor' => $doctor,
			'tooth_position' => $tooth_position,
			'production_unit' => $production_unit,
			'create_time' => $create_time,
			'operator' => $recorder,
			'false_tooth' => $repaire_type,
			'repaire_pic' => $name
		);
			/*
			importModule("userInfo","class");
			$obj_user = new userInfo;
			$user_id = $obj_user->insert_user($data);

			if ($user_id) {
				header('Location: user.php?do=ucenter&user_id='.$user_id);
			}
			*/
		}
		else 
		{
			echo json_encode(array('status'=>0, 'message'=>'failed'));
		}

		var_dump($data);die;

		print_r($data);
		print_r($_FILES);die;
	}

	
}
$app->run();
	
?>
