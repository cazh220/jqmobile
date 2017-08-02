<?php 
/**
 * 消息类
 * 
 */
require_once('./common.inc.php');

class message extends Action {
	
	/**
	 * 默认执行的方法
	 */
	public function doDefault(){	
		
		$page = $this->app->page();
		$page->value('user',$_SESSION);
		$page->params['template'] = 'user.php';
		$page->output();
	}

	
}
$app->run();
	
?>
