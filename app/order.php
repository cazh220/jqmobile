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
	
	//生成订单
	public function doCreateOrder()
	{
		//print_r($_POST);
		//print_r($_SESSION);die;
		$gift_id_arr = $_POST['gift_id'];
		$gift_num    = $_POST['gift_num'];
		
		importModule("OrderInfo","class");
		$obj_order = new OrderInfo;
		//生成订单号
		$order_no = $obj_order->get_orderno();
		
		importModule("ShopInfo","class");
		$obj_shop = new ShopInfo;
		//订单商品
		$order_goods = array();
		$total_credits = 0;
		foreach($gift_id_arr as $key => $value)
		{
			$gift_detail = $obj_shop->get_gift_detail($value);

			$order_goods[] = array(
				'order_id'	=> 1,
				'gift_id'	=> $value,
				'gift_name'	=> $gift_detail['gift_name'],
				'amount'	=> $gift_num[$key],
				'price'		=> $gift_detail['credits'],
				'gift_pic'	=> $gift_detail['gift_photo'],
			);
			$total_credits += $price*$gift_num[$key];
		}
		
		$user_id = $_SESSION['user_id'];
		$user_name = $_SESSION['username'];
		//生成订单基本信息
		$order = array(
			'order_no'	=>  $order_no,
			'order_status'	=> 0,
			'user_id'		=> $user_id,
			'username'		=> $user_name,
			'address'		=> $_POST['address'],
			'consignee'		=> $_POST['receiver'],
			'mobile'		=> $_POST['mobile'],
			'total_credits'	=> $total_credits,
			'create_time'	=> date('Y-m-d H:i:s', time())
		);
		
		
		$res = $obj_order->create_order($order, $order_goods);
		if($res)
		{
			header('Location: order.php?do=ordersuccess&order_no='.$order_no);
		}
		else
		{
			echo "failed";
		}
	}
	
	//订单成功页
	public function doOrderSuccess()
	{
		$order_no = $_GET['order_no'];
		importModule("OrderInfo","class");
		$obj_order = new OrderInfo;
		$data = $obj_order->get_order_info($order_no);
		
		$info = "";
		foreach($data as $key => $val)
		{
			$info .= $val['gift_name']."，数量".$val['amount']."，";
		}
		
		$order_info = array(
			'create_time'	=> $data[0]['create_time'],
			'order_no'		=> $data[0]['order_no'],
			'total_credits'	=> $data[0]['total_credits'],
			'info'			=> $info,
			'left_credits'	=> $_SESSION['left_credits']
		);
		
		//获取订单信息
		$page = $this->app->page();
		//print_r($_SESSION);
		$page->value('info', $order_info);
		$page->value('user',$_SESSION);
		$page->params['template'] = 'order_success.php';
		$page->output();
	}
	
	public function doMyOrder()
	{
		$user_id = $_SESSION['user_id'];
		importModule("OrderInfo","class");
		$obj_order = new OrderInfo;
		$data = $obj_order->get_my_order($user_id);
		//print_r($data);die;
		$page = $this->app->page();
		$page->value('list', $data);
		$page->value('address',isset($data[0]['address']) ? $data[0]['address'] : '');
		$page->value('consignee',isset($data[0]['consignee']) ? $data[0]['consignee'] : '');
		$page->value('mobile',isset($data[0]['mobile']) ? $data[0]['mobile'] : '');
		$page->value('create_time',isset($data[0]['create_time']) ? $data[0]['create_time'] : '');
		$page->value('send_time',isset($data[0]['send_time']) ? '已发货' : '待发货');
		$page->params['template'] = 'myorder.php';
		$page->output();
	}
	
}
$app->run();
	
?>
