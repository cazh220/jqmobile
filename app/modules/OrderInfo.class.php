<?php
/**
 * 订单处理类
 * 
 * @package     modules
 */
 
class OrderInfo
{
	/**
	 * 应用程序对象
	 * @var Application
	 */
	private $app = null;
	
	/**
	 * 数据库操作对象
	 * @var OrmQuery
	 */
	private $db = null;
	
	/**
     * 构造函数，获取数据库连接对象
     *
     */
    public function __construct(){
        global $app;
        
        $this->app = $app;
        
        $this->db = $app->orm($app->cfg['db'])->query();
		
        mysql_query("set names utf8");
    }
    
    //生成订单
    public function create_order($order=array(), $order_goods=array())
    {
    	if($this->db == null)
		{
    		return false;
    	}	
    	
    	$this->db->exec("START TRANSACTION");
    	$sql = "INSERT INTO hg_order(order_no, user_id, username, address, consignee, mobile, total_credits, create_time)VALUES('".$order['order_no']."','".$order['user_id']."','".$order['username']."','".$order['address']."','".$order['consignee']."','".$order['mobile']."','".$order['totalcredits']."','".$order['create_time']."')";

    	$res = $this->db->exec($sql);
    	$order_id = $this->db->getLastId();
    	    	
    	if(empty($order_id))
    	{
    		$this->db->exec("ROLLBACK");
    		return false;
    	}
    	
    	foreach($order_goods as $key => $val)
    	{
    		$sql = "INSERT INTO hg_order_gift(order_id, gift_id, gift_name, amount, price, gift_pic)VALUES('".$order_id."', '".$val['gift_id']."','".$val['gift_name']."','".$val['amount']."','".$val['price']."', '".$val['gift_pic']."')";
    		//echo $sql;die;
    		$result = $this->db->exec($sql);
    		if(empty($result))
    		{
    			$this->db->exec("ROLLBACK");
    			return false;
    		}
    	}
    	$this->db->exec("COMMIT");
    	
    	return true;
    }
    
	//获取订单信息
	public function get_order_info($order_no)
	{
		if($this->db == null)
		{
    		return false;
    	}
		$sql = "SELECT * FROM hg_order a LEFT JOIN hg_order_gift b ON a.order_id = b.order_id WHERE a.order_no ='".$order_no."'";
		
		$res = $this->db->getArray($sql);
		
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
		}
		return $res;
	}
	
	//我的订单
	public function get_my_order($user_id)
	{
		if($this->db == null)
		{
    		return false;
    	}
		$sql = "SELECT * FROM hg_order a LEFT JOIN hg_order_gift b ON a.order_id = b.order_id WHERE a.user_id = {$user_id}";
		
		$res = $this->db->getArray($sql);
		
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
		}
		return $res;
	}


    //订单号生成
    public function get_orderno()
    {
    	return '10'.date("YmdHis",time()).rand(1000,9999);
    }
	
	
	/**
	 * 数据更新失败记录日志，并标识操作失败
	 *
	 * @param 	Array 	$data
	 * @return 	bool	false
	 */
	private function _log($data){
	    $log = $this->app->log();
	    $log->reset()->setPath("modules/UserInfo")->setData($data)->write();
	    
	    return false;
	}
}
?>