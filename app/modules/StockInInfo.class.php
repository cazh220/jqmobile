<?php 
/**
 * 商品入库处理
 * 
 * @package modules
 * @author  鲍<chenglin.bao@lyceem.com>
 * @copyright 2010-3-24
 */

class StockInInfo {
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
     * 获取入库商品
     * 
     * @param $ar_stockinid 入库id
     * @return
     */
	public function getStockInGoods($ar_stockinid){
		if($this->store_db == null){
			return false;
		}
		
		if(empty($ar_stockinid) || !is_array($ar_stockinid)){
			return false;
		}
		
		$sql = "SELECT * FROM goods_stock_in_details WHERE stock_in_id IN (".JOIN(',',$ar_stockinid).")";
		$res = $this->store_db->getArray($sql);
		
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
		}
		
		$ar_res = array();
		if(!empty($res) && is_array($res)){
			foreach($res as $r){
				$ar_res[$r['stock_in_id']][] =$r;	
			}
		}
		
		return $ar_res;

    }
    
	/**
     * 查询入库商品
     * 
     * @param string $s_where 查询条件
	 * @param string $s_act   执行任务
     * @return array|bool   成功返回数组，失败返回false
     */
   public function getStockInList($s_where,$i_startnum = 0,$i_pagesize = 20, $s_act = ''){
	    if($this->store_db == null){
    		return false;
    	}

    	if($s_where == ''){
    		return false;
    	}
    	
    	$s_where = (string)$s_where;
		
		if($s_act == 'stockoutin') { 
			$sql = "SELECT gsi.*,gso.stock_out_sn,gso.out_person FROM goods_stock_in gsi,goods_stock_out gso".
				   "$s_where AND gsi.old_order_id = gso.stock_out_id ORDER BY stock_in_id DESC LIMIT $i_startnum,$i_pagesize"; 
		}else if($s_act == 'tuihuo') {
			$sql = "SELECT gsi.* FROM goods_stock_in gsi".
				   "$s_where ORDER BY stock_in_id DESC LIMIT $i_startnum,$i_pagesize"; 
				   //echo $sql;
		} else {
			$sql = "SELECT gsi.*,b.batch_code,s.supplier_name FROM goods_stock_in gsi,batch b,suppliers s ".
				   "$s_where AND b.batch_id = gsi.batch_id AND b.supplier_id = s.supplier_id ".
				   "ORDER BY stock_in_id DESC LIMIT $i_startnum,$i_pagesize"; 
		} 
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false || !is_array($res)){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
    
    /**
     * 获取入库的总数量
     * 
     * @param string $s_where 查询条件
     * @param int|bool
     */
    public function getStockInTotal($s_where,$supplier){
    	if($this->store_db == null){
    		return false;
    	}

    	if($s_where == ''){
    		return false;
    	}
    	
    	$s_where = (string)$s_where;
    	if($supplier != 0)
    	{
    		$sql="SELECT COUNT(gsi.stock_in_id) FROM goods_stock_in gsi , suppliers s ".$s_where;
    	}
    	else 
    	{
    		$sql="SELECT COUNT(gsi.stock_in_id) FROM goods_stock_in gsi".$s_where;
    	}
    	
//    	$sql = "SELECT COUNT(gsi.stock_in_id) FROM goods_stock_in gsi $s_where";
		//$sql = "SELECT COUNT(gsi.stock_in_id) FROM goods_stock_in gsi,batch b,suppliers s $s_where "."AND b.batch_id = gsi.batch_id AND b.supplier_id = s.supplier_id";
					//echo $sql;
//		$sql = "SELECT COUNT(gsi.stock_in_id) FROM goods_stock_in gsi  $s_where";
//echo $sql;exit;
    	$res = $this->store_db->getValue($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
       
    /**
     * 查询入库商品(socket)
     * 
     * @param string $s_where 查询条件
     * @return array|bool   成功返回数组，失败返回false
     */
   public function getStockInInfo($s_where,$i_startnum = 0,$i_pagesize = 30){
	    if($this->store_db == null){
    		return false;
    	}

    	if($s_where == ''){
    		return false;
    	}
    	
    	$s_where = (string)$s_where;
 	
     	$sql = "SELECT i.*,sum(d.quantity) as quantity FROM goods_stock_in as i left join goods_stock_in_details as d on i.stock_in_id=d.stock_in_id ".
     		$s_where." group by i.stock_in_id ORDER BY stock_in_id DESC LIMIT $i_startnum,$i_pagesize";

    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false || !is_array($res)){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
	
    /**
     * 入库详细查询
     * 
     * @param int  $i_storageid  入库id
     * @return array|bool        成功返回数组,失败返回false
     */
    public function getStockInDetail($i_stockid,$do=''){
    	if($this->store_db == null){
    		return false;
    	}

    	$i_stockid = (int)$i_stockid;
    	
    	if($i_stockid == 0){
    		return false;
    	}
    	
    	if($do == 'sum'){
    		$sql = "SELECT sum(quantity) FROM goods_stock_in_details WHERE stock_in_id = $i_stockid";
    		$res  = $this->store_db->getValue($sql);
    	}else{
    		$sql = "SELECT gsi.batch_id,gsid.size,gsid.agency_id,gsid.quantity,gsid.goods_id,gsi.old_order_id FROM goods_stock_in gsi,goods_stock_in_details gsid WHERE gsi.stock_in_id = gsid.stock_in_id AND gsi.stock_in_id = $i_stockid";
    		$res = $this->store_db->getArray($sql);
    	}
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
    
    /**
     * 入库审核查询
     * 
     * @param array $ar_stockinid 入库单id
     * @return array|bool
     */
    public function getInStockAudit($ar_stockinid) {
   		if($this->store_db == null){
    		return false;
    	}
    	
    	if(empty($ar_stockinid) || !is_array($ar_stockinid)) {
    		return false;
    	}
    	
    	$sql = "SELECT stock_in_id,stock_in_status FROM goods_stock_in WHERE stock_in_id IN (".JOIN(',',$ar_stockinid).")";
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    	
    }
	
	/**
	 * 入库分析统计
	 *
	 * @param array $ar_stockid
	 * @return 
	 */
	public function getStockInStatists($ar_stockid) {
		if($this->store_db == null){
    		return false;
    	}
		
		if(!is_array($ar_stockid) || count($ar_stockid) < 1){
    		return false;
    	}
		
		$sql = "SELECT gsid.stock_in_id,gsi.batch_id,gsid.goods_id,gsid.quantity,gsid.size,gsi.create_user_id,gsi.confirm_user_id,gsi.stock_in_time,gsi.description ".
				"FROM goods_stock_in gsi,goods_stock_in_details gsid WHERE gsid.stock_in_id IN (".join(",",$ar_stockid).") AND gsi.stock_in_id = gsid.stock_in_id";
		$res = $this->store_db->getArray($sql);
		
		if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute sql is fail:'.mysql_error(), date("Y-m-d H:i:s")));
    	}
		
		$ar_batchprice = $ar_batchcode = $ar_supplierid = array();
		
		if(!empty($res) && is_array($res)) {
			$ar_batchid = array();
			
			foreach($res as $val) {
				$ar_batchid[] = $val['batch_id'];
			}
			
			$sql = "SELECT bd.batch_id,bd.goods_id,bd.cost_price,b.supplier_id,b.batch_code FROM batch b,batch_details bd ".
					"WHERE b.batch_id = bd.batch_id AND bd.batch_id IN (".join(",",$ar_batchid).")";
			$res_batch = $this->store_db->getArray($sql);
			
			if($res_batch === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute sql is fail:'.mysql_error(), date("Y-m-d H:i:s")));
			}
			
			if(!empty($res_batch) && is_array($res_batch)) {
				foreach($res_batch as $r) {
					$ar_supplierid[$r['batch_id']] = $r['supplier_id'];
					$ar_batchprice[$r['batch_id']][$r['goods_id']][] = $r['cost_price'];
					$ar_batchcode[$r['batch_id']]  = $r['batch_code'];
				}
				
				foreach($ar_batchprice as $key=>$val) {
					foreach($val as $k=>$v) {
						$ar_batchprice[$key][$k] = number_format(array_sum($v)/count($v),2,'.','');
					}
				}
			}
		}

		$ar_res = array();

		if(!empty($res) && is_array($res)) {

			foreach($res as &$val) {
				$val['batch_code']  = $ar_batchcode[$val['batch_id']];
				$val['supplier_id'] = $ar_supplierid[$val['batch_id']];
				$val['cost_price']  = $ar_batchprice[$val['batch_id']][$val['goods_id']];
				$val['sum_price']   = number_format($val['cost_price'] * $val['quantity'],2,'.','');
				$ar_res[$val['stock_in_id']][] = $val;
			}
		}

		return $ar_res;
	}
	
    
    /**
     * 添加入库
     * 
     * @param  array $ar_stockin 入库信息
     * @param  string	$s_table 指定操作数据表
     * @return int|bool			  成功返回影响行数,失败返回false
     */
    public function addStockIn($ar_stockin,$s_table,$s_isbad=0){
    	if($this->store_db == null){
    		return false;
    	}
    	
   		if(!is_array($ar_stockin) || count($ar_stockin) < 1){
    		return false;
    	}
    	
    	$s_table = (string)$s_table;
    	
    	if($s_table == 'goods_stock_in'){
    		if($s_isbad){
    			$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute insert is fail:'.serialize($ar_stockin), date("Y-m-d H:i:s")));
    			$sql = "INSERT INTO goods_stock_in (batch_id,description,stock_in_type,stock_in_time,create_user_id,create_time,old_order_id,handle_method) ".
    			"VALUES ".JOIN(',',$ar_stockin);
    		}
    		else $sql = "INSERT INTO goods_stock_in (batch_id,description,stock_in_type,stock_in_time,create_user_id,create_time,old_order_id) ".
    				"VALUES ".JOIN(',',$ar_stockin);
    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute insert is fail:'.$sql, date("Y-m-d H:i:s")));
    		$res = $this->store_db->exec($sql);
    		$res = $this->store_db->getLastId();
    	}else if($s_table == 'goods_stock_in_details'){
	    	$sql = "INSERT INTO goods_stock_in_details (stock_in_id,agency_id,goods_id,quantity,size) VALUES ".JOIN(',',$ar_stockin);
	    	$res = $this->store_db->exec($sql);
    	}	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute insert is fail:'.mysql_error(), date("Y-m-d H:i:s")));
    	}
    	importModule('LogSqs');
		$logsqs=new LogSqs;
    	return $res;
    }
    
    /**
     * 添加退货入库
     * 
     * @param array  $arr 商品信息数组
     * @return 
     */
    public function addTHStcokIn($arr) {
   		if($this->store_db == null){
    		return false;
    	}
    	
    	if(empty($arr) || !is_array($arr)){
    		return false;
    	}
    	
    	if(empty($arr[0]) && empty($arr[1])){
    		return false;
    	}
    	
    	$res = $this->store_db->addTable('goods_stock_in')->insert($arr[0]);
    	$res_id = $this->store_db->getLastId();
    	
    	if(!$res || !$res_id){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute insert is fail:'.$res, date("Y-m-d H:i:s")));
    	}
    	
    	$sql = "INSERT INTO goods_stock_in_details (stock_in_id,".join(',',array_keys($arr[1])).") VALUES ($res_id,'".JOIN("','",$arr[1])."')";
    	$res = $this->store_db->exec($sql);
    	
    	if(!$res){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute insert is fail:'.$res, date("Y-m-d H:i:s")));
    	}
		importModule('LogSqs');
		$logsqs=new LogSqs;
    	
    	return true;
    }
	
	/**
	 * 获取入库商品的颜色
	 *
	 * @param int $goodsid
	 * @return 
	 */
	 public function getGoodsColor($goodsid){
	   	if($this->db == null){
    		return false;
    	}
		
		$goodsid = (int)$goodsid;
		
		if($goodsid == 0){
			return false;	
		}
		
		$sql = "select color from ecs_goods_unique where goods_id = $goodsid";
		$r = $this->db->getValue($sql);
		
		if($r === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute insert is fail:'.$res, date("Y-m-d H:i:s")));
    	}
		
		return $r;	
	 } 

    
	/**
	 * 将入库的商品添加到库存表中
	 */
	public function addToStock($ar_id,$i_userid){
		if($this->store_db == null){
    		return false;
    	}
    	
   		if(!is_array($ar_id) || count($ar_id) < 1){
    		return false;
    	}
   	
    	$sql="SELECT gsid.* FROM  goods_stock_in_details as gsid inner join goods_stock_in as gsi"
    	." on gsi.stock_in_id=gsid.stock_in_id WHERE gsi.handle_method!=2 and gsi.stock_in_id IN (".JOIN(',',$ar_id).")"; 
    	$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute sql is fail:'.$sql, date("Y-m-d H:i:s")));
    	$res_detail = $this->store_db->getArray($sql); 
		if(!$res_detail || count($res_detail) < 1){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute sql is fail:'.$res, date("Y-m-d H:i:s")));
    	}

    	$ar_data = array();$ar_temp = array();
    	foreach($res_detail as $val){
			$color = $this->getGoodsColor($val['goods_id']);
			$color = ($color) ? $color : '';
    		$ar_temp['goods_id'] = $val['goods_id'];
    		$ar_temp['size'] = $val['size'];
			$ar_temp['color'] = $color;
			$ar_temp['agency_id'] = $val['agency_id'];
    		$ar_temp['quantity'] = $val['quantity'];
			$ar_temp['efficacious_quantity'] =  $val['quantity']; 
    		$ar_data[] = $ar_temp; 
    		$ar_temp = array();
    	}
    	
    	$ar_detail = array();$ar_temp = array();
		foreach($res_detail as $val){
    		$ar_temp['goods_id'] = $val['goods_id'];
    		$ar_temp['size'] = $val['size'];
    		$ar_temp['agency_id'] = $val['agency_id'];
    		$ar_temp['quantity'] = $val['quantity'];
    		$ar_temp['add_user_id'] = $i_userid;
    		$ar_temp['add_time'] = time(); 
    		$ar_detail[] = $ar_temp; 
    		$ar_temp = array();
    	}
    	unset($res_detail);
		
		$this->success_log(array(date("Y-m-d H:i:s"),__CLASS__ . '.class.php line ' . __LINE__ , ' function '. __FUNCTION__ .' user_id:'.$i_userid.' data:'.serialize($ar_data)));
		
    	foreach($ar_data as $val){
    		if(empty($val['goods_id']) || empty($val['agency_id']) || empty($val['size'])) {
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'data is not full:'.serialize($val), date("Y-m-d H:i:s")));
			} 
	
			if($this->findStock($val,'goods_stock')){
  				$sql = "UPDATE goods_stock SET quantity =quantity+". $val['quantity'] .",efficacious_quantity = efficacious_quantity+".$val['efficacious_quantity'].
						" WHERE agency_id = ".$val['agency_id']." AND goods_id =". $val['goods_id'] ." AND size ='".$val['size']."'"; 
  				$res1 = $this->store_db->exec($sql);
				 
  				if($res1 === false) {
  					$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'add stock is fail', date("Y-m-d H:i:s")));
  				}
				importModule('LogSqs');
				$logsqs=new LogSqs;
  			}else{
  				$s_k = array_keys($val);$s_v = array_values($val);
				
  				$sql = "insert into goods_stock (".join(',',$s_k).") values ('".join("','",$s_v)."')";
				 $res1 = $this->store_db->exec($sql);
				
  				if($res1 === false) {
  					$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'add stock is fail', date("Y-m-d H:i:s")));
  				}
				importModule('LogSqs');
		 		$logsqs=new LogSqs;
  			} 
    	}

		foreach($ar_detail as $val){
			if(empty($val['goods_id']) || empty($val['agency_id']) || empty($val['size'])) {
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'data is not full:'.serialize($val), date("Y-m-d H:i:s")));
			}
			 
			$s_k = array_keys($val);$s_v = array_values($val);
			$sql = "insert into goods_stock_detail (".join(',',$s_k).") values ('".join("','",$s_v)."')"; 
			$res2 = $this->store_db->exec($sql); 
			if(!$res2) {
    			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'add stock is fail', date("Y-m-d H:i:s")));
    		}
		}
		importModule('LogSqs');
		$logsqs=new LogSqs;
		
    	return $ar_data;
	}
	
	/**
	 * 将入库的不良添加到库存表中
	 */
	public function addBadToStock($ar_id,$i_userid){
		if($this->store_db == null){
    		return false;
    	}
    	
   		if(!is_array($ar_id) || count($ar_id) < 1){
    		return false;
    	}
   	
    	$sql="SELECT gsid.* FROM  goods_stock_in_details as gsid inner join goods_stock_in as gsi"
    	." on gsi.stock_in_id=gsid.stock_in_id WHERE gsi.handle_method=2 and gsi.stock_in_id IN (".JOIN(',',$ar_id).")"; 
    	$res_detail = $this->store_db->getArray($sql); 
		if(!$res_detail || count($res_detail) < 1){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute sql is fail:'.$res, date("Y-m-d H:i:s")));
    	}

    	$ar_data = array();$ar_temp = array();
    	foreach($res_detail as $val){
			$color = $this->getGoodsColor($val['goods_id']);
			$color = ($color) ? $color : '';
    		$ar_temp['goods_id'] = $val['goods_id'];
    		$ar_temp['size'] = $val['size'];
			$ar_temp['color'] = $color;
			$ar_temp['agency_id'] = $val['agency_id'];
    		$ar_temp['bad_quantity'] = $val['quantity'];
    		$ar_temp['quantity'] = $val['quantity'];
			$ar_temp['efficacious_quantity'] =  0; 
    		$ar_data[] = $ar_temp; 
    		$ar_temp = array();
    	}
    	
    	$ar_detail = array();$ar_temp = array();
		foreach($res_detail as $val){
    		$ar_temp['goods_id'] = $val['goods_id'];
    		$ar_temp['size'] = $val['size'];
    		$ar_temp['agency_id'] = $val['agency_id'];
    		$ar_temp['quantity'] = $val['quantity'];
    		$ar_temp['add_user_id'] = $i_userid;
    		$ar_temp['add_time'] = time(); 
    		$ar_detail[] = $ar_temp; 
    		$ar_temp = array();
    	}
    	unset($res_detail);
		
		$this->success_log(array(date("Y-m-d H:i:s"),__CLASS__ . '.class.php line ' . __LINE__ , ' function '. __FUNCTION__ .' user_id:'.$i_userid.' data:'.serialize($ar_data)));
		
    	foreach($ar_data as $val){
    		if(empty($val['goods_id']) || empty($val['agency_id']) || empty($val['size'])) {
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'data is not full:'.serialize($val), date("Y-m-d H:i:s")));
			} 
	
			if($this->findStock($val,'goods_stock')){
  				$sql = "UPDATE goods_stock SET quantity =quantity+". $val['bad_quantity'] .",bad_quantity = bad_quantity+".$val['bad_quantity'].
						" WHERE agency_id = ".$val['agency_id']." AND goods_id =". $val['goods_id'] ." AND size ='".$val['size']."'"; 
  				$res1 = $this->store_db->exec($sql);
				 
  				if($res1 === false) {
  					$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'add stock is fail', date("Y-m-d H:i:s")));
  				}
				importModule('LogSqs');
				$logsqs=new LogSqs;
  			}else{
  				$s_k = array_keys($val);$s_v = array_values($val);
				
  				$sql = "insert into goods_stock (".join(',',$s_k).") values ('".join("','",$s_v)."')";
				 $res1 = $this->store_db->exec($sql);
				
  				if($res1 === false) {
  					$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'add stock is fail', date("Y-m-d H:i:s")));
  				}
				importModule('LogSqs');
		 		$logsqs=new LogSqs;
  			} 
    	}

		foreach($ar_detail as $val){
			if(empty($val['goods_id']) || empty($val['agency_id']) || empty($val['size'])) {
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'data is not full:'.serialize($val), date("Y-m-d H:i:s")));
			}
			 
			$s_k = array_keys($val);$s_v = array_values($val);
			$sql = "insert into goods_stock_detail (".join(',',$s_k).") values ('".join("','",$s_v)."')"; 
			$res2 = $this->store_db->exec($sql); 
			if(!$res2) {
    			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'add stock is fail', date("Y-m-d H:i:s")));
    		}
		}
		importModule('LogSqs');
		$logsqs=new LogSqs;
		
    	return $ar_data;
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
    	
		$sql = "select count(*) from $s_table where goods_id =".$ar_data['goods_id']." and size = '".$ar_data['size']."' and agency_id = ". $ar_data['agency_id'];
		
    	$r = $this->store_db->getValue($sql);

    	if($r && $r > 0){
    		return true;
    	}
    	
    	return false;
	}
	
	
	/**
     * 商品入库审核
     * 
     * @param array $ar_id  	入库id
     * @param int $i_userid     操作管理员的id
     * @return int|bool 		成功返回影响行数,失败返回false
     */
    public function editAudit($ar_id,$i_userid){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	if(!is_array($ar_id)){
    		$ar_id = (array)$ar_id;
    	}
    	
    	if(count($ar_id) < 1){
    		return false;
    	}
    	
    	$i_userid = (int)$i_userid;
    	
    	if($i_userid == 0){
    		return false;
    	}
    	
    	$sql = "UPDATE goods_stock_in SET stock_in_status = 1,confirm_user_id = $i_userid ,confirm_time = ".time()." WHERE stock_in_id IN (".JOIN(',',$ar_id).")";
    	$res = $this->store_db->exec($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute update is fail:'.$res, date("Y-m-d H:i:s")));
    	}
		importModule('LogSqs');
		$logsqs=new LogSqs;
    	
    	return $res;
    }
    
   /**
     * 移除入库
     * 
     * @param string $s_stockinid 入库id
     * @return int|bool            成功返回影响行数，失败返回false
     */
    public function removeStockIn($s_stockinid){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$s_stockinid = (string)trim($s_stockinid);

    	$sql = "UPDATE goods_stock_in SET stock_in_status = 2 , confirm_user_id = " . $_SESSION['user_id'] . " , confirm_time = " . time() . " WHERE stock_in_id IN (" . $s_stockinid . ")";

    	$res = $this->store_db->exec($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute delete is fail:'.$res, date("Y-m-d H:i:s")));
    	}
		importModule('LogSqs');
		$logsqs=new LogSqs;
    	
    	return $res;
    }
    
    /**
     * 获批次商品的入库数量
     * 
     * @param array $param    批次id和商品id
     * @param int $s_size  	     尺寸
     * @return array|bool     成功返回数量,失败返回false
     */
    public function getStockInNum($param,$s_size = ''){
    	if($this->store_db == null){
    		return false;
    	}

    	if($s_size == ''){
    		//$sql = "SELECT sum(quantity) FROM goods_stock_in_details WHERE  stock_in_id IN ".
    		//		" (SELECT stock_in_id FROM goods_stock_in WHERE batch_id = ".$param.")";
			$sql = "SELECT sum(quantity) FROM goods_stock WHERE batch_id = ".$param;
    	}elseif($s_size == 'size'){
    		$sql = "SELECT sum(quantity) FROM goods_stock_in_details WHERE  stock_in_id = $param";
    	}elseif($s_size == 'sum'){
			$sql = "SELECT sum(quantity) FROM goods_stock_in_details WHERE stock_in_id IN ".
    				" (SELECT stock_in_id FROM goods_stock_in WHERE batch_id = ".$param." and stock_in_status =1)";
		}else{
    		$sql = "SELECT sum(quantity) FROM goods_stock_in_details WHERE  goods_id = ".$param[0]." AND size='$s_size' AND stock_in_id IN ".
    				" (SELECT stock_in_id FROM goods_stock_in WHERE batch_id = ".$param[1]." and stock_in_status =1)";
			//$sql = "SELECT sum(quantity) FROM goods_stock WHERE goods_id = ".$param[0]." AND size='$s_size'";
    	}
    	
    	$res = $this->store_db->getValue($sql);

    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute update is fail:'.$res, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
    
    
/**
     * 获批次商品的入库时间
     * 
     * @param array $param    批次id和商品id
     * @param int $s_size  	     尺寸
     * @return array|bool     成功返回数量,失败返回false
     */
    public function getStockInTime($param){
    	if($this->store_db == null){
    		return false;
    	}
        
    	$s_where = 'where 1 and b.batch_id = '.$param;
    	
    	$sql = "SELECT gsi.stock_in_time FROM goods_stock_in gsi,batch b,suppliers s ".
				   "$s_where AND b.batch_id = gsi.batch_id AND b.supplier_id = s.supplier_id ".
				   "ORDER BY stock_in_id DESC"; 

    	$res = $this->store_db->getValue($sql);
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute update is fail:'.$res, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
    
    
     /**
     * 获批次商品的入库备注
     * 
     * @param array $param    批次id和商品id
     * @param int $s_size  	     尺寸
     * @return array|bool     成功返回数量,失败返回false
     */
    public function getStockInDesc($param){
    	if($this->store_db == null){
    		return false;
    	}
        
    	$s_where = 'where 1 and b.batch_id = '.$param;
    	
    	$sql = "SELECT gsi.description FROM goods_stock_in gsi,batch b,suppliers s ".
				   "$s_where AND b.batch_id = gsi.batch_id AND b.supplier_id = s.supplier_id ".
				   "ORDER BY stock_in_id DESC"; 
    	
    	$res = $this->store_db->getValue($sql);
   	    
    	return $res;
    }
    
     /**
     * 获批次商品的入库序号
     * 
     * @param array $param    批次id和商品id
     * @param int $s_size  	     尺寸
     * @return array|bool     成功返回数量,失败返回false
     */
    public function getStockInId($param){
    	if($this->store_db == null){
    		return false;
    	}
        
    	$s_where = 'where 1 and b.batch_id = '.$param;
    	
    	$sql = "SELECT gsi.stock_in_id FROM goods_stock_in gsi,batch b,suppliers s ".
				   "$s_where AND b.batch_id = gsi.batch_id AND gsi.stock_in_status = 1 AND b.supplier_id = s.supplier_id ".
				   "ORDER BY stock_in_id DESC";     //gsi.stock_in_status=1有效状态
    	$res = $this->store_db->getArray($sql);
   	    
    	return $res;
    }
	
	/**
	 * 查询询入库商品总数，和进货总价
	 *
	 * @param array $ar_stockin
	 * @return 
	 */
	public function getStockinSumAndPrice($ar_stockin){
		if($this->store_db == null){
    		return false;
    	}
		
		if(empty($ar_stockin) || !is_array($ar_stockin)) {
			return false;
		}
		
		if(!empty($ar_stockin['batch_id'])) {
			$sql = "SELECT goods_id,cost_price FROM batch_details WHERE batch_id = $ar_stockin[batch_id]";
			$res = $this->store_db->getArray($sql);

			$ar_batchprice = array();
			
			if($res && is_array($res)) {
				foreach($res as $r) {
					if(!array_key_exists($r['goods_id'],$ar_batchprice)){
						$ar_batchprice[$r['goods_id']][] = $r['cost_price'];
					}
				}
			}
			if(!empty($ar_batchprice)) {
				foreach($ar_batchprice as $key=>$val) {
					$ar_batchprice[$key] = number_format(array_sum($val)/count($val),2,'.','');
				}
			}
		}
		
		
		if(!empty($ar_stockin['stock_in_id'])) {
			$sql = "SELECT goods_id,quantity FROM goods_stock_in_details WHERE stock_in_id = $ar_stockin[stock_in_id]";
			$res = $this->store_db->getArray($sql);
			
			$ar_stockinnum = array();
			
			if($res && is_array($res)) {
				foreach($res as $r) {
					$ar_stockinnum[$r['goods_id']] += $r['quantity'];
				}
			}
		}
		
		$ar_res = array('costprice'=>0.00,'quantity'=>0);
		
		if(!empty($ar_stockinnum)) {
			foreach($ar_stockinnum as $key=>$val) {
				if(!empty($ar_batchprice)) {
					$ar_res['costprice'] += $val * $ar_batchprice[$key];
				}
				$ar_res['quantity']  += $val;
			}
			
			$ar_res['costprice'] = number_format($ar_res['costprice'],2,'.','');
		}
		//PRINT_R($ar_batchprice);
		//PRINT_R($ar_stockinnum);
		//print_r($ar_res);
		return $ar_res;
	}
	
	/***
	 * 获取批次对应的入库序号
	 */
	public function  getBatchStockInId($param){
		if($this->store_db == null) return false;
		$sql ="SELECT batch_id,stock_in_id FROM `goods_stock_in` WHERE batch_id = ".$param;
		$res = $this->store_db->getArray($sql);
		if(!$res) return  false;
		return  $res;
	}
	
	/**
	 * 获取供应商
	 */
	public function getSuppliers()
	{
		if($this->store_db == null) return false;
		$sql ="SELECT supplier_id,supplier_name from suppliers";
		$res = $this->store_db->getArray($sql);
		if($res)
		return $res;
	}
	
    /**
	 * 获取批次入库数量
	 */
/*    public function getStockInBatchNum($ar_batchid){
   		if($this->store_db == null){
    		return false;
    	}
    	
    	if(empty($ar_batchid)){
    		return false;
    	}
    	
    	$sql = "select stock_in_id,batch_id from goods_stock_in where batch_id  IN (".join(',',$ar_batchid).")";
    	$res = $this->store_db->getArray($sql);
    	
    	if(!$res){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute update is fail:'.$res, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }*/
	
	/**
	 * 查询商品的批次数量
	 * 
	 * @param $i_stockinid  入库id
	 * @param $goodsid      商品id
	 * @return
	 */
	public function getBatchNum($i_stockinid, $goodsid){
		if($this->store_db == null){
    		return false;
    	}
		
		$i_stockinid = (int)$i_stockinid;
		$goodsid = (int)$goodsid;
		
		if($i_stockinid == 0 || $goodsid == 0){
			return false;	
		}
		
		$sql = "SELECT sum(quantity) FROM goods_stock_in_details WHERE stock_in_id = $i_stockinid AND goods_id = $goodsid";
		$r = $this->store_db->getValue($sql);
		
		if($r === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute update is fail:'.$res, date("Y-m-d H:i:s")));
		}
		
		return $r;
	}
	
	 /**
	  * 获取已退货的入库商品数
	  * 
	  * @param string $s_where 查询条件
	  * @return array|bool
	  */
	 public function getInStockGoods($s_where) {
	 	if($this->store_db == null) return false;
	 	
	 	$sql = "SELECT id.quantity,id.goods_id,id.size,i.old_order_id FROM goods_stock_in i,goods_stock_in_details id $s_where AND  i.stock_in_id = id.stock_in_id ";
	 	$res = $this->store_db->getArray($sql);

	 	if($res === false || !is_array($res)){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	$ar_data = array();
 		if($res) {
	    	foreach($res as $r) {
	    		$ar_data[$r['old_order_id']]['in_sum'] += isset($r['quantity']) ? $r['quantity'] : 0;
	    		$ar_data[$r['old_order_id']][$r['goods_id']][$r['size']] += isset($r['quantity']) ? $r['quantity'] : 0;
	    	}
 		}

    	return $ar_data;
	 }

	 /**
	  * 入库总额处理
	  */
	 public function getInStockSumCost($param){
	 	if($this->store_db == null){
	 	return  false;
	 	}
	 	if (empty($param) || !is_array($param)){
	 		return false;
	 	}
	 
	 if(!empty($param['batch_id'])) {
			$sql = "SELECT goods_id,cost_price FROM batch_details WHERE batch_id = $param[batch_id]";
			$res = $this->store_db->getArray($sql);

			$ar_batchprice = array();
			
			if($res && is_array($res)) {
				foreach($res as $r) {
					if(!array_key_exists($r['goods_id'],$ar_batchprice)){
						$ar_batchprice[$r['goods_id']][] = $r['cost_price'];
					}
				}
			}
			if(!empty($ar_batchprice)) {
				foreach($ar_batchprice as $key=>$val) {
					$ar_batchprice[$key] = number_format(array_sum($val)/count($val),2,'.','');
				}
			}
		}
		
		$sum = array();
		if(!empty($param['stock_in_id'])){
			foreach ($param['stock_in_id'] as $val){
			$sql = "SELECT goods_id,quantity FROM goods_stock_in_details WHERE stock_in_id = ".$val['stock_in_id'];
			$resu = $this->store_db->getArray($sql);
			foreach($resu as $val){
				$ar_batch['goods_id'] = $val['goods_id'];
				$ar_batch['quantity'] = $val['quantity'];
				$ar_batch['cost'] = 	$ar_batchprice[$ar_batch['goods_id']];
				$sum['costprice'] +=  $ar_batch['cost'] * $ar_batch['quantity']; 	
				$sum['costprice'] = number_format($sum['costprice'],2,'.','');					  	
			   }
			}
		}
		return $sum; 	
		
	 }
	
	/**
     * 查询入库数
     * 
     * @param string $s_where 查询条件
     * @return array|bool
     */
    public function getInStockNum($s_where) {
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$s_where = (string)$s_where;
    	
    	if($s_where == ''){
    		return false;
    	}
    	
    	$sql = "SELECT stock_in_id,stock_in_type FROM goods_stock_in $s_where";
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
    
    /**
     * 质检审核
     * @param  string $stock_id
     * @return array
     */
    public function quality_confirm($stock_id)
    {
    	if($this->store_db == null)  return false;
    	$ip = $this->app->ip();
    	$date = time();
        
    	if(is_array($stock_id))
    	{
    		foreach ($stock_id as $key=>$val)
    		{  
    			$sql = "UPDATE goods_stock_in SET stock_in_status = 3,confirm_time = '".$date."',confirm_user_id = ".$_SESSION[user_id]." WHERE stock_in_id = ".$val;
    			$res = $this->store_db->exec($sql);
    			
    			if($res === false)
    			{
    				return 1;
    			}
    			
    			$sql_log = "INSET INTO admin_log(log_time,user_id,log_info,ip_address)VALUES($date,$_SESSION[user_id],'质检审核',$ip)";
    			$r       = $this->store_db->exec($sql);
    		}
    	}
    }
    
    /**
     * 获取审核类别
     */
    public function getConfirm_type($batch_id)
    {
    	if($this->store_db == null) return false;
    	$sql = "SELECT stock_in_status FROM goods_stock_in WHERE batch_id = ".$batch_id;
    	$res  = $this->store_db->getValue($sql);
    	if($res === false)
    	{
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
	    $log->reset()->setPath("success/StockInInfo")->setData($data)->write();
	}
    
	/**
	 * 数据更新失败记录日志，并标识操作失败
	 *
	 * @param 	Array 	$data
	 * @return 	bool	false
	 */
	private function _log($data){
	    $log = $this->app->log();
	    $log->reset()->setPath("modules/StockInInfo")->setData($data)->write();
	    
	    return false;
	}
}
?>