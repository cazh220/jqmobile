<?php 

class QualityInfo {

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
    }
	
	/**
	 * 转换信息存入 stock_quality_control 表中
	 */
	public function insertQualityControl($ar_goodsinfo){
		if($this->store_db == null)
			return false;

		//设置参数
		$key = "(".join(',',array_keys($ar_goodsinfo)).")";
    	$val = "('".join("'".','."'",array_values($ar_goodsinfo))."')";
		
		$sql = "INSERT into stock_quality_control $key values $val";

		$res = $this->store_db->exec($sql);
		if($res) return false;
		return true;
	}
	
	/**
	 * 从 stock_quality_control 表中查询数据
	 * 显示
	 * $s_where  查询条件
	 */
	public function getQualityControlInfo($s_where){
		if($this->store_db == null){
    		return false;
    	}  	
    	$s_where = (string)$s_where;
    	
    	if($s_where == ''){
    		return false;
    	}
		
		$sql = "SELECT quality_control_id,type,goods_sn,create_user_id,create_time,goods_name,color,size,".
				"confirm_status,confirm_user_id,confirm_time,bad_quantity,efficacious_quantity FROM stock_quality_control ".
				"$s_where ORDER BY quality_control_id DESC";
		$res_convert = $this->store_db->getArray($sql);
		
		if($res_convert === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	if(!empty($res_convert) && !is_array($res_convert)){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'data struct is error : '.$res_convert, date("Y-m-d H:i:s")));
    	}
    	
    	return $res_convert;
	}
	
	/**
     * 转换申请审核
     * 
     * @param int $s_qualitycontrolid 转换申请id
     * @return bool 		
     */
    public function editVertify($s_qualitycontrolid){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$s_qualitycontrolid = (string)$s_qualitycontrolid;
    	
    	if(empty($s_qualitycontrolid)){
    		return false;
    	}
//" . $_SESSION['user_id'] . "    	
    	$sql = "UPDATE stock_quality_control SET confirm_status = 1,confirm_user_id = 53 ,confirm_time = ".time()." WHERE quality_control_id IN (" . $s_qualitycontrolid . ")";
    	$res = $this->store_db->exec($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute update is fail:'.$res, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
	
	/**
	 * 审核成功，更新库存
	 * $ar_new  更新信息数组
	 */
	public function UpdateStock($ar_new=array()){
		if($this->store_db == null){
    		return false;
    	}
		if($this->findStock($ar_new,'goods_stock')){
			$sql = "UPDATE goods_stock SET bad_quantity =$ar_new[bad] +  bad_quantity,".
				"efficacious_quantity = efficacious_quantity + $ar_new[good],quantity =".
				" bad_quantity + efficacious_quantity where goods_id = $ar_new[goods_id] ".
				" AND size ='".$ar_new[size]."' AND agency_id = 1";
			
			$res = $this->store_db->exec($sql);

		if(!$res) {
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' update stock is fail'.$sql, date("Y-m-d H:i:s")));
		}
		}else{
			$sql = "insert into goods_stock (goods_id,size,color,agency_id,quantity,bad_quantity,efficacious_quantity) 
								values ($ar_new[goods_id],'$ar_new[size]','$ar_new[color]',1,$ar_new[good]+$ar_new[bad],$ar_new[bad],$ar_new[good])";
			$res1 = $this->store_db->exec($sql);
				
  			if($res1 === false) {
  				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'add stock is fail'.$sql, date("Y-m-d H:i:s")));
  			}
		}	
		return true;
	}
	
	/**
	 * 库存查询
	 */
	public function findStock($ar_data,$s_table){
		if($this->store_db == null){
    		return false;
    	}
		
		$s_table = (string)$s_table;
		if(!is_array($ar_data) || count($ar_data) < 1 || $s_table == ''){
			return false;
		}
    	
		$sql = "select count(*) from $s_table where goods_id =".$ar_data['goods_id']." and size = '".$ar_data['size']."' and agency_id = 1";	
    	$r = $this->store_db->getValue($sql);

    	if($r && $r > 0){
    		return true;
    	}
    	
    	return false;
	}
	/**
	 * 查询对应商品现有库存
	 * 
	 */
	 public function SearchStock($arr){
	 	if($this->store_db == null){
    		return false;
    	}	
		$sql = "select efficacious_quantity,bad_quantity from goods_stock where goods_id =".$arr['goods_id']." and size = '".$arr['size']."' and agency_id = 1";
    	$r = $this->store_db->getArray($sql);

    	if($r === false) {
  			$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'add search stock is fail'.$sql, date("Y-m-d H:i:s")));
  		}

    	return $r;
	 }
	 
	  /**
     * 移除转换申请
     * 
     * @param string $s_qualitycontrolid 表中记录id
     * @return int|bool            成功返回影响行数，失败返回false
     */
    public function removeRequest($s_qualitycontrolid){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$s_qualitycontrolid = (string)trim($s_qualitycontrolid);
//. $_SESSION['user_id'] .
    	$sql = "UPDATE stock_quality_control SET confirm_status = 2 , confirm_user_id = 53  , confirm_time = " . time() . " WHERE quality_control_id IN (" . $s_qualitycontrolid . ")";
    	$res = $this->store_db->exec($sql);
		
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute delete is fail:'.$res, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }

	/**
	 * 数据更新失败记录日志，并标识操作失败
	 *
	 * @param 	Array 	$data
	 * @return 	bool	false
	 */
	private function _log($data){
	    $log = $this->app->log();
	    $log->reset()->setPath("modules/QualityInfo")->setData($data)->write();
	    
	    return false;
	}
	
}
?>