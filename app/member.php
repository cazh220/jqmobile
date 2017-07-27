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
		
		$page = $this->app->page();
		$page->value('user_list',$_SESSION);
		$page->params['template'] = 'search.php';
		$page->output();
	}
	
}
$app->run();
	
?>
