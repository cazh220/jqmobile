<?php 
/**
 * 区域处理
 * 
 */
require_once('./common.inc.php');

class area extends Action {
	
	/**
	 * 默认执行的方法(用户登录页面)
	 */
	public function doDefault(){	
		
		$page = $this->app->page();
		$page->value('user_list',$_SESSION);
		$page->params['template'] = 'user.php';
		$page->output();
	}
	
	//获取城市
	public function doGetCity()
	{
		$province_id = $_GET['province_id'];
		
		importModule("AreaInfo","class");
		$obj_area = new AreaInfo;
		$city = $obj_area->get_city($province_id);
		
		if (!empty($city))
		{
			exit(json_encode(array('status'=>1, 'list'=>$city)));
		}
		else
		{
			exit(json_encode(array('status'=>0, 'list'=>array())));
		}
		
	}
	
	//获取区域
	public function doGetDistrict()
	{
		$city_id = $_GET['city_id'];
		
		importModule("AreaInfo","class");
		$obj_area = new AreaInfo;
		$district = $obj_area->get_city($city_id);
		
		if (!empty($district))
		{
			exit(json_encode(array('status'=>1, 'list'=>$district)));
		}
		else
		{
			exit(json_encode(array('status'=>0, 'list'=>array())));
		}
		
	}
	
	
}
$app->run();
	
?>
