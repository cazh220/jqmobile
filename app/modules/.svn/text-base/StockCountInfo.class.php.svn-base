<?php
/**
 * 商品出库处理
 * 
 * @package modules
 * @author  鲍<chenglin.bao@lyceem.com>
 * @copyright 2010-3-31
 */
class StockCountInfo {
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
	
	private $union = null;
	
	private $store_db = null;
	
	
 	/**
     * 构造函数，获取数据库连接对象
     *
     */
    public function __construct(){
        global $app;
		
        $this->app = $app;
		
	    $this->db = $app->orm($app->cfg['db'])->query();
		
		mysql_query("set names utf8");
		
        $this->store_db = $app->orm($app->cfg['store_db'])->query();
		
        mysql_query("set names utf8");
		
		$this->union = $app->orm($app->cfg['union'])->query();
		
        mysql_query("set names utf8");
    }
	
	/**
	 * 库存统计分页查询
	 *
	 * @param string $s_where
	 * @return 
     */
	public function getStockCount($s_where) {
		if($this->store_db == null){
    		return false;
    	}
		
		$s_where = (string)$s_where;
    	
    	if($s_where == ''){
    		return false;
    	}
		
		$sql = "SELECT * FROM goods_stock $s_where";

		$r   = $this->store_db->getArray($sql); 
		if($r === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute delete is fail:'.$res, date("Y-m-d H:i:s")));
    	}
    	
    	return $r;
	}
	
	/**
	 * 获取商品编号
	 */
	public function getGoods_id($goods_name)
	{
		if($this->db == null) return false;
		$sql = "SELECT goods_id FROM ecs_goods WHERE goods_name LIKE '%$goods_name%'";
		$res = $this->db->getArray($sql);
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute getArray is fail:'.$res, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
	}
	
	/**
	 * 查询库存总数
	 * 
     * @param string $s_where 查询条件
     * @return int|bool     
	 */
	public function getStockCountNum($s_where){
		if($this->store_db == null){
    		return false;
    	}
    	
    	$s_where = (string)$s_where;
    	
    	if($s_where == ''){
    		return false;
    	}
    	
    	$sql = "SELECT COUNT(goods_id) FROM goods_stock $s_where";
    	$res = $this->store_db->getValue($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
	}
	
	/**
	 * 查询库存商品的颜色
	 *
	 * @param array $ar_goodsid
	 * @return
	 */
	public function getStockGoodsColor($ar_goodsid){
		if($this->store_db == null){
    		return false;
    	}
		
		if(empty($ar_goodsid)){
			return false;
		}
		
		
		$whereOr = '';	
		foreach($ar_goodsid as $k=>$v)
		{
			if( !empty( $v ) )
			{
				if( ""==$whereOr )
				{
					$whereOr = " goods_id =  ".$v;
				}else{
					$whereOr .= " OR goods_id =  ".$v;
				}
			}
		}
		if( empty( $whereOr  ) )
		{
			return false;
		}
		$sql = "SELECT goods_id,color FROM goods_stock WHERE ".$whereOr;                                                // 不用 in  转成 = ,谢佐福    2012-6-14 15:00
		//die( $sql );

		$res = $this->store_db->getArray($sql);
		
		if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute error : '.$sql, date("Y-m-d H:i:s")));
    	}
		
		if(empty($res) || !is_array($res)){
			return false;
		}
		
		$ar = array();
		foreach($res as $r){
			if(in_array($r['goods_id'],array_keys($ar))){
				$ar[$r['goods_id']] = $r['color'];
			}else{
				$ar[$r['goods_id']] = $r['color'];	
			}
		}
		
		return $ar;
	}
	
	/**
	 * 进销存入库商品统计查询
	 * 
	 * @param string $s_where
	 * @retrun array|bool
	 */
	public function getInStockCount($s_where) {
		if($this->store_db == null){
    		return false;
    	}
		
		$s_where = (string)$s_where;
		
		if($s_where == '') {
			return false;
		}
		
		$sql = "SELECT goods_id,size,quantity FROM `goods_stock_in_details` WHERE stock_in_id IN (SELECT stock_in_id  FROM `goods_stock_in` $s_where) ORDER BY goods_id";
		$res = $this->store_db->getArray($sql);
		if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
	}
	
	/**
	 * 查询进销存出库商品
	 * 
	 * @param string $s_where 
	 * @return array|bool
	 */
	public function getOutStockCount($s_where) {
		if($this->store_db == null){
    		return false;
    	}
		
		$s_where = (string)$s_where;
		
		if($s_where == '') {
			return false;
		}
		
		$sql = "SELECT goods_id,size,quantity FROM `goods_stock_out_details` WHERE stock_out_id IN (SELECT stock_out_id  FROM `goods_stock_out` $s_where) ORDER BY goods_id";

		$res = $this->store_db->getArray($sql);
		
		if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
	}
	
	/**
	 * 获取分销商信息
	 * 
	 */
	public function getDistributor($param)
	{
	  if($this->store_db == null) return false;
	  if($param){
	    $sql = "SELECT distributor_name FROM distributors where distributor_id = ".$param;
	  }else{
	    return  false;
	  }	  
	  $res = $this->store_db->getValue($sql);
	  if($res)
	    return $res;  
	}
	
 	/**
     * 通过批次查询进货统计
     * 
     * @param void 
     * @return array|bool
     */
    public function getBatchInStock (){
    	if($this->store_db == null) return false;
    	
    	$sql = "SELECT stock_in_id,batch_id,stock_in_status,description FROM goods_stock_in WHERE stock_in_type = 1 and stock_in_status =1";
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
    	}
		
		if(!is_array($res) || !$res) return false;
    	
		$ar_batchid = $ar_stockinid = array();

		foreach($res as $r) {
			if($r['batch_id']) {
				$ar_batchid[] = $r['batch_id'];
			}
			
			if(array_key_exists($r['batch_id'],$ar_stockinid)) {
				$ar_stockinid[$r['batch_id']][] = $r['stock_in_id'];
			} else {
				$ar_stockinid[$r['batch_id']][] = $r['stock_in_id'];	
			}
		}
		 
		if($ar_batchid && is_array($ar_batchid)) {
			$ar_batchid = array_unique($ar_batchid);
			
			//查询商品批次码
			$sql = "SELECT batch_id,batch_code FROM batch WHERE batch_id IN (".join(',',$ar_batchid).")";
			$r_batchcode = $this->store_db->getArray($sql);
			
			if($r_batchcode === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
			}
			
			//查询批次进货价
			$sql = "SELECT batch_id,cost_price FROM batch_details WHERE batch_id IN (".join(',',$ar_batchid).")";
			$r_batchprice = $this->store_db->getArray($sql);
		
			if($r_batchprice === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
			}
		}
		
		if($ar_stockinid && is_array($ar_stockinid)) {
			$ar_quantity = array();
			
			//查询入库数量
			foreach($ar_stockinid as $key=>$val) {
				$ar_stockinid = array_unique($val);
				
				$sql = "SELECT sum(quantity) FROM goods_stock_in_details WHERE stock_in_id IN (".join(',',$val).")";
				$i_num = $this->store_db->getValue($sql);
			
				if($i_num === false){
					return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
				}
				
				$ar_quantity[$key] = (int)$i_num;
			}
		}
		
		$ar_batchcode = $ar_batchprice = array();

		if(is_array($r_batchprice) && $r_batchprice) {
			foreach($r_batchprice as $val) {
				if(array_key_exists($val['batch_id'],$ar_batchprice)) {
					$ar_batchprice[$val['batch_id']][] = $val['cost_price'];
				} else {
					$ar_batchprice[$val['batch_id']][] = $val['cost_price'];
				}
			}
		
			if($ar_batchprice) {
				foreach($ar_batchprice as $key=>$val) {
					$ar_batchprice[$key] =  array_unique($val);
				}
		
				foreach($ar_batchprice as $key=>$val) {
					$i_count = count($val);
					$ar_batchprice[$key] = $i_count > 1 ? array_sum($val)/$i_count : $val[0];
				}
			}
		}
		
		if($r_batchcode && is_array($r_batchcode)) {
			foreach($r_batchcode as $val) {
				$ar_batchcode[$val['batch_id']] = $val['batch_code'];
			}
		}

		$ar_res = array();

		foreach($res as &$r) {
			if(!array_key_exists($r['batch_id'],$ar_res)) {
				$f_price = !empty($ar_batchprice[$r['batch_id']]) ? $ar_batchprice[$r['batch_id']] : 0;
				$f_price = number_format($f_price,2,'.','');
				$i_sum   = !empty($ar_quantity[$r['batch_id']]) ? $ar_quantity[$r['batch_id']] : 0;

				$ar_res[$r['batch_id']] = array(
					'batch_id'        => $r['batch_id'],
					'batch_code'      => !empty($ar_batchcode[$r['batch_id']]) ? $ar_batchcode[$r['batch_id']] : '',
					'stock_in_status' => $r['stock_in_status'],
					'description'     => $r['description'],
					'price'      => $f_price,
					'quantity'        => !empty($ar_quantity[$r['batch_id']]) ? $ar_quantity[$r['batch_id']] : 0,
					'sum_price'  => number_format($i_sum * $f_price,2,'.','')
				);
				
				if($this->app->cfg['cost']['standard']=='ON')
				{   
					if($this->store_db == null)  return false;
					$sql = "SELECT gsi.batch_id,gsid.goods_id,gsid.quantity,eg.standard_cost FROM goods_stock_in gsi,goods_stock_in_details gsid, lyceem.ecs_goods eg 
					WHERE gsid.stock_in_id = gsi.stock_in_id AND eg.goods_id = gsid.goods_id AND gsi.stock_in_type = 1 AND gsi.batch_id = ".$r['batch_id'];
					$res_s = $this->store_db->getArray($sql);
					if($res_s === false)
					{
						return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
					}
					$sum = 0;
					
					foreach ($res_s as $k=>$v)
					{
						$stand_cost = empty($v['standard_cost']) ? '0' : $v['standard_cost'];
						$sum += $v['quantity']*$stand_cost;
					}
					$ar_res[$r['batch_id']]['sum_standard'] = number_format($sum,2,'.','');
					unset($res_s);
				}
			}
		}
		return $ar_res;
    }
	
	/**
     * 通过分类查询进货统计
     * 
     * @param void 
     * @return array|bool
     */
    public function getTypeInStock (){
    	if($this->store_db == null || $this->db == null) {
			return false;
		}
		
		//入库查询
		$sql = "SELECT gsi.stock_in_id,gsi.batch_id,gsid.goods_id,gsid.quantity FROM goods_stock_in gsi,goods_stock_in_details gsid WHERE stock_in_type = 1 and stock_in_status =1 and gsid.stock_in_id = gsi.stock_in_id";
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
    	}
		
		if(!is_array($res) || !$res) return false;
		
		$ar_goodsid = $ar_batchid = $ar_stockin = array();
			
		foreach($res as $r) {
			if(array_key_exists($r['batch_id'].'_'.$r['goods_id'],$ar_stockin)) {
				$ar_stockin[$r['batch_id'].'_'.$r['goods_id']]['quantity'] += $r['quantity'];
			} else {
				$ar_stockin[$r['batch_id'].'_'.$r['goods_id']] = $r;
			}
			
			$ar_batchid[] = $r['batch_id'];
			
			$ar_goodsid[] = $r['goods_id'];
		}
		
		//查询批次进货价
		if($ar_batchid && is_array($ar_batchid)) {
			$ar_batchid = array_unique($ar_batchid);
			
			$sql = "SELECT batch_id,cost_price FROM batch_details WHERE batch_id IN (".join(',',$ar_batchid).")";
			$r_batchprice = $this->store_db->getArray($sql);
			
			if($r_batchprice === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
			}
			
			$ar_batchprice = array();
			
			if(is_array($r_batchprice) && $r_batchprice) {
				foreach($r_batchprice as $val) {
					if(array_key_exists($val['batch_id'],$ar_batchprice)) {
						$ar_batchprice[$val['batch_id']][] = $val['cost_price'];
					} else {
						$ar_batchprice[$val['batch_id']][] = $val['cost_price'];
					}
				}
			
				if($ar_batchprice) {
					foreach($ar_batchprice as $key=>$val) {
						$ar_batchprice[$key] =  array_unique($val);
					}
					
					foreach($ar_batchprice as $key=>$val) {
						$i_count = count($val);
						$ar_batchprice[$key] = $i_count > 1 ? array_sum($val)/$i_count : $val[0];
					}
				}
			}
		}
		
		//查询商分类
		if($ar_goodsid && is_array($ar_goodsid)) {
			$ar_goodsid = array_unique($ar_goodsid);
			
			$sql = "SELECT goods_id,cat_id FROM ecs_goods WHERE goods_id IN (".JOIN(',',$ar_goodsid).")";
			$r_catid = $this->db->getArray($sql);
			
			if($r_catid === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
			}
			
			$ar_catid = array();
			
			foreach($r_catid as $r) {
				$ar_catid[$r['goods_id']] = $r['cat_id']; 
			}
			
			$sql = "SELECT c.cat_id,c.cat_name FROM ecs_category c WHERE cat_id IN (".JOIN(',',$ar_catid).")";
			$r_type = $this->db->getArray($sql);

			if($r_type === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
			}
	
			$ar_type = array();
	
			foreach($ar_catid as $k=>$r) {
				foreach($r_type as $val) {
					if($r == $val['cat_id']) {
						$ar_type[$k] = $val;
					}
				}
			}
		}
	
		foreach($ar_stockin as &$val) {
			$val['price'] = isset($ar_batchprice[$val['batch_id']]) ? number_format($ar_batchprice[$val['batch_id']],2,'.','') : 0.00;
			
			$val['cat_id'] = isset($ar_type[$val['goods_id']]['cat_id']) ? $ar_type[$val['goods_id']]['cat_id'] : '';
			$val['cat_name'] = isset($ar_type[$val['goods_id']]['cat_name']) ? $ar_type[$val['goods_id']]['cat_name'] : '';
			if($this->app->cfg['cost']['standard']=='ON')
			{
				if($this->db == null)  return false;
				$sql = "SELECT standard_cost FROM ecs_goods WHERE goods_id = ".$val['goods_id'];
				$stand_cost = $this->db->getValue($sql);
				if($stand_cost === false)
				{
					return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
				}
			    $val['standard'] = empty($stand_cost) ? '0' : $stand_cost;
			}	
		}
		unset($ar_batchprice,$ar_type);

		$ar_res = array();
		
		foreach($ar_stockin as $val) {
			$ar_res[$val['cat_id']]['quantity']  += $val['quantity'] ? (int)$val['quantity'] : 0;
			$ar_res[$val['cat_id']]['sum_price'] += $val['quantity'] * $val['price'];	
			
			$ar_res[$val['cat_id']]['sum_price'] = number_format($ar_res[$val['cat_id']]['sum_price'],2,'.','');
			$ar_res[$val['cat_id']]['cat_id']    = $val['cat_id'];
			$ar_res[$val['cat_id']]['cat_name']  = $val['cat_name'];
			$ar_res[$val['cat_id']]['sum_standard'] += $val['quantity'] * $val['standard'];	

			if($this->app->cfg['cost']['standard']=='ON')
			{
				$ar_res[$val['cat_id']]['sum_standard'] = number_format($ar_res[$val['cat_id']]['sum_standard'],2,'.','');		
			}	
		}
		
		unset($ar_stockin);
		return $ar_res;
    }
	
	/**
     * 通过款式查询进货统计
     * 
     * @param void 
     * @return array|bool
     */
    public function getStyleInStock (){
    	if($this->store_db == null || $this->db == null) {
			return false;
		}
		
		//入库查询
		$sql = "SELECT gsi.stock_in_id,gsi.batch_id,gsid.goods_id,gsid.quantity FROM goods_stock_in gsi,goods_stock_in_details gsid WHERE stock_in_type = 1 and stock_in_status =1 and gsid.stock_in_id = gsi.stock_in_id";
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
    	}
		
		if(!is_array($res) || !$res) return false;
		
		$ar_goodsid = $ar_batchid = $ar_stockin = array();
			
		foreach($res as $r) {
			if(array_key_exists($r['batch_id'].'_'.$r['goods_id'],$ar_stockin)) {
				$ar_stockin[$r['batch_id'].'_'.$r['goods_id']]['quantity'] += $r['quantity'];
			} else {
				$ar_stockin[$r['batch_id'].'_'.$r['goods_id']] = $r;
			}
			
			$ar_batchid[] = $r['batch_id'];
			
			$ar_goodsid[] = $r['goods_id'];
		}
		
		if($ar_batchid && is_array($ar_batchid)) {
			$ar_batchid = array_unique($ar_batchid);
			
			//查询批次进货价
			$sql = "SELECT batch_id,cost_price FROM batch_details WHERE batch_id IN (".join(',',$ar_batchid).")";
			$r_batchprice = $this->store_db->getArray($sql);
			
			if($r_batchprice === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
			}
			
			$ar_batchprice = array();
			
			if(is_array($r_batchprice) && $r_batchprice) {
				foreach($r_batchprice as $val) {
					if(array_key_exists($val['batch_id'],$ar_batchprice)) {
						$ar_batchprice[$val['batch_id']][] = $val['cost_price'];
					} else {
						$ar_batchprice[$val['batch_id']][] = $val['cost_price'];
					}
				}
			
				if($ar_batchprice) {
					foreach($ar_batchprice as $key=>$val) {
						$ar_batchprice[$key] =  array_unique($val);
					}
					
					foreach($ar_batchprice as $key=>$val) {
						$i_count = count($val);
						$ar_batchprice[$key] = $i_count > 1 ? array_sum($val)/$i_count : $val[0];
					}
				}
			}
		}

		//查询商品款式
		if($ar_goodsid && is_array($ar_goodsid)) {
			$ar_goodsid = array_unique($ar_goodsid);
			
			$sql = "SELECT goods_style_sn,goods_id FROM ecs_goods WHERE goods_id IN (".JOIN(',',$ar_goodsid).")";
			$r_style = $this->db->getArray($sql);
			
			if($r_style === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
			}
	
			$ar_style = array();
			
			foreach($r_style as $r) {
				$ar_style[$r['goods_id']] = $r['goods_style_sn'];
			}
		}
		
		foreach($ar_stockin as &$val) {
			$val['price'] = isset($ar_batchprice[$val['batch_id']]) ? number_format($ar_batchprice[$val['batch_id']],2,'.','') : 0.00;
			$val['goods_style_sn'] = isset($ar_style[$val['goods_id']]) ? $ar_style[$val['goods_id']] : '';
				
			if($this->app->cfg['cost']['standard']=='ON')
			{
				if($this->db == null)  return false;
				$sql = "SELECT standard_cost FROM ecs_goods WHERE goods_id = ".$val['goods_id'];
				$stand_cost = $this->db->getValue($sql);
				if($stand_cost === false)
				{
					return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
				}
			    $val['standard'] = empty($stand_cost) ? '0' : $stand_cost;
			}
		}
		unset($ar_batchprice,$ar_style);
		
		//echo '<pre>';print_r($ar_stockin);die;
		
		$ar_res = array();
		
		foreach($ar_stockin as $val) {
			$ar_res[$val['goods_style_sn']]['quantity']  += $val['quantity'] ? (int)$val['quantity'] : 0;
			$ar_res[$val['goods_style_sn']]['sum_price'] += $val['quantity'] * $val['price'];	
			
			$ar_res[$val['goods_style_sn']]['sum_price'] = number_format($ar_res[$val['goods_style_sn']]['sum_price'],2,'.','');
			$ar_res[$val['goods_style_sn']]['goods_style_sn'] = $val['goods_style_sn'];
			$ar_res[$val['goods_style_sn']]['sum_standard'] += $val['standard'] * $val['quantity'];
			if($this->app->cfg['cost']['standard']=='ON')
			{
				$ar_res[$val['goods_style_sn']]['sum_standard'] = number_format($ar_res[$val['goods_style_sn']]['sum_standard'],2,'.','');
			}
			
		}
	
		return $ar_res;
    }
    
    /***
     * 查询供应商进货信息
     */
    public function SupplierStatic()
    {
      if($this->union == null) return false;
      //$sql = "SELECT DISTINCT(ba.batch_id),bd.goods_id,supplier_id,batch_code,bd.quantity,sd.quantity as shou_q,si.description,si.create_time FROM batch_details bd,batch ba,goods_stock_in si,goods_stock_in_details sd WHERE bd.batch_id = ba.batch_id AND ba.batch_id = si.batch_id AND si.stock_in_id = sd.stock_in_id";
      $sql ="SELECT DISTINCT(ba.batch_id),batch_code,supplier_id,bd.quantity,bd.cost_price, bd.goods_id,bd.size,si.description FROM batch ba, batch_details bd,goods_stock_in si WHERE ba.batch_id = bd.batch_id AND ba.batch_id = si.batch_id and bd.goods_id = 1 ";
      $res = $this->store_db->getArray($sql);
      if($res === false)
          return  false;
      return  $res;
    }
    
    /**
     * 获取进货的信息
     */
    public function GetStockIn()
    {
      if($this->union == null) return false;
      $sql = "";
    }
	
	/**
     * 通过颜色查询进货统计
     * 
     * @param void 
     * @return array|bool
     */
    public function getColorInStock (){
    	if($this->store_db == null || $this->db == null) {
			return false;
		}
		
		//入库查询
		$sql = "SELECT gsi.stock_in_id,gsi.batch_id,gsid.goods_id,gsid.quantity FROM goods_stock_in gsi,goods_stock_in_details gsid WHERE stock_in_type = 1 and stock_in_status =1 and gsid.stock_in_id = gsi.stock_in_id";
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
    	}
		
		if(!is_array($res) || !$res) return false;
		
		$ar_goodsid = $ar_batchid = $ar_stockin = array();
			
		foreach($res as $r) {
			if(array_key_exists($r['batch_id'].'_'.$r['goods_id'],$ar_stockin)) {
				$ar_stockin[$r['batch_id'].'_'.$r['goods_id']]['quantity'] += $r['quantity'];
			} else {
				$ar_stockin[$r['batch_id'].'_'.$r['goods_id']] = $r;
			}
			
			$ar_batchid[] = $r['batch_id'];
			
			$ar_goodsid[] = $r['goods_id'];
		}
		
		//查询批次进货价
		if($ar_batchid && is_array($ar_batchid)) {
			$ar_batchid = array_unique($ar_batchid);
			
			$sql = "SELECT batch_id,cost_price FROM batch_details WHERE batch_id IN (".join(',',$ar_batchid).")";
			$r_batchprice = $this->store_db->getArray($sql);
			
			if($r_batchprice === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
			}
			
			$ar_batchprice = array();
			
			if(is_array($r_batchprice) && $r_batchprice) {
				foreach($r_batchprice as $val) {
					if(array_key_exists($val['batch_id'],$ar_batchprice)) {
						$ar_batchprice[$val['batch_id']][] = $val['cost_price'];
					} else {
						$ar_batchprice[$val['batch_id']][] = $val['cost_price'];
					}
				}
			
				if($ar_batchprice) {
					foreach($ar_batchprice as $key=>$val) {
						$ar_batchprice[$key] =  array_unique($val);
					}
					
					foreach($ar_batchprice as $key=>$val) {
						$i_count = count($val);
						$ar_batchprice[$key] = $i_count > 1 ? array_sum($val)/$i_count : $val[0];
					}
				}
			}
		}
		
		//查询商品颜色
		if($ar_goodsid && is_array($ar_goodsid)) {
			$ar_goodsid = array_unique($ar_goodsid);
			
			$sql = "SELECT color,goods_id FROM ecs_goods_unique WHERE goods_id IN (".JOIN(',',$ar_goodsid).")";
			$r_color = $this->db->getArray($sql);
			
			if($r_color === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
			}
	
			$ar_color = array();
			
			foreach($r_color as $r) {
				$ar_color[$r['goods_id']] = $r['color'];
			}
		}
		
		foreach($ar_stockin as &$val) {
			$val['price'] = isset($ar_batchprice[$val['batch_id']]) ? number_format($ar_batchprice[$val['batch_id']],2,'.','') : 0.00;
			$val['color'] = isset($ar_color[$val['goods_id']]) ? $ar_color[$val['goods_id']] : '';
				
			if($this->app->cfg['cost']['standard']=='ON')
			{
				if($this->db == null)  return false;
				$sql = "SELECT standard_cost FROM ecs_goods WHERE goods_id = ".$val['goods_id'];
				$stand_cost = $this->db->getValue($sql);
				if($stand_cost === false)
				{
					return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
				}
			    $val['standard'] = empty($stand_cost) ? '0' : $stand_cost;
			}
		}
		unset($ar_batchprice,$ar_color);
		
		$ar_res = array();
		
		foreach($ar_stockin as $val) {
			$ar_res[$val['color']]['quantity']  += $val['quantity'] ? (int)$val['quantity'] : 0;
			$ar_res[$val['color']]['sum_price'] += $val['quantity'] * $val['price'];	
			
			$ar_res[$val['color']]['sum_price'] = number_format($ar_res[$val['color']]['sum_price'],2,'.','');
			$ar_res[$val['color']]['color']     = $val['color'];
			if($this->app->cfg['cost']['standard']=='ON')
			{
				$ar_res[$val['color']]['sum_standard'] += $val['quantity'] * $val['standard'];
					
				$ar_res[$val['color']]['sum_standard'] = number_format($ar_res[$val['color']]['sum_standard'],2,'.','');
			}
		}
		
		return $ar_res;
    }
	
	/**
     * 通过尺寸查询进货统计
     * 
     * @param void 
     * @return array|bool
     */
    public function getSizeInStock (){
    	if($this->store_db == null || $this->db == null) {
			return false;
		}
		
		//入库查询
		$sql = "SELECT gsi.stock_in_id,gsi.batch_id,gsid.goods_id,eg.standard_cost,gsid.size,gsid.quantity  FROM goods_stock_in gsi,goods_stock_in_details gsid, lyceem.ecs_goods eg 
WHERE stock_in_type = 1 and stock_in_status =1 and gsid.stock_in_id = gsi.stock_in_id AND eg.goods_id = gsid.goods_id";	  
    	$res = $this->store_db->getArray($sql);
    	
    	if($this->app->cfg['cost']['standard']=='ON')
    	{
    		foreach($res as $k=>$v)
    		{
    			empty($v['standard_cost']) ? '0':intval($v['standard_cost']);
    			$res[$k]['sum_standard'] = ($v['standard_cost'] * $v['quantity']);
    		}
    	}
	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
    	}
		
		if(!is_array($res) || !$res) return false;
		
		$ar_batchid = $ar_stockin = array();

		foreach($res as $r) {
			if(array_key_exists($r['size'],$ar_stockin)) {
				$ar_stockin[$r['size']]['quantity'] += $r['quantity'];
				if($this->app->cfg['cost']['standard']=='ON')
				{   
					$ar_stockin[$r['size']]['sum_standard'] += $r['sum_standard'];
				}
			} else {
				$ar_stockin[$r['size']] = $r;
			}
			
			$ar_batchid[] = $r['batch_id'];
		}

		if($ar_batchid && is_array($ar_batchid)) {
			$ar_batchid = array_unique($ar_batchid);
			
			//查询批次进货价
			$sql = "SELECT batch_id,cost_price FROM batch_details WHERE batch_id IN (".join(',',$ar_batchid).")";
			$r_batchprice = $this->store_db->getArray($sql);
			
			if($r_batchprice === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
			}
			
			$ar_batchprice = array();
			
			if(is_array($r_batchprice) && $r_batchprice) {
				foreach($r_batchprice as $val) {
					if(array_key_exists($val['batch_id'],$ar_batchprice)) {
						$ar_batchprice[$val['batch_id']][] = $val['cost_price'];
					} else {
						$ar_batchprice[$val['batch_id']][] = $val['cost_price'];
					}
				}
			
				if($ar_batchprice) {
					foreach($ar_batchprice as $key=>$val) {
						$ar_batchprice[$key] =  array_unique($val);
					}
					
					foreach($ar_batchprice as $key=>$val) {
						$i_count = count($val);
						$ar_batchprice[$key] = $i_count > 1 ? array_sum($val)/$i_count : $val[0];
					}
				}
			}
		}
	
		foreach($ar_stockin as &$val) {
			$val['price'] = isset($ar_batchprice[$val['batch_id']]) ? number_format($ar_batchprice[$val['batch_id']],2,'.','') : 0.00;
			$val['sum_price'] = $val['quantity']*$val['price'];
			$val['sum_price'] = number_format($val['sum_price'],2,'.','');
		}
		unset($ar_batchprice);
		
		return $ar_stockin;
    }
	
	
	/**
     * 通过供应商查询进货统计
     * 
     * @param void 
     * @return array|bool
     */
    public function getSuppliesInStock (){
    	if($this->store_db == null || $this->db == null) {
			return false;
		}
		
		//入库查询
		$sql = "SELECT gsi.stock_in_id,gsi.batch_id,gsid.goods_id,gsid.quantity,eg.standard_cost  FROM goods_stock_in gsi,goods_stock_in_details gsid,lyceem.ecs_goods eg 
		 WHERE stock_in_type = 1 and stock_in_status =1 and gsid.stock_in_id = gsi.stock_in_id AND eg.goods_id = gsid.goods_id ";
    	$res = $this->store_db->getArray($sql);
   	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
    	}
		
		if(!is_array($res) || !$res) return false;
		
		$ar_batchid = $ar_stockin = array();
			
		foreach($res as $r) {
			if(array_key_exists($r['batch_id'].'_'.$r['goods_id'],$ar_stockin)) {
				$ar_stockin[$r['batch_id'].'_'.$r['goods_id']]['quantity'] += $r['quantity'];
			} else {
				$ar_stockin[$r['batch_id'].'_'.$r['goods_id']] = $r;
			}
			
			$ar_batchid[] = $r['batch_id'];
		}
		
		if($ar_batchid && is_array($ar_batchid)) {
			$ar_batchid = array_unique($ar_batchid);
			
			//查询批次进货价
			$sql = "SELECT batch_id,cost_price FROM batch_details WHERE batch_id IN (".join(',',$ar_batchid).")";
			$r_batchprice = $this->store_db->getArray($sql);
			
			if($r_batchprice === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
			}
			
			$ar_batchprice = array();
			
			if(is_array($r_batchprice) && $r_batchprice) {
				foreach($r_batchprice as $val) {
					if(array_key_exists($val['batch_id'],$ar_batchprice)) {
						$ar_batchprice[$val['batch_id']][] = $val['cost_price'];
					} else {
						$ar_batchprice[$val['batch_id']][] = $val['cost_price'];
					}
				}
			
				if($ar_batchprice) {
					foreach($ar_batchprice as $key=>$val) {
						$ar_batchprice[$key] =  array_unique($val);
					}
					
					foreach($ar_batchprice as $key=>$val) {
						$i_count = count($val);
						$ar_batchprice[$key] = $i_count > 1 ? array_sum($val)/$i_count : $val[0];
					}
				}
			}
		}
		
		foreach($ar_stockin as &$val) {
			$val['price'] = isset($ar_batchprice[$val['batch_id']]) ? number_format($ar_batchprice[$val['batch_id']],2,'.','') : 0.00;
		}
		unset($ar_batchprice);
		

		//查询供应商
		$sql = "SELECT b.batch_id,b.supplier_id,s.supplier_name FROM batch b, suppliers s WHERE b.supplier_id = s.supplier_id AND b.confirm_status = 1";
		$r_supplier = $this->store_db->getArray($sql);
		
		if($r_supplier === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
		}

		$ar_supplier = array();

		foreach($r_supplier as $r) {
			$ar_supplier[$r['batch_id']]  = $r;
		}
		
		foreach($ar_stockin as &$val){
			$val['supplier_id']   = !empty($ar_supplier[$val['batch_id']]['supplier_id']) ? $ar_supplier[$val['batch_id']]['supplier_id'] : 0;
			$val['supplier_name'] = !empty($ar_supplier[$val['batch_id']]['supplier_name']) ? $ar_supplier[$val['batch_id']]['supplier_name'] : '';
			unset($val['goods_id'],$val['batch_id']);
		}

		$ar_res = array();

		foreach($ar_stockin as $val){

			$ar_res[$val['supplier_id']]['quantity']  += $val['quantity'] ? (int)$val['quantity'] : 0;
			$ar_res[$val['supplier_id']]['sum_price'] += $val['quantity']*$val['price'];

			if($this->app->cfg['cost']['standard']=='ON')
			{
				$stand = empty($val['standard_cost']) ? '0' : $val['standard_cost'];
				$ar_res[$val['supplier_id']]['sum_standard'] += $val['quantity']*$stand;
				$ar_res[$val['supplier_id']]['sum_standard']  = number_format($ar_res[$val['supplier_id']]['sum_standard'],2,'.','');
			}
			$ar_res[$val['supplier_id']]['quantity']    = $ar_res[$val['supplier_id']]['quantity'] ? $ar_res[$val['supplier_id']]['quantity'] : 0;
			$ar_res[$val['supplier_id']]['sum_price']   = number_format($ar_res[$val['supplier_id']]['sum_price'],2,'.','');
			$ar_res[$val['supplier_id']]['supplier_name']   = $val['supplier_name'];
		
		}

		return $ar_res;
    }
	
	/**
     * 查询进货统计明细
     * 
     * @param int $i_id  编号ID
	 * @param string  $s_act 查询明细对象
     * @return array|bool
     */
    public function getInStockGoods ($i_id, $s_act = 'batch'){
    	if($this->store_db == null || $this->db == null) {
			return false;
		}
		
		$i_id = (int)$i_id;
		
		if($i_id == 0) return false;
		
		if($s_act == 'supplier') {
			//查询供应商
			$sql = "SELECT b.batch_id,b.supplier_id,s.supplier_name as name FROM batch b, suppliers s WHERE b.supplier_id = s.supplier_id AND b.confirm_status = 1 AND b.supplier_id = $i_id";
		} else {
			//按批次查询
			$sql = "SELECT batch_id,batch_code as name FROM batch WHERE batch_id = $i_id AND confirm_status = 1";
		} 
		
		$res = $this->store_db->getArray($sql);
		
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
		}

		$ar = array();

		foreach($res as $r) {
			$ar['name']  = isset($r['name']) ? $r['name'] : '';
			$ar['batch_id'][] = $r['batch_id'];
		}
		
		if(empty($ar['batch_id'])) return false;
		
		//入库查询
		$sql = "SELECT gsi.stock_in_id,gsi.batch_id,gsid.goods_id,gsid.size,gsid.quantity FROM goods_stock_in gsi,goods_stock_in_details gsid WHERE stock_in_type = 1 AND stock_in_status =1 AND gsid.stock_in_id = gsi.stock_in_id AND gsi.batch_id IN (".JOIN(',',$ar['batch_id']).")";
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
    	}
		
		if(!is_array($res) || !$res) return false;
		
		$ar_stockin = array();
			
		foreach($res as $r) {
			if(array_key_exists($r['goods_id'].'_'.$r['size'],$ar_stockin)) {
				$ar_stockin[$r['goods_id'].'_'.$r['size']]['quantity'] += $r['quantity'];
			} else {
				$ar_stockin[$r['goods_id'].'_'.$r['size']] = $r;
			}
		}

			
		//查询批次进货价
		$sql = "SELECT batch_id,cost_price FROM batch_details WHERE batch_id IN (".join(',',$ar['batch_id']).")";
		$r_batchprice = $this->store_db->getArray($sql);
	
		if($r_batchprice === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
		}
			
		$ar_batchprice = array();
			
		if(is_array($r_batchprice) && $r_batchprice) {
			foreach($r_batchprice as $val) {
				if(array_key_exists($val['batch_id'],$ar_batchprice)) {
					$ar_batchprice[$val['batch_id']][] = $val['cost_price'];
				} else {
					$ar_batchprice[$val['batch_id']][] = $val['cost_price'];
				}
			}
		
			if($ar_batchprice) {
				foreach($ar_batchprice as $key=>$val) {
					$ar_batchprice[$key] =  array_unique($val);
				}
				
				foreach($ar_batchprice as $key=>$val) {
					$i_count = count($val);
					$ar_batchprice[$key] = $i_count > 1 ? array_sum($val)/$i_count : $val[0];
				}
			}
		}
		
		$ar_goodsid = array();
		
		foreach($ar_stockin as $val) {
			$ar_goodsid[] = $val['goods_id'];
		}

		if($ar_goodsid ) {
		
			//查询商品名称颜色尺寸
			$sql = "SELECT distinct(g.goods_id),g.goods_name,g.goods_sn,gu.color FROM ecs_goods g,ecs_goods_unique gu WHERE g.goods_id = gu.goods_id AND g.goods_id IN ('".join("','",$ar_goodsid)."')";
			
			$r_goods = $this->db->getArray($sql);
			
			if($r_goods === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
			}
		}
		  
		$ar_goods = array();
			
		foreach($r_goods as $r) {
			$ar_goods[$r['goods_id']] = $r;
		}

		foreach($ar_stockin as $key=>$val) {
			$ar_stockin[$key]['price']      = isset($ar_batchprice[$val['batch_id']]) ? number_format($ar_batchprice[$val['batch_id']],2,'.','') : 0.00;
			if($this->app->cfg['cost']['standard'] == 'ON')  //如果开启标准成本计算
			{
				$sql = "SELECT standard_cost FROM ecs_goods WHERE goods_id = ".$val['goods_id'];
				$r   = $this->db->getValue($sql);
				if($r === false)
				{
					return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
				}
				$ar_stockin[$key]['stand_price'] = $r ? number_format($r,2,'.','') : 0.00;
			}
			$ar_stockin[$key]['standard_price']  = number_format($val['quantity']*$ar_stockin[$key]['stand_price'],2,'.','');
			$ar_stockin[$key]['sum_price']  = number_format($val['quantity']*$ar_stockin[$key]['price'],2,'.','');
			$ar_stockin[$key]['goods_name'] = isset($ar_goods[$val['goods_id']]['goods_name']) ? $ar_goods[$val['goods_id']]['goods_name'] : '';
			$ar_stockin[$key]['goods_sn']   = isset($ar_goods[$val['goods_id']]['goods_sn']) ? $ar_goods[$val['goods_id']]['goods_sn'] : '';
			$ar_stockin[$key]['color']      = isset($ar_goods[$val['goods_id']]['color']) ? $ar_goods[$val['goods_id']]['color'] : '';
		}

		return array('name'=>$ar['name'],'data'=>$ar_stockin);
    }
    
    /**
     * 获取商品标准成本
     */
    public function getStandard()
    {
    	if($this->db == null) return false;
    }
    
    /**
     * 
     * 获取商品价格
     */
    public function getprice($goods_sn)
    {
    	if($this->db == null)  return false;
    	$sql = "SELECT shop_price FROM ecs_goods WHERE goods_sn = '$goods_sn'";
    	$res = $this->db->getValue($sql);
    	if($res === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
    
    /**
     * 获取商品条码
     */
    public function getbarcode($goods_id,$goods_sn,$size)
    {
    	if($this->db == null)  return false;
    	if($goods_id == 0)
    	{
    		$sql   = "SELECT goods_id FROM ecs_goods WHERE goods_sn LIKE '%$goods_sn%' ";
    		$r     = $this->db->getValue($sql);
    		$sql_t = "SELECT barcode FROM ecs_goods_unique WHERE goods_id = ".$r." AND size = '$size' ";
    		$res   = $this->db->getValue($sql_t);
    	}
    	else 
    	{
    		$sql = "SELECT barcode FROM ecs_goods_unique WHERE goods_id = ".$goods_id." AND size = '$size' ";
    		$res = $this->db->getValue($sql);
    	}
  	

    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
    	}
    	return $res;
    }
	
	
	/**
	 * 出库商品明细统计查询
	 * 
	 * @param array $ar_param 查询条件数组
	 * @param string $type    查询方式
	 * @return 
	 */
	public function getStockOutDetailCount($ar_param, $type = false) {
		if($this->union == null || $this->store_db == null || $this->db == null) return false;
		$where = ' AND 1';
		if(empty($ar_param) || !is_array($ar_param)) {
			return false;
		}

		if($ar_param['goods_sn']){
		   $where = $where." AND goods_sn = '".$ar_param['goods_sn']."'";
		}
        if($ar_param['goods_name']){
           $where = $where." AND goods_name = '".$ar_param['goods_name']."'";
        }
        
		$ar_order = array();
	
		if($type == 0 || $type == 5) {
		
			//查询分销订单
			$sql = "SELECT oi.order_sn,oi.consignee name,oi.shipping_time time,og.goods_id,og.goods_name,og.goods_sn,og.color,og.size,og.goods_number num FROM order_info oi,order_goods og ".
					"WHERE og.order_id = oi.order_id AND oi.order_status = 1 AND oi.shipping_status =1 AND oi.shipping_time >= $ar_param[start_time] AND oi.shipping_time <= $ar_param[end_time]".$where;

			if(!empty($ar_param['order_sn'])) {
				$sql .= " AND oi.order_sn = '$ar_param[order_sn]'";
			}
			
			if(!empty($ar_param['goods_id'])) {
				$sql .= " AND og.goods_id IN ($ar_param[goods_id])";
			}
     
			$ar_order = $this->union->getArray($sql);

			if($ar_order === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
			}
			
			if(!empty($ar_order)) {
				foreach($ar_order as $key=>$val) {
					$ar_order[$key]['description'] = '分销订单';
				}
			}
		}
		
		if($this->db == null) 
			  return  false;
		if($ar_param['goods_sn'])
		{
		  $sql = "SELECT goods_id FROM `ecs_goods` WHERE goods_sn = '".$ar_param['goods_sn']."'";
		  $goods_ids = $this->db->getValue($sql);
		}
		if($ar_param['goods_name'])
		{
		  $sql = "SELECT goods_id FROM `ecs_goods` WHERE goods_name LIKE '%".$ar_param['goods_name']."%'";
		  $goods_idn = $this->db->getValue($sql);
		}


		$condition = ' AND 1 ';
		if($goods_ids)
		{
		  $condition = $condition." AND goods_id = ".$goods_ids;
		}else if($goods_idn) {
		  $condition = $condition." AND goods_id = ".$goods_idn;
		}


		$ar_stockout = array();
		
		if(in_array($type,array(0,6,7,8))) {
		
			//查询进销存出库单
			$sql = "SELECT gso.stock_out_sn as order_sn,gso.confirm_time time,gso.description,gsod.goods_id,gsod.size,gsod.quantity num FROM goods_stock_out gso, goods_stock_out_details gsod ".
					"WHERE gso.stock_out_id = gsod.stock_out_id AND gso.confirm_time >= $ar_param[start_time] AND gso.confirm_time <= $ar_param[end_time] AND gso.confirm_status = 1 ".$condition;

			$sql .= ($type != 0) ? " AND gso.stock_out_type = $type" : " AND gso.stock_out_type > 5";
			
			if(!empty($ar_param['order_sn'])) {
				$sql .= " AND gso.stock_out_sn = '$ar_param[order_sn]'";
			}
			
			if(!empty($ar_param['goods_id'])) {
				$sql .= " AND gsod.goods_id IN ($ar_param[goods_id])";
			}
			
			if(!empty($ar_param['distributor']) && $ar_param['distributor'] != 0)
			{
				$sql .= " AND gso.out_person = '$ar_param[distributor]'";
			}

			$ar_stockout = $this->store_db->getArray($sql);
			
			if($ar_stockout === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
			}
			
			if(!empty($ar_stockout)){ 
				$ar_goodsid = $ar_stockoutsn =  array();
				
				foreach($ar_stockout as $val){
					$ar_goodsid[]    = $val['goods_id'];
					$ar_stockoutsn[] = $val['order_sn'];
				}
				
				if(!empty($ar_goodsid)) {
					$ar_goodsid = array_unique($ar_goodsid);
				}
				
				//查询商品信息
				$sql = "SELECT g.goods_id,g.goods_name,g.goods_sn,gu.color FROM ecs_goods g,ecs_goods_unique gu ".
						"WHERE g.goods_id = gu.goods_id AND g.goods_id IN (".JOIN(',',$ar_goodsid).")";
				$r_goods = $this->db->getArray($sql);
				
				if($r_goods === false ){
					return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
				}
				
				$ar_goods = array();
				
				if(!empty($r_goods)) {
					foreach($r_goods as $val) {
						$ar_goods[$val['goods_id']] = $val;
					}
				}
				unset($ar_goodsid,$r_goods);
				
				if(!empty($ar_stockoutsn)) {
					$ar_stockoutsn = array_unique($ar_stockoutsn);
				}
				
				//查询提货人
				$sql = "SELECT gso.stock_out_sn,au.user_name FROM goods_stock_out gso,admin_user au ".
						"WHERE gso.create_user_id = au.user_id AND gso.stock_out_type < 6 AND gso.stock_out_sn IN ('".JOIN("','",$ar_stockoutsn)."')";
				$r_admin = $this->store_db->getArray($sql);

				if($r_admin === false ){
					return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
				}
				
				$ar_admin = array();
				
				if(!empty($r_admin)) {
					foreach($r_admin as $val) {
						$ar_admin[$val['stock_out_sn']] = $val;
					}
				}
				unset($ar_stockoutsn,$r_admin);
				
				foreach($ar_stockout as & $val){
					$val['goods_sn']   = isset($ar_goods[$val['goods_id']]['goods_sn']) ? $ar_goods[$val['goods_id']]['goods_sn'] : '';
					$val['goods_name'] = isset($ar_goods[$val['goods_id']]['goods_name']) ? $ar_goods[$val['goods_id']]['goods_name'] : '';
					$val['color']      = isset($ar_goods[$val['goods_id']]['color']) ? $ar_goods[$val['goods_id']]['color'] : '';
					$val['name']       = isset($ar_admin[$val['order_sn']]['user_name']) ? $ar_admin[$val['order_sn']]['user_name'] : '';
				}
				
			}
		}

		return array_merge($ar_order,$ar_stockout);
	}
	
	/**
	 * 获取分销商信息
	 */
	public function getAllDistributor()
	{ 
	  if($this->store_db == null)  return false;
	  $sql = "SELECT distributor_id,distributor_name FROM `distributors`";
	  $res = $this->store_db->getArray($sql);
	  
	  if($res == false)  return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
	  
	  return $res;	  
	}
	
	/**
	 * 获取单品的出入库统计信息
	 */
	public function getInInfo($goods_sn,$ar_date)
	{
		if($this->store_db == null)  return false;
		//入库查询
		$sql  = "SELECT gsid.goods_id,gsi.batch_id, gsid.stock_in_id,eg.goods_name,eg.goods_sn,gsid.size,gsid.quantity,ag.agency_name,gsi.stock_in_type,au.user_name,gsi.description,gsi.confirm_time 
                 FROM goods_stock_in gsi, goods_stock_in_details gsid,lyceem.ecs_goods eg,agency ag, admin_user au 
                 WHERE gsi.stock_in_id = gsid.stock_in_id AND eg.goods_id = gsid.goods_id AND gsid.agency_id = ag.agency_id AND au.user_id = gsi.create_user_id AND eg.goods_sn = '$goods_sn' AND stock_in_status = 1
		";
		if($ar_date['start'] && $ar_date['end'])
		{
			$sql .= " AND gsi.confirm_time > '$ar_date[start]' AND gsi.confirm_time < '$ar_date[end]'";
		}
		$sql .= " ORDER BY gsi.confirm_time";

		$res = $this->store_db->getArray($sql);
		
		foreach($res as $key=>$val)
		{
			$confirm_date = date("Y-m-d H:i:s",$val['confirm_time']);	
			$res[$key]['confirm_time'] = $confirm_date;
			switch ($val['stock_in_type'])
			{
				case 1:  $res[$key]['stock_in_type'] = '供应商入库';break;
				case 2:  $res[$key]['stock_in_type'] = '员工提货退货入库';break;
				case 3:  $res[$key]['stock_in_type'] = '分销商退货入库';break;
				case 4:  $res[$key]['stock_in_type'] = '良品退货';break;
				case 5:  $res[$key]['stock_in_type'] = '不良品退货';break;
				case 6:  $res[$key]['stock_in_type'] = '调拨入库';break;
				default: $res[$key]['stock_in_type'] = '其它';
			}
			if($val['batch_id']!=0)
			{
				$sql = "SELECT batch_code FROM batch WHERE batch_id = ".$val['batch_id'];
				$r   = $this->store_db->getValue($sql);
				if($r == false)  return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
				$res[$key]['sn'] = $r;
			}
			else
			{
				$res[$key]['sn'] = '';
			}
		}
		if($res == false)  return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
	
		return $res;
	}
	
	
	/**
	 * 获取单品的出库统计信息
	 */
	public function getOutInfo($goods_sn,$ar_date)
	{
		if($this->store_db == null || $this->db == null)  return false;
		$sql_1 = "SELECT goods_id FROM ecs_goods WHERE goods_sn = '$goods_sn'";
		$goods_id = $this->db->getValue($sql_1);
		if($goods_id == false)  return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' getValue error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));

		//获取损益申请
		$sql_a = "SELECT stock_out_sn FROM goods_stock_out gso,goods_stock_out_details gsod 
                  WHERE gso.stock_out_id = gsod.stock_out_id AND gso.stock_out_type = 4 AND gso.confirm_status=1 AND gsod.goods_id = $goods_id";
		if($ar_date['start'] && $ar_date['end'])
		{
			$sql_a .= " AND gso.confirm_time > '$ar_date[start]' AND gso.confirm_time < '$ar_date[end]'";
		}

		$r1    = $this->store_db->getArray($sql_a); 

		if($r1)
		{
			foreach ($r1 as $v1)
			{
				$str .= $v1['stock_out_sn'].',';
			}
			$str = rtrim($str,',');
			$sql_b = "SELECT stock_out_id FROM goods_stock_out WHERE stock_out_type = 7 AND confirm_status = 1 AND stock_out_sn IN ($str)";  //获取分销商、损益相同的单子
			$r2    = $this->store_db->getArray($sql_b);
			foreach ($r2 as $v2)
			{
				$strs .= $v2['stock_out_id'].',';
			}
			$strs = rtrim($strs,',');
			//出库查询
			$sql   = "SELECT gsod.stock_out_id, eg.goods_name,eg.goods_sn,gsod.size,gsod.quantity,ag.agency_name,gso.stock_out_type,au.user_name,gso.description,gso.confirm_time  
		          FROM goods_stock_out gso,goods_stock_out_details gsod,lyceem.ecs_goods eg ,agency ag,admin_user au WHERE gso.stock_out_id = gsod.stock_out_id AND eg.goods_id = gsod.goods_id AND ag.agency_id = gsod.agency_id AND au.user_id = gso.create_user_id  
		          AND gso.confirm_status=1 ";
		   if($ar_date['start'] && $ar_date['end'])
		   {
				$sql .= " AND gso.confirm_time > '$ar_date[start]' AND gso.confirm_time < '$ar_date[end]'";
	 	   }
	 	   $sql .= " AND gsod.goods_id = $goods_id AND gso.stock_out_type IN (4,5,6,7,8) AND gso.stock_out_id NOT IN ($strs) ORDER BY gso.confirm_time";
	 	   
		}
		else 
		{
			//没有损益申请
			$sql = "SELECT gsod.stock_out_id,eg.goods_name,eg.goods_sn,gsod.size,gsod.quantity,ag.agency_name,gso.stock_out_type,au.user_name,gso.description,gso.confirm_time 
                FROM goods_stock_out gso,goods_stock_out_details gsod,lyceem.ecs_goods eg,agency ag,admin_user au
                WHERE gso.stock_out_id = gsod.stock_out_id AND eg.goods_id = gsod.goods_id AND gsod.agency_id = ag.agency_id AND au.user_id = gso.create_user_id AND eg.goods_sn = '$goods_sn'  AND confirm_status = 1
                 AND gso.stock_out_type IN (4,5,6,7,8) ";			
			if($ar_date['start'] && $ar_date['end'])
			{
				$sql .= " AND gso.confirm_time > '$ar_date[start]' AND gso.confirm_time < '$ar_date[end]'";
	 	    }
	 	    $sql .= " ORDER BY gso.confirm_time";
		}

		$res = $this->store_db->getArray($sql);
		
		
		foreach($res as $key=>$val)
		{
			$confirm_date = date("Y-m-d H:i:s",$val['confirm_time']);
			$res[$key]['confirm_time'] = $confirm_date;
			switch ($val['stock_out_type'])
			{
				case 1:  $res[$key]['stock_out_type'] = '供应商出库申请';break;
				case 2:  $res[$key]['stock_out_type'] = '分销商出库申请';break;
				case 3:  $res[$key]['stock_out_type'] = '员工出库申请';break;
				case 4:  $res[$key]['stock_out_type'] = '损益申请';break;
				case 5:  $res[$key]['stock_out_type'] = '不良品申请';break;
				case 6:  $res[$key]['stock_out_type'] = '供应商出库';break;
				case 7:  $res[$key]['stock_out_type'] = '分销商出库';break;
				default: $res[$key]['stock_out_type'] = '员工出库';
			}
			
			$sql = "SELECT stock_out_sn FROM goods_stock_out WHERE stock_out_id = ".$val['stock_out_id'];
			$r   = $this->store_db->getValue($sql);
			$res[$key]['sn'] = $r;
		}
		//print_r($res);
		if($res == false)  return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' execute error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
	     
		return $res;
	}
	
	/**
	 * 获取单品质检记录
	 */
	public function getQuantityControl($goods_sn,$ar_date)
	{
		if($this->store_db == null) return false;
		$sql ="SELECT gc.goods_name,gc.size,gc.bad_quantity,gc.efficacious_quantity,gc.type,gc.create_user_id,gc.confirm_time,gc.description,ad.user_name 
		FROM `stock_quality_control` gc,admin_user ad WHERE confirm_status = 1 AND gc.goods_sn = '$goods_sn' AND gc.create_user_id = ad.user_id";
		if($ar_date['start'] && $ar_date['end'])
		{
			$sql .= " AND gc.confirm_time > '$ar_date[start]' AND gc.confirm_time < '$ar_date[end]'";
		}
	
		$res = $this->store_db->getArray($sql);
		if($res == false)  return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' getArray error : '.$sql.'; mysql_error: '.mysql_error(), date("Y-m-d H:i:s")));
		foreach($res as $key=>$val)
		{
			$confirm_date = date("Y-m-d H:i:s",$val['confirm_time']);
			$res[$key]['confirm_time'] = $confirm_date;
			switch ($val['type'])
			{
				case 1:  $res[$key]['type'] = "良品转不良品";break;
				case 2:  $res[$key]['type'] = "<font color='red'>隐蔽良品</font>";break;
				case 3:  $res[$key]['type'] = "不良品转次品";break;
				case 4:  $res[$key]['type'] = "<font color='red'>隐蔽次品</font>";break;
			}
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
	    $log->reset()->setPath("modules/StockCountInfo")->setData($data)->write();
	    
	    return false;
	}
}	