<?php
/**
 * 库存数据处理类
 * 
 * @package modules
 * @author  Jerry.G
 * @copyright 2010-6-30
 */


class StockLockInfo {
	/**
	 * 应用程序对象
	 * @var Application
	 */
	private $app = null;
	
	/**
	 * 数据库操作对象
	 * @var OrmQuery
	 */
	private $store_db = null;
	
	private $db = null;
	
	private $union = null;
	
 	/**
     * 构造函数，获取数据库连接对象
     *
     */
    public function __construct(){
        global $app;
		
        $this->app = $app;
		
        $this->store_db = $app->orm($app->cfg['store_db'])->query();
		
        mysql_query("set names utf8");
		
		$this->db = $app->orm($app->cfg['db'])->query();
		
        mysql_query("set names utf8");
		
		$this->union = $app->orm($app->cfg['union'])->query();
		
        mysql_query("set names utf8");
    }
	/**
	 * 查询进销存被占库存
	 */
	public function getStockInfo($goods_id){
		$sql = "SELECT stock_out_sn FROM goods_stock_out g, goods_stock_out_details gd WHERE gd.stock_out_id = g.stock_out_id ".
                "AND g.confirm_status IN (0,1) AND g.`stock_out_type` >= 6 AND gd.goods_id=$goods_id";
		$res = $this->store_db->getColumn($sql);
        
		if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
		
		$sql =  "SELECT * FROM goods_stock_out as gso inner join goods_stock_out_details as gsod on gsod.stock_out_id = gso.stock_out_id ".
		        "AND gso.confirm_status IN (0,1) AND gso.`stock_out_type` < 6  AND gsod.goods_id=$goods_id";
		
        
        
		if(!empty($res) && is_array($res)) {
			$res = array_unique($res);
			$sql .= " AND gso.stock_out_sn NOT IN (".JOIN(',',$res).")";
		}
        

		$res = $this->store_db->getArray($sql);
		if($res == false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	return $res;
		
	}
	
	/**
	 * 查询进销存所有被占库存列表
	 */
	private function getStockInfoList(){
		$sql = "SELECT stock_out_sn FROM goods_stock_out g, goods_stock_out_details gd WHERE gd.stock_out_id = g.stock_out_id ".
                "AND g.confirm_status IN (0,1) AND g.`stock_out_type` >= 6";
		$res = $this->store_db->getColumn($sql);
		
		if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
		
		$sql =  "SELECT goods_id,quantity FROM goods_stock_out as gso inner join goods_stock_out_details as gsod on gsod.stock_out_id = gso.stock_out_id ".
		        "AND gso.confirm_status IN (0,1) AND gso.`stock_out_type` < 6";
		
		if(!empty($res) && is_array($res)) {
			$res = array_unique($res);
			$sql .= " AND gso.stock_out_sn NOT IN (".JOIN(',',$res).")";
		}
		$res = $this->store_db->getArray($sql);
		if($res == false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	return $res;
		
	}
	
	/**
	 * 查询分销系统订单库存
	 */
	public function getDistributionInfo($goods_id){
		
		$sql = "SELECT order_sn,consignee,goods_number,add_time,partner_id,order_status,shipping_status,pay_status,print_status FROM order_info as oi inner join order_goods as og on og.order_id = oi.order_id ".
			   " WHERE oi.order_status IN (0,1) AND oi.shipping_status IN (0,3)".
			   " AND og.goods_id=$goods_id";
		$res=$this->union->getArray($sql);
		
		if($res == false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
		return $res;
	}
	
	/**
	 * 查询所有分销系统订单库存
	 */
	private function getDistributionInfoList(){
		
		$sql = "SELECT goods_id,goods_number FROM order_info as oi inner join order_goods as og on og.order_id = oi.order_id ".
			   " WHERE og.extension_code = '' AND oi.order_status IN (0,1) AND oi.shipping_status IN (0,3)";
		$res=$this->union->getArray($sql);
		if($res == false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
		return $res;
	}
	/**
	 * 获取占用库存的goods_id
	 */
	public function getStockLockGoodsId(){
		$ar_list=array();
		$res=$this->getStockInfoList();
		foreach($res as $val){
			$ar_list[$val['goods_id']]+=$val['quantity'];
		}
		$res=$this->getDistributionInfoList();
		foreach($res as $val){
			$ar_list[$val['goods_id']]+=$val['goods_number'];
		}
		arsort($ar_list);
		$res=array();
		foreach($ar_list as $key=>$val){
			$res[]=$key;
		}
		
		return $res;
	}
    
    
    /**
     * 
     */
    public function getStockLockByStockSn($stock_out_sn){
        
        if(!$stock_out_sn)return false;
        
        $sql = "select g.stock_out_id as stock_out_id,stock_out_sn,stock_out_type,confirm_status,stock_out_details_id,goods_id,size,quantity from goods_stock_out as g"
            ." inner join goods_stock_out_details as gd on g.stock_out_id = gd.stock_out_id where g.stock_out_sn = '$stock_out_sn'";
        
        $res = $this->store_db->getArray($sql);
        if(!empty($res) && is_array($res)){
            $ar_tmp = array();
            foreach ($res as $key => $value) {
                if($value['stock_out_type'] == 7){
                    $ar_tmp[] = $value;
                    unset($res[$key]);
                }
                    
            }
            if(!empty($ar_tmp) && is_array($ar_tmp)){
                foreach ($res as $key => $value) {
                    foreach ($ar_tmp as $val) {
                        
                        if(($val['goods_id'] == $value['goods_id']) && ($val['size'] == $value['size'])){
                            unset($res[$key]);
                        }
                                                    
                    }
                    
                }
            }
            return $res;
        }
        return false;
    }
    /**
     * 释放
     */
    public function releaseStockLock($stock_out_sn, $stock_out_details_id){
        
        
        if(!$stock_out_details_id)return false;
        
        $sql = "select * from goods_stock_out as gso inner join goods_stock_out_details as gsod on gso.stock_out_id = gsod.stock_out_id "
            ."where gso.stock_out_sn = '$stock_out_sn' and gsod.stock_out_details_id = '$stock_out_details_id' and gsod.quantity != 0";
            
        
        $res = $this->store_db->getArray($sql);
        
        if(!$res){
            return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
        }
        $sql = "update goods_stock_out_details set quantity = 0 where stock_out_details_id='$stock_out_details_id'";
        
        $res = $this->store_db->exec($sql);

        return $res;
    }
    /**
     * 释放
     */
    public function releaseStockLockByDetailsIds($ar_detials_id){
        
        
        if(!$ar_detials_id || !is_array($ar_detials_id))return false;
        $detials_id = join(',', $ar_detials_id);
        $sql = "update goods_stock_out_details set quantity = 0 where stock_out_details_id in ($detials_id)";
        $res = $this->store_db->exec($sql);
        $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql: '.$sql, date("Y-m-d H:i:s")));
        return $res;
    }
    
    /**
     * 查仓库库存
     */
    public function SearchAgency_stock()
    {
    	if($this->store_db == null)  return false;
    	$sql = "SELECT goods_name,goods_sn,size,color,agency_name,efficacious_quantity FROM goods_stock gs,lyceem.ecs_goods eg,agency ag WHERE gs.goods_id = eg.goods_id AND ag.agency_id = gs.agency_id ORDER BY eg.goods_id";
    	$res = $this->store_db->getArray($sql);
    	if(!$res){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
        }
        return $res;
	
    }
    
	
    /**
	 * 记录库存操作日志
	 *
	 * @param 	Array 	$data
	 * @return 	bool	false
	 */
	private function success_log($data){
	    $log = $this->app->log();
	    $log->reset()->setPath("success/StockLockInfo")->setData($data)->write();
	}
	
	/**
	 * 数据更新失败记录日志，并标识操作失败
	 *
	 * @param 	Array 	$data
	 * @return 	bool	false
	 */
	private function _log($data){
	    $log = $this->app->log();
	    $log->reset()->setPath("modules/StockLockInfo")->setData($data)->write();
	    
	    return false;
	}
}
?>	