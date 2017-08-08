<?php 
/**
 * 会员信息处理
 * 
 */
require_once('./common.inc.php');

class member extends Action {
	
	/**
	 * 默认执行的方法(用户登录页面)
	 */
	public function doDefault(){	
		$act = $_POST['act'];
		$page = !empty($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$page_size = 10;
		
		$data = array(
			'page'		=> $page,
			'page_size'	=> $page_size
		);
		if($act == 1)
		{
			$serach = !empty($_POST['search']) ? trim($_POST['search']) : '';
			$data['hospital'] = $serach;
		}
		
		importModule("PatientInfo","class");
		$obj_patient = new PatientInfo;
		$list = $obj_patient->patient_list($data);
		if(!empty($list))
		{
			foreach($list as $key => $val)
			{
				$list[$key]['create_time'] = date("Y/m/d", strtotime($val['create_time']));
			}
		}
		$page = $this->app->page();
		$page->value('user_list',$_SESSION);
		$page->value('list',$list);
		$page->params['template'] = 'search.php';
		$page->output();
	}
	
	//搜索
	public function doSearch()
	{
		print_r($_POST);
	}
	
}
$app->run();
	
?>
