<?php 
/**
 * 系统信息检测类
 * 
 * @package modules
 * @author  chenglin.bao@lyceem.com
 * @copyright 1.0
 * 
 * $Id: CheckInfo.class.php 2553 2011-03-24 01:26:56Z bao $
 */
class CheckInfo {

	/**
	 * 应用程序对象
	 * @var Application
	 */
	private $app = null;
	
	/**
	 * 数据库操作对象
	 * @var OrmQuery
	 */
	private $stock = null;
	
	
 	/**
     * 构造函数，获取数据库连接对象
     *
     */
    public function __construct(){
        global $app;
		
        $this->app = $app;
		
        $this->stock = $app->orm($app->cfg['store_db'])->query();
		
        mysql_query("set names utf8");
    }
    
    /**
     * 新订单和新支付订单信息
     * 
     * @param void
     * @return array|bool
     */
    public function getCheckOrder() {
  		if($this->stock == null) return false;
  		
  		//提货新单
    	$sql = "SELECT COUNT(stock_out_id) FROM goods_stock_out WHERE stock_out_type <= 5 AND confirm_status = 0 AND create_time >= ".$_SESSION['last_check'];
    	$res = $this->stock->getValue($sql);
    
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.mysql_error(), date("Y-m-d H:i:s")));
    	}
    	
    	$ar_res['new_orders'] = (int)$res; 
    	
    	//待出库订单
    	$sql = "SELECT stock_out_sn FROM goods_stock_out WHERE stock_out_type > 5 AND confirm_status IN(0,1) AND create_time >= ".$_SESSION['last_check'];
    	$r_stockoutsn = $this->stock->getColumn($sql);
    
    	if($r_stockoutsn === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.mysql_error(), date("Y-m-d H:i:s")));
    	}

    	$sql = "SELECT COUNT(stock_out_id) FROM goods_stock_out WHERE stock_out_type <= 5 AND confirm_status = 1 AND create_time >= ".$_SESSION['last_check'];
    	       	
    	if(!empty($r_stockoutsn) && is_array($r_stockoutsn)) {
    		$sql .= " AND stock_out_sn NOT IN ('".join("','",$r_stockoutsn)."')";	
    	}
   		$res = $this->stock->getValue($sql);
	    	
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.mysql_error(), date("Y-m-d H:i:s")));
    	}
    	
    	$ar_res['out_orders'] = (int)$res; 
    	
    	//带出库确认订单
    	$sql = "SELECT COUNT(stock_out_id) FROM goods_stock_out WHERE stock_out_type > 5 AND confirm_status = 0 AND create_time >= ".$_SESSION['last_check'];
   		$res = $this->stock->getValue($sql);
    	
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.mysql_error(), date("Y-m-d H:i:s")));
    	}
    	
    	$ar_res['out_orders_ok'] = (int)$res; 
    	
    	return $ar_res;
    }
    
	/**
	 * 数据更新失败记录日志，并标识操作失败
	 *
	 * @param 	Array 	$data
	 * @return 	bool	false
	 */
	private function _log($data){
	    $log = $this->app->log();
	    $log->reset()->setPath("modules/CheckInfo")->setData($data)->write();
	    
	    return false;
	}
	
}
?>