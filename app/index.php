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

	
}
$app->run();
	
?>
