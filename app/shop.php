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
		//获取积分用户信息
		importModule("userInfo","class");
		$obj_user = new userInfo;
		$user = $obj_user->get_user_detail($_SESSION['user_id']);
	
		$page->value('list', $products);
		$page->value('user',$user[0]);
		$page->params['template'] = 'shop.php';
		$page->output();
	}
	
}
$app->run();
	
?>
