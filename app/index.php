<?php 
/**
 * 处理类
 * 
 * @package  	controller
 * @author 	    鲍(chenglin.bao@lyceem.com)
 * @copyright   2010-4-29
 */
require_once('./common.inc.php');

class index extends Action {
	
	/**
	 * 默认执行的方法(用户登录页面)
	 */
	public function doDefault(){	
		
		$page = $this->app->page();
		//print_r($_SESSION);die;
		$page->value('user',$_SESSION);
		$page->params['template'] = 'user.php';
		$page->output();
	}
	
	//关于页面
	public function doAbout()
	{
		$page = $this->app->page();
		$page->params['template'] = 'about.php';
		$page->output();
	}
	
	//反馈页面
	public function doFeedBack()
	{
		if($_POST['action']==1)
		{
			$feedback = $_POST['feedback'];
			$user_id  = $_SESSION['user_id'];
			
			importModule("userInfo","class");
			$obj_user = new userInfo;
			
			$res = $obj_user->feedback($user_id, $feedback);
		}
		$page = $this->app->page();
		$page->params['template'] = 'feedback.php';
		$page->output();
	}
	
	//分享页面
	public function doShare()
	{
		$security_code = !empty($_GET['security_code']) ? trim($_GET['security_code']) : '';
		//查询防伪详情
		importModule("PatientInfo","class");
		$obj_patient = new PatientInfo;
		$patient = $obj_patient->get_patient($security_code);
		//获取技工信息
		importModule("userInfo","class");
		$obj_user = new userInfo;
		$teach = $obj_user->get_user_detail($patient[0]['tech_id']);
		//获取医生信息
		$doctor = $obj_user->get_user_detail($patient[0]['doctor_id']);
		$patient[0]['tech'] = $teach[0];
		$patient[0]['doc'] = $doctor[0];

		$patient[0]['wxname'] = mb_substr($patient[0]['name'], 0, 1, 'utf8').'**';
		$page = $this->app->page();
		$page->value('patient',$patient[0]);
		$page->params['template'] = 'share.php';
		$page->output();
	}


	
}
$app->run();
	
?>
