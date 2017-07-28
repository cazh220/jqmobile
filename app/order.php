<?php 
/**
 * 订单处理类

 */
require_once('./common.inc.php');

class order extends Action {
	
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
	
	//订单确认
	public function doOrderConfirm()
	{
		$id = $_GET['id'];
		$id_arr = explode(',', $id);
		//获取商品信息
		$goods_list = array();
		importModule("ShopInfo","class");
		$obj_shop = new ShopInfo;
		$products = $obj_shop->get_product_list($id);

		$page = $this->app->page();
		$page->value('list', $products);
		$page->value('user',$_SESSION);
		$page->params['template'] = 'order_detail.php';
		$page->output();
	}
	
}
$app->run();
	
?>
