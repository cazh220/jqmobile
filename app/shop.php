<?php 
/**
 * 商城处理类

 */
require_once('./common.inc.php');

class shop extends Action {
	
	/**
	 * 默认执行的方法(用户登录页面)
	 */
	public function doDefault(){	

		importModule("ShopInfo","class");
		$obj_shop = new ShopInfo;
		$products = $obj_shop->get_products();

		//print_r($products);die;
		
		$page = $this->app->page();
		//print_r($_SESSION);
		$page->value('list', $products);
		$page->value('user',$_SESSION);
		$page->params['template'] = 'shop.php';
		$page->output();
	}
	
}
$app->run();
	
?>
