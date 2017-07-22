<?php
/**
 * 商品出库处理
 * 
 * @package modules
 * @author  鲍<chenglin.bao@lyceem.com>
 * @copyright 2010-3-31
 */
class StockOutInfo {
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
        $this->db       = $app->orm($app->cfg['db'])->query();
		
        mysql_query("set names utf8");
    }
    
    /**
     * 获取出库自增id
     * 
     * @return int|bool   成功返回maxId,失败返回false
     */
    public function getStockOutMaxId(){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$sql = "SELECT MAX(stock_out_id) FROM goods_stock_out";
    	$res_id = $this->store_db->getValue($sql);
    	
    	if($res_id === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res_id;
    }
    
    /**
     * 获取出库单号
     * 
     * @return array|bool 成功返回库存id,失败返回false
     */
    public function getStockOutId($s_where = ''){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$sql = "SELECT stock_out_sn FROM goods_stock_out $s_where";
    	$res = $this->store_db->getColumn($sql);
    	
    	if($res=== false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute error : '.$res, date("Y-m-d H:i:s")));
    	}
    	
    	if(!empty($res) && !is_array($res)){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'data struct is error : '.$res, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
    
    /**
     * 获取出库商品数量
     * 
     * @param array $ar_stockid 提货单号
     * @return int|bool     
     */
	public function getStockOut($ar_stockid,$s_do=''){
	    if($this->store_db == null){
	    	return false;
	    }	
	    
	    if(!is_array($ar_stockid)){
	    	$ar_stockid = (array)$ar_stockid;
	    }
	    
	    if(count($ar_stockid) == 0){
	    	return false;
	    }
	    
	    if($s_do == 'detail'){
	    	  $sql = "SELECT * FROM goods_stock_out_details WHERE stock_out_id IN (".join(',',$ar_stockid).")";
	    }else{
	    	$sql = "SELECT * FROM goods_stock_out WHERE stock_out_id IN (".join(',',$ar_stockid).")";
	    }
	    
	    $res = $this->store_db->getArray($sql);
	    
	    if($res === false){
	    	return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'data struct is error : '.$res, date("Y-m-d H:i:s")));
	    }
	    
	    return $res;
	}
    
    /**
     * 出库查询
     * 
     * @param string $s_do 执行的操作
     * @param string $s_where 查询条件
     * @return array|bool 成功返回数组,失败返回false
     */
    public function getStockInInfo($s_where,$s_do='',$i_startnum = 0,$i_pagesize = 20){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$s_where = (string)$s_where;
    	
    	if($s_where == ''){
    		return false;
    	}
    
    	if($s_do == 'stockin'){
    		$sql = "SELECT gso.*,au.user_name FROM goods_stock_out gso,admin_user au ".
	    			"$s_where AND gso.create_user_id = au.user_id ORDER BY gso.stock_out_id DESC";
    	}else if($s_do == 'stockoutin'){
			$sql = "SELECT gso.*,au.user_name FROM goods_stock_out gso,admin_user au ".
	    			"$s_where AND gso.create_user_id = au.user_id ORDER BY gso.stock_out_id DESC";
		}else{
    		$sql = "SELECT gso.* FROM goods_stock_out gso ".
	    			"$s_where  ORDER BY gso.stock_out_id DESC LIMIT $i_startnum, $i_pagesize";
    	}

    	$res_stockout = $this->store_db->getArray($sql);
    	
    	if($res_stockout === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	if(!empty($res_stockout) && !is_array($res_stockout)){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'data struct is error : '.$res_stockout, date("Y-m-d H:i:s")));
    	}
    	
    	return $res_stockout;	
    }
	
    /**
     * 查询出库总数
     * 
     * @param string 查询条件
     * @return int|bool 
     */
    public function getStockTotalNum($s_where){
   		if($this->store_db == null){
    		return false;
    	}
    	
    	$s_where = (string)$s_where;
    	
    	if($s_where == ''){
    		return false;
    	}
    	
    	$sql = "SELECT COUNT(gso.stock_out_id) FROM goods_stock_out gso $s_where"; 
    	$res = $this->store_db->getValue($sql); 
    	//$res = count($res);
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
    
    /**
     * 出库详细查询
     * 
     * @param int $i_stockid    提货申请单号
     * @return	array|bool      成功返回出库商品,失败返回false
     */
    public function getStockOutDetail($i_stockid,$do=''){
    	if($this->store_db == null){
    		return false;
    	}

    	$i_stockid = (int)$i_stockid;
    	
    	if($do == 'sum'){
    		$sql = "SELECT sum(quantity) FROM goods_stock_out_details WHERE stock_out_id = $i_stockid";
    		$res  = $this->store_db->getValue($sql);
    	}elseif($do == 'agency'){
    		$sql = "SELECT distinct(a.agency_name) FROM goods_stock_out_details gsod,agency a WHERE gsod.agency_id = a.agency_id AND stock_out_id = $i_stockid";
    		$res  = $this->store_db->getValue($sql);
    		//$res  = array_unique($res);
    	}elseif($do == 'info'){
    	    $sql = "SELECT * from goods_stock_out where stock_out_id = $i_stockid";
            $res = $this->store_db->getArray($sql);            
    	}else{
    		$sql = "SELECT gsod.*,a.agency_name FROM goods_stock_out_details gsod,agency a WHERE gsod.agency_id = a.agency_id AND gsod.stock_out_id = $i_stockid";
    		$res = $this->store_db->getArray($sql);
    	}
    
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
	
	/**
	 * 查询已出库数量
	 *
	 * @param $s_stockoutsn 出库单号
	 * @param $i_stockouttype 出库类型
	 */
	public function getHasQuantityNum($s_stockoutsn,$i_stockouttype){
		if($this->store_db == null){
    		return false;
    	}
		
		if($s_stockoutsn == '' || !$i_stockouttype){
			return false;
		}
		
		$sql = "SELECT sum(quantity) FROM goods_stock_out_details WHERE stock_out_id in ".
			"(SELECT stock_out_id FROM goods_stock_out WHERE stock_out_type = $i_stockouttype AND stock_out_sn = '$s_stockoutsn' AND confirm_status = 1)";
		//$sql = "SELECT sum(quantity) FROM goods_stock_out_details WHERE stock_out_id = $i_stockoutid AND stock_out_type = $i_stockouttype";echo $sql;die;

		$res = $this->store_db->getValue($sql);
		
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
		}
		
		return $res;
	}
	
	/**
	 * 查询商品尺寸的已出库数量
	 * 
	 * @param $s_stockoutsn 出库单号
	 * @param $i_stockouttype 出库类型
	 */
	public function getGoodsHasQuantity($s_stockoutsn,$i_stockouttype){
		if($this->store_db == null){
    		return false;
    	}
		
		if(!$s_stockoutsn || !$i_stockouttype){
			return false;
		}
		
		$sql = "SELECT * FROM goods_stock_out_details WHERE stock_out_id in ".
			"(SELECT stock_out_id FROM goods_stock_out WHERE stock_out_type = ($i_stockouttype+5) AND stock_out_sn = '$s_stockoutsn' AND confirm_status =1)";

		$res = $this->store_db->getArray($sql);
		
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
		}
		
		$ar_temp = array();
		if(!empty($res) && is_array($res)){
			foreach($res as $r){
				$ar_temp[$r['goods_id']][$r['size']] += $r['quantity'];
			}
		}
		
		return $ar_temp;
	}
    
    /**
     * 添加提货出库申请
     * 
     * @param  array	$ar_stockout 出库提货申请数据
     * @param  string	$s_table     操作数据表
     * @return int|bool 成功返回影响行数,失败返回false
     */
    public function addRequest($ar_stockout,$s_table){
    	if($this->store_db == null){
    		return false;
    	}

    	$s_table = (string)$s_table;
    	
   	if(!is_array($ar_stockout) || count($ar_stockout) < 1){
    		return false;
    	}



  
	if($s_table == 'goods_stock_out'){
    		$key = "(".join(',',array_keys($ar_stockout)).")";
    		$val = "('".join("'".','."'",array_values($ar_stockout))."')";
    	
            //   echo(time()."t\n");
//echo($sql);

    		$sql = "INSERT INTO goods_stock_out $key VALUES $val";
    		$res = $this->store_db->exec($sql);

		

//  echo(time()."t\n");

    		$res = $this->store_db->getLastId();
  //echo(time()."t\n");
		
    	}else if($s_table == 'goods_stock_out_details'){
			
 	

			foreach($ar_stockout as $val){
				$ar_key = array_keys($val);
				$ar_val[] = "('".join("','",array_values($val))."')";
			}
			
			$s_key = "(".join(",",array_values($ar_key)).")";
			$s_val = join(",",array_values($ar_val));
			
    		

		$sql = "INSERT INTO stock.goods_stock_out_details $s_key VALUES $s_val"; 
    		
		//echo(time()."\n");
		$this->store_db->clear();
		$res = $this->store_db->exec($sql);
//	 echo(time()."\n");
		//PRINT_r($sql);exit;
    	}
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute insert is fail:'.$res, date("Y-m-d H:i:s")));
    	}
		/*
		importModule('LogSqs');
		$logsqs=new LogSqs;  	*/
    	return $res;
    }
    
    /**
     * 添加提货出库申请
     * 
     * @param  array	$ar_stockout 出库提货申请数据
     * @param  string	$s_table     操作数据表
     * @return int|bool 成功返回影响行数,失败返回false
     */
    public function addStockOut($ar_stockout){
   		if($this->store_db == null){
    		return false;
   		}
    	
   		if(!is_array($ar_stockout) || count($ar_stockout) < 1){
    		return false;
    	}
    	
    	$sql = "INSERT INTO goods_stock_out (stock_out_sn,batch_id,stock_out_date,stock_out_type,out_person,description,create_user_id,create_time,need_return,return_time)
    			VALUES ".JOIN(',',$ar_stockout);
    	$res = $this->store_db->exec($sql);
    	$res = $this->store_db->getLastId();
   
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute insert is fail:'.$res, date("Y-m-d H:i:s")));
    	}
		importModule('LogSqs');
		$logsqs=new LogSqs;
    	
    	return $res;
    }
    
    /**
     * 提货出库审核
     * 
     * @param int $s_stockoutid 提货申请id
     * @return bool 		
     */
    public function editAudit($s_stockoutid){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$s_stockoutid = (string)$s_stockoutid;
    	
    	if(empty($s_stockoutid)){
    		return false;
    	}
    	
    	$sql = "UPDATE goods_stock_out SET confirm_status = 1,confirm_user_id = " . $_SESSION['user_id'] . ",confirm_time = ".time()." WHERE stock_out_id IN (" . $s_stockoutid . ")";
    	$res = $this->store_db->exec($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute update is fail:'.$res, date("Y-m-d H:i:s")));
    	}
		importModule('LogSqs');
		$logsqs=new LogSqs;
    	
    	return $res;
    }
    
    /**
     * 根据分销商查询提货单号
     */
    public function getStockOutSnByPo($ar_condition) {
    	if($this->store_db == null){
    		return false;
    	}
 
    	if(empty($ar_condition) || !is_array($ar_condition)){
    		return false;
    	}
    	
    	$sql = "SELECT distinct(stock_out_sn),stock_out_id FROM goods_stock_out WHERE stock_out_type > 5 AND ".JOIN('AND', $ar_condition);
    	$res = $this->store_db->getArray($sql);
    
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute update is fail:'.$res, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
    
    /**
     * 出库库存更新
     * 
     * @param string $s_stockoutid
     * @param int     $i_userid;
	 * @param string  $s_field 
     * @return bool
     */
    public function UpdateStock($s_stockoutid,$i_userid,$s_field,$frozen_limit = true) {
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$i_userid = (int)$i_userid;
		$s_field  = (trim((string)$s_field));
    	
   		if(empty($s_stockoutid) || $i_userid < 1 || !$s_field){
    		return '参数不正确！';
    	}

    	$sql = "SELECT * FROM  goods_stock_out WHERE stock_out_id IN (".$s_stockoutid.")  AND stock_out_type >= 5";
    	$res = $this->store_db->getArray($sql);
		
		if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute sql is fail:'.$sql, date("Y-m-d H:i:s")));
    	}

    	if(!$res || count($res) < 1){
    		return '该提货单号不存在！';
    	}
		
		$sql = "SELECT * FROM  goods_stock_out_details WHERE stock_out_id IN (".$s_stockoutid.")";
    	$res_detail = $this->store_db->getArray($sql);
    		
		if($res_detail === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute sql is fail:'.$sql, date("Y-m-d H:i:s")));
    	}
		
		if(!$res_detail || count($res_detail) < 1){
    		return '该提货单号商品不存在！';
    	}
		
    	$ar_data = $ar_temp = array();
/*    	foreach($res_detail as $val){
    		$ar_temp['goods_id'] = $val['goods_id'];
    		$ar_temp['size'] = $val['size'];
			$ar_temp['agency_id'] = $val['agency_id'];
    		$ar_temp['quantity'] = $val['quantity'];
			$ar_temp['field'] = $s_field;
    		$ar_data[] = $ar_temp;
    		unset($ar_temp);
    	}*/
		
		foreach($res_detail as $val){
			$key = $val['agency_id'].'_'.$val['goods_id'].'_'.$val['size'];
			
			$ar_data[$key]['goods_id'] = $val['goods_id'];
    		$ar_data[$key]['size'] = $val['size'];
			$ar_data[$key]['agency_id'] = $val['agency_id'];
			$ar_data[$key]['field'] = $s_field;
			if(array_key_exists($key,$ar_data)) {
				$ar_data[$key]['quantity'] += $val['quantity'];
			} else {
				$ar_data[$key]['quantity'] = $val['quantity'];
			}

			unset($key);
    	}

		$this->success_log(array(date("Y-m-d H:i:s"),__CLASS__ . '.class.php line ' . __LINE__ , ' function '. __FUNCTION__ .' user_id:'.$i_userid.' data:'.serialize($ar_data)));
		foreach ($ar_data as $key=>$val)
		{
			$vp = str_replace('U', '#', $val['size']);
			$ar_data[$key]['size'] = $vp;
		}

		//盘断商品库存
		foreach($ar_data as $val){
			if(empty($val['goods_id']) || empty($val['agency_id']) || empty($val['size'])) {
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'data is not full:'.serialize($val), date("Y-m-d H:i:s")));
			}
			
			$r = $this->findStock($val,'goods_stock');
			
			if($frozen_limit){
			     if(!$r || $r <  $val['quantity']) return '该提货单号商品库存不足,请仔细核查！';
            }
    	}

		//更新库存
    	foreach($ar_data as $val){
			$sql = "UPDATE goods_stock SET $s_field = $s_field -". $val['quantity'] .",quantity = bad_quantity + efficacious_quantity ".
				" WHERE goods_id =". $val['goods_id'] ." AND size ='".$val['size']."' AND agency_id =".$val['agency_id'];
			$res = $this->store_db->exec($sql);
			
			if(!$res) {
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' update stock is fail'.$sql, date("Y-m-d H:i:s")));
			}
    	}
		// importModule('LogSqs');
		// $logsqs=new LogSqs;
// 		
    	return true;
    }
    
	/**
	 * 库存查询
	 * 
	 * @param  array $ar_data
	 * @param  string $s_table 
	 * @return bool
	 */
	public function findStock($ar_data,$s_table){
		if($this->store_db == null){
    		return false;
    	}
		
		$s_table = (string)$s_table;
		
		if(!is_array($ar_data) || count($ar_data) < 1 || $s_table == ''){
			return false;
		}
    	
		$sql = "select sum($ar_data[field]) from $s_table where goods_id =".$ar_data['goods_id']." and size = '".$ar_data['size']."' and agency_id = ". $ar_data['agency_id'];

    	$r = $this->store_db->getValue($sql);

    	if($r && $r > 0){
    		return $r;
    	}
    	
    	return false;
	}
    
    /**
     * 移除提货申请
     * 
     * @param string $s_stockoutid 提货申请id
     * @return int|bool            成功返回影响行数，失败返回false
     */
    public function removeRequest($s_stockoutid){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$s_stockoutid = (string)trim($s_stockoutid);

    	$sql = "UPDATE goods_stock_out SET confirm_status = 2 , confirm_user_id = " . $_SESSION['user_id'] . " , confirm_time = " . time() . " WHERE stock_out_id IN (" . $s_stockoutid . ")";

    	$res = $this->store_db->exec($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute delete is fail:'.$res, date("Y-m-d H:i:s")));
    	}
    	importModule('LogSqs');
		$logsqs=new LogSqs;
    	
    	return $res;
    }
    
    /**
     * 查询出库数
     * 
     * @param string $s_where 查询条件
     * @return array|bool
     */
    public function getStockOutNum($s_where) {
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$s_where = (string)$s_where;
    	
    	if($s_where == ''){
    		return false;
    	}
    	
    	$sql = "SELECT stock_out_id,stock_out_type FROM goods_stock_out $s_where";
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
	
	/**
     * 查询未出库提货单
     * 
     * @param void 
     * @return array|bool
     */
	public function getNoStockOut() {
		if($this->store_db == null){
    		return false;
    	}
		
		//待出库订单
    	$sql = "SELECT stock_out_sn FROM goods_stock_out WHERE stock_out_type > 5 AND confirm_status IN(0,1)";
    	$r_stockoutsn = $this->store_db->getColumn($sql);
    
    	if($r_stockoutsn === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.mysql_error(), date("Y-m-d H:i:s")));
    	}

    	$sql = "SELECT stock_out_type,COUNT(stock_out_id) num FROM goods_stock_out WHERE stock_out_type <= 5 AND confirm_status = 1";
    	       	
    	if(!empty($r_stockoutsn) && is_array($r_stockoutsn)) {
    		$r_stockoutsn = array_unique($r_stockoutsn);
    		$sql .= " AND stock_out_sn NOT IN ('".join("','",$r_stockoutsn)."')";	
    	}
    	$sql .= " GROUP BY stock_out_type";
		$res = $this->store_db->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
		
		if(empty($res) || !is_array($res)) return array();
		
		$ar_res = array();
		
		foreach($res as $v) {
			$ar_res[$v['stock_out_type']] = $v['num'];
		}
    	
    	return $ar_res;
	}
	
	
	/**
	 * 获取出库提货人id
	 *
	 * @param string $s_stockoutsn 
	 * @param int    $i_type
	 * @return 
	 */
	public function getStockOutInfo($s_stockoutsn, $i_type) {
		if($this->store_db == null){
    		return false;
    	}
    	
    	$s_stockoutsn = (string)$s_stockoutsn;
		$i_type       = (int)$i_type;
    	
    	if($s_stockoutsn == '' || $i_type < 1){
    		return false;
    	}
		
		$sql = "select * from goods_stock_out where stock_out_sn = '$s_stockoutsn' and stock_out_type = $i_type";
		$res = $this->store_db->getRow($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
	}
	/**
     * 
     * 
     * confirmProfitAndLoss
     */
    public function confirmProfitAndLoss($s_stockoutid){
        if($this->store_db == null){
            return false;
        }
        
        $s_stockoutid = (string)$s_stockoutid;
        
        $ar_stockoutid = split(',', $s_stockoutid);
        foreach ($ar_stockoutid as $s_stockoutid) {

            $sql = "insert into goods_stock_out (stock_out_type, stock_out_sn, batch_id, stock_out_date, "
            ."update_user_id, update_time, confirm_user_id, create_time, "
            ."confirm_time, need_return, create_user_id, confirm_status)"
            ." (select 7,stock_out_sn, batch_id, stock_out_date, update_user_id, update_time, confirm_user_id, "
            ."create_time, confirm_time, need_return, create_user_id, confirm_status from goods_stock_out where stock_out_id in ($s_stockoutid))";
            
            $res = $this->store_db->exec($sql);
            if(!$res)$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
            
            $i_lastid = $this->store_db->getLastId();

            if($i_lastid){
                $ar_lastid[] =$i_lastid;
                $sql = "insert into goods_stock_out_details (stock_out_id,agency_id,batch_id,goods_id,size,goods_price,quantity)"
                ." (select $i_lastid,agency_id,batch_id,goods_id,size,goods_price,quantity from "
                ." goods_stock_out_details where stock_out_id in ($s_stockoutid))";
                $res = $this->store_db->exec($sql);

                if(!$res)$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
            
            }
        }
        if(!empty($ar_lastid) && is_array($ar_lastid))return implode($ar_lastid, ',');
        return false;
    }
    
    
    /**
     * 查询盘盈盘亏记录（出入库记录）
     */
    public function GetShortage_over($ar_param)
    {  
    	if($this->store_db == null) return false;
    	$sql .= "SELECT gso.stock_out_sn,gso.create_user_id,gso.create_time,gsod.agency_id,gsod.goods_id,gsod.size,gsod.goods_price,gsod.quantity 
    	FROM goods_stock_out gso LEFT JOIN goods_stock_out_details gsod ON gso.stock_out_id = gsod.stock_out_id ";
    	if($ar_param['starttime'] && $ar_param['endtime'])
    	{
    		$sql .= " WHERE gso.create_time < '".$ar_param['endtime']."' AND gso.create_time > '".$ar_param['starttime']."' "; 
    	}
    	$sql .= " AND gso.stock_out_type =4 AND gso.confirm_status = 1 ORDER BY gso.create_time DESC";

    	$res = $this->store_db->getArray($sql);
    	
    	if(!res) $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	
    	return $res;
    	
    }
    
    /**
     * 获取商品详细信息
     */
    public function getGoodsDetail($goods_id,$size)
    {  
    	if($this->db == null)  return false;
    	$sql = "SELECT color FROM ".$this->app->cfg['prefix']."goods_unique WHERE goods_id = $goods_id AND size = '".$size."'";
    	
    	$res = $this->db->getValue($sql);
    	if(!$res )  $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	
    	return $res;
    }
    
    /**
     * 获取商品名称
     */
    public function getGoods_name($goods_id)
    {
    	if($this->db == null)  return false;
    	$sql = "SELECT goods_name,goods_sn FROM ".$this->app->cfg['prefix']."goods WHERE goods_id = $goods_id";
    	
    	$res = $this->db->getArray($sql);
    	if(!$res )  $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	return $res[0];
    }
    
    /**
     * 获取仓库
     */
    public function getAgencyInfo($agency_id)
    {
    	if($this->store_db == null)  return false;
    	$sql = "SELECT agency_name FROM agency WHERE agency_id = $agency_id";

    	$res =  $this->store_db->getValue($sql);
    	if(!$res )  $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	
    	return $res;
    }
    
    /**
     * 获取管理员信息
     */
    public function getAdmin_user($user_id)
    {
    	if($this->store_db == null)  return false;
    	$sql = "SELECT user_name FROM admin_user WHERE user_id = $user_id";
 
    	$res = $this->store_db->getValue($sql);
    	if(!$res )  $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	
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
	    $log->reset()->setPath("success/StockOutInfo")->setData($data)->write();
	}
	
	/**
	 * 数据更新失败记录日志，并标识操作失败
	 *
	 * @param 	Array 	$data
	 * @return 	bool	false
	 */
	private function _log($data){
	    $log = $this->app->log();
	    $log->reset()->setPath("modules/StockOutInfo")->setData($data)->write();
	    
	    return false;
	}
}
?>
