<?php
/**
 * 库存数据处理类
 * 
 * @package modules
 * @author  鲍<chenglin.bao@lyceem.com>
 * @copyright 2010-6-30
 */

class StockInfo {

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
	 * 查询商品库存
	 *
	 * @param string $s_where 查询条件
	 * @return 
     */
	public function getGoodsStock($s_where) {
		if($this->store_db == null){
    		return false;
    	}

		$s_where = trim((string)$s_where);
    	
    	if($s_where == ''){
    		return false;
    	}

		$sql = "SELECT goods_id,size,efficacious_quantity,bad_quantity FROM goods_stock $s_where";
		$r   = $this->store_db->getArray($sql);
		
		if($r === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute select is fail:'.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	if(empty($r)){
    		return array();
    	}

    	$ar = array();
    	foreach($r as $v){
    		if(in_array($v['size'],array_keys($ar))){
    			$ar[$v['size']] += $v['efficacious_quantity'];
				$ar[$v['size'].'-bad'] += $v['bad_quantity'];
    		}else{
    			$ar[$v['size']] = $v['efficacious_quantity'];
				$ar[$v['size'].'-bad'] = $v['bad_quantity'];
    		}
    	}
		
    	
    	return $ar;
	}
	
	/**
	 * 条码与商品库存关系
	 */
	public function barstock()
	{
		if($this->store_db == null)  return false;
		$sql = "SELECT egu.barcode,sum(gs.quantity) as 'quantity' FROM goods_stock gs,lyceem.ecs_goods_unique egu WHERE egu.goods_id = gs.goods_id AND egu.size = gs.size GROUP BY egu.barcode";
		$res = $this->store_db->getArray($sql);
		if($res === false)
		{
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute select is fail:'.$sql, date("Y-m-d H:i:s")));
		}
		
		return $res;
	}
	
	
	/**
	 * 获取商品库存和批次id
	 * 
	 * @param  string  $s_where
	 * @return array|bool
	 */
	public function getGoodsStockBatchId($s_where) {
		if($this->store_db == null){
    		return false;
    	}
    	
    	if(is_null($s_where)){
    		return false;
    	}

    	$sql = "SELECT batch_id,efficacious_quantity as quantity FROM goods_stock $s_where";
    	$res = $this->store_db->getRow($sql);

    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute select is fail:'.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
	}
	
	/**
	 * 获取不同仓库的商品库存
	 * 
	 * @param  int $i_goodsid 商品id
	 * @return array|bool
	 */
	public function getAgencyGoodsStock($param,$act='') {
		if($this->store_db == null){
    		return false;
    	}
    	
    	if(is_null($param)){
    		return false;
    	}

    	if($act){
    		$sql = "SELECT agency_id, goods_id,size,efficacious_quantity AS sum_quantity FROM stock.goods_stock $param ";
    	}else{
    		$sql = "SELECT agency_id, sum( efficacious_quantity ) AS sum_quantity FROM stock.goods_stock WHERE goods_id = '$param' GROUP BY agency_id";
    	}
    	
	   // $this->writelog($sql);
    	$res = $this->store_db->getArray($sql);
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute select is fail:'.$sql, date("Y-m-d H:i:s")));
    	}
		
		if(empty($res)){
			return array();
		}
    	
    	return $res;
	}
	
	/**
	 * 更新商品库存
	 * 
	 * @param array  $arr 商品信息数组
	 * @param string $type :stock_in 进库操作,stock_out 出库操作,stock_update 更新操作
	 * @param string $bad : bad 不良品
	 * @return
	 */
	public function updateStock($type,$arr,$bad=''){
		if($this->store_db == null){
    		return false;
    	}
    	
    	if(empty($arr) || !is_array($arr)){
    		return false;
    	}

		//设置参数
		$agency_id = isset($arr['stock_id']) 	? $arr['stock_id']    : 1;
		$goods_id  = isset($arr['goods_id'])    ? $arr['goods_id']    : '';
		$quantity  = isset($arr['quantity'])    ? intval($arr['quantity'])    : 0;
		$quantity  = isset($arr['quatity'])     ? intval($arr['quatity'])     : $quantity;
		$color     = isset($arr['color'])       ? $arr['color']       : '';
		$color     = isset($arr['goods_color']) ? $arr['goods_color'] : $color;
		$size      = isset($arr['size'])        ? $arr['size']        : '';
		$size      = isset($arr['goods_size'])  ? $arr['goods_size']  : $size;
		$table_quantity = 'efficacious_quantity';
		
		if($bad=='bad'){
			$table_quantity = '`bad_quantity`';
		}
		
		//设置条件
		$where = " WHERE agency_id='$agency_id' AND goods_id='$goods_id' AND size='$size' ";

		//查询库存
		$sql = "SELECT goods_id FROM goods_stock ".$where;
		$res = $this->store_db->getValue($sql);
	
        $this->success_log(array(date("Y-m-d H:i:s"),__CLASS__ . '.class.php line ' . __LINE__ , ' function '. __FUNCTION__ .' act:'.$type.',data:'.serialize($arr)));
		
		if($res) {
			//入库操作
			if($type=='stock_in'){
				
				$sql = "update goods_stock set $table_quantity = ($table_quantity+$quantity),quantity = quantity + $quantity ".$where;		
				$res = $this->store_db->exec($sql);
		
				if(!$res){
					return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute sql is fail:'.$sql, date("Y-m-d H:i:s")));
				}
				importModule('LogSqs');
		        $logsqs=new LogSqs;	
				return true;
				
			}
			//出库操作
			else if($type=='stock_out'){

				//查询库存数量
				$sql = "SELECT $table_quantity FROM goods_stock".$where;
				$r_num = $this->store_db->getValue($sql);
	
				if($r_num < $quantity || $r_num === false) {
					return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'stock is too many:'.$r_num1, date("Y-m-d H:i:s")));
				}
	
				//执行出库操作
				$sql = "update goods_stock set $table_quantity = ($table_quantity-$quantity),quantity = quantity - $quantity".$where;			
				$res = $this->store_db->exec($sql);
				
				if(!$res){
					return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute sql is fail:'.$sql, date("Y-m-d H:i:s")));
				}
				importModule('LogSqs');
			    $logsqs=new LogSqs;
				return true;
			
			}
			//盘点更新库存操作
			else if($type=='stock_update'){
				//执行更新操作
				$sql = "update goods_stock set $table_quantity = $quantity,quantity = efficacious_quantity + bad_quantity ".$where;				
				$res = $this->store_db->exec($sql);
					
				if(!$res){
					return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute sql is fail:'.$sql, date("Y-m-d H:i:s")));
				}
				importModule('LogSqs');
				$logsqs=new LogSqs;
				return true;
			}
		}else if($type=='stock_in'|| $type=='stock_update'){
			$efficacious_quantity = ($table_quantity == 'quantity') ? $quantity : 0;
	
			//添加不存在的库存商品
			$sql = "insert into goods_stock(agency_id,goods_id,size,color,$table_quantity,efficacious_quantity)values".
					"('$agency_id','$goods_id','$size','$color','$quantity','$efficacious_quantity')";
			$res1 = $this->store_db->exec($sql);
			
			$sql = "insert into goods_stock_detail(agency_id,goods_id,size,$table_quantity,add_user_id,add_time)values('$agency_id','$goods_id','$size','$quantity',2,".time().")";
			$res2 = $this->store_db->exec($sql);
					
			if(!$res1 || !$res2){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute sql is fail:'.$sql, date("Y-m-d H:i:s")));
			}
		 importModule('LogSqs');
		 $logsqs=new LogSqs;
			
			return true;
		}else {
			return false;
		}
	}
    /**
     * 商品调拨
     * 
     */
    public function transferStock($arr){
        if($this->store_db == null){
            return false;
        }
        if(empty($arr) || !is_array($arr)){
            return false;
        }        

        //设置参数
        $agency_id = isset($arr['agency_id'])    ? $arr['agency_id']    : 1;
        if($agency_id == 1)return false;
        $goods_id  = isset($arr['goods_id'])    ? $arr['goods_id']    : '';
        if(!$goods_id)return false;
        $quantity  = isset($arr['quantity'])    ? intval($arr['quantity'])    : 0;
        $quantity  = isset($arr['quatity'])     ? intval($arr['quatity'])     : $quantity;
        $color     = isset($arr['color'])       ? $arr['color']       : '';
        $color     = isset($arr['goods_color']) ? $arr['goods_color'] : $color;
        $size      = isset($arr['size'])        ? $arr['size']        : '';
        $size      = isset($arr['goods_size'])  ? $arr['goods_size']  : $size;
        if(!$size)return false;
        //获取主仓的冻结数量
        $a_where = array('goods_id'=>$goods_id,'size'=>$size,'agency_id'=>1);
        $freeze = $this->getFreezeStock($a_where);

        // $table_quantity = 'efficacious_quantity';
        
        if($bad=='bad'){
            $table_quantity = '`bad_quantity`';
        }
        $sql = "LOCK TABLES goods_stock WRITE";
        $res = $this->store_db->exec($sql);
        
        //设置条件
        $where = " WHERE agency_id = $agency_id AND goods_id='$goods_id' AND size='$size' ";

        //查询库存
        $sql = "SELECT goods_id FROM goods_stock ".$where;
        $res_goods_id = $this->store_db->getValue($sql);
        
        //查询总仓库和当前仓库的和
        $where = " where agency_id in (1,$agency_id) and goods_id='$goods_id' and size='$size' ";
        $sql = "select agency_id,efficacious_quantity from goods_stock ".$where;
        $sql .= " ORDER BY agency_id ASC";
        $res = $this->store_db->getArray($sql);

        $main_num = $res[0]['efficacious_quantity'];  //主仓数量
        $part_num = $res[1]['efficacious_quantity'];  //分仓数量
        
        $ar_quantity = array();
        $res_quantity = 0;
        if(!empty($res) && is_array($res)){
            foreach ($res as $val) {
                $ar_quantity[$val['agency_id']] = $val['efficacious_quantity'];    
                $res_quantity +=  $val['efficacious_quantity'];         
            }
        }
        if($res_quantity<0)return false;

        //判断数量是不是超过和
        if($res_quantity < $quantity)return false;
        //判断数据是不是改变
        if($ar_quantity[$agency_id] == $quantity)return false;
        $i_increment = $quantity - $ar_quantity[$agency_id];//增量
        $i_current_quantity = !empty($ar_quantity[$agency_id]) ? $ar_quantity[$agency_id] + $i_increment : $i_increment;
        
        $i_left = $res_quantity - $quantity;
        
        //获取差额
        $diff = $quantity - $part_num - $main_num + $freeze;
        if($diff > 0)
        {
        	$available_stock = $main_num - $freeze;
        	return array('availble'=>$available_stock,'status'=>0);
        }
        
        
        //更新分仓库
        $where = " where agency_id='$agency_id' and goods_id='$goods_id' and size='$size' ";
        if($res_goods_id){
            $sql = "update goods_stock set efficacious_quantity='$quantity',quantity=bad_quantity+'$quantity'".$where;
        }
        else{
            $sql = "insert into goods_stock (efficacious_quantity,quantity,size,color,agency_id,goods_id)values".
            "('$quantity','$quantity','$size','$color','$agency_id','$goods_id')";   
        }
        $res = $this->store_db->exec($sql);
        if(!$res)return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'update stock error:'.$sql, date("Y-m-d H:i:s")));
        
        //更新主仓
        $sql = "update goods_stock set efficacious_quantity='$i_left',quantity=bad_quantity+'$i_left' where agency_id=1 and goods_id='$goods_id' and size='$size'";
        $res = $this->store_db->exec($sql);
        if(!$res)return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'update stock error:'.$sql, date("Y-m-d H:i:s")));  
        
        $sql = "UNLOCK TABLES";
        $res = $this->store_db->exec($sql);  
        $i_act_time = time();        
        $sql = "insert into goods_stock_transfer_log (agency_id,act_time,goods_id,size,color,increment,current_quantity,goods_sn)values('$agency_id','$i_act_time','$goods_id','$size','$color','$i_increment','$i_current_quantity','')";
        $this->store_db->exec($sql);
        
        
        importModule('LogSqs');
        $logsqs=new LogSqs;
        $this->success_log(array(date("Y-m-d H:i:s"),__CLASS__ . '.class.php line ' . __LINE__ , ' function '. __FUNCTION__ .' act:'.$type.',data:'.serialize($arr)));
        
        return true;
    }
	
	/**
	 * 查询所有库存预警商品
	 *
	 * @param int $i_leavel
	 * @return array|bool
	 */
	public function getWaringStock($i_leavel = 10){
		if($this->store_db == null){
    		return false;
    	}
		
		if(!is_int($i_leavel)){
			return false;
		}
		
		$sql = "SELECT * FROM goods_stock WHERE efficacious_quantity <= 10";
		$res = $this->store_db->getArray($sql);
		
		if(!$res){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute select is fail:'.$sql, date("Y-m-d H:i:s")));
    	}
		
		if(!is_array($res) || count($res) == 1){
			return false;
		}
		
		//设置数组
		$ar_res = array();
		foreach($res as $r){
			if(in_array($r['agency_id'],array_keys($ar_res))){
				if(in_array($r['goods_id'],array_keys($ar_res[$r['agency_id']]))){
					if(in_array($r['size'],array_keys($ar_res[$r['agency_id']][$r['goods_id']]))){
						$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['goods_id'] = $r['goods_id'];
						$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['size'] = $r['size'];
						$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['color'] = $r['color'];
						$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['agency_id'] = $r['agency_id'];
						$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['batch_id'] = $r['batch_id'];
						$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['efficacious_quantity'] += $r['efficacious_quantity'];
					}else{
						$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['goods_id'] = $r['goods_id'];
						$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['size'] = $r['size'];
						$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['color'] = $r['color'];
						$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['agency_id'] = $r['agency_id'];
						$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['batch_id'] = $r['batch_id'];
						$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['efficacious_quantity'] = $r['efficacious_quantity'];
					}
				}else{
					$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['goods_id'] = $r['goods_id'];
					$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['size'] = $r['size'];
					$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['color'] = $r['color'];
					$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['agency_id'] = $r['agency_id'];
					$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['batch_id'] = $r['batch_id'];
					$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['efficacious_quantity'] = $r['efficacious_quantity'];
				}
			}else{
				$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['goods_id'] = $r['goods_id'];
				$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['size'] = $r['size'];
				$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['color'] = $r['color'];
				$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['agency_id'] = $r['agency_id'];
				$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['batch_id'] = $r['batch_id'];
				$ar_res[$r['agency_id']][$r['goods_id']][$r['size']]['efficacious_quantity'] = $r['efficacious_quantity'];
			}
		}
		unset($res);
		
		return $ar_res;
	}
	
	/**
	 * 获取商品的库存总量
	 * 
	 * @param  int     $i_goodsid  商品id
	 * @param  string  $size       商品尺寸
	 * @param  int     $i_agencyid 仓库id
	 * @param  string  $s_field    查询库存对象
	 * @return array|bool 
	 */
 	public function getGoodsStockNum($i_goodsid, $size = '', $i_agencyid = 1,$s_field = 'quantity'){
    	if($this->store_db == null){
			return false;
    	}

		$i_goodsid  = (int)$i_goodsid;
		$i_agencyid = (int)$i_agencyid;
		$size       = trim((string)$size);
		$s_field    = trim((string)$s_field);
		
		if($i_goodsid == 0){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' parameter error'.$i_goodsid , date("Y-m-d H:i:s")));
		}
		
		if($i_agencyid != 0){
			if( $size == '' ) {
				$sql = "SELECT SUM($s_field) FROM goods_stock WHERE goods_id = $i_goodsid  AND agency_id = $i_agencyid";
			} else {
				$sql = "SELECT SUM($s_field) FROM goods_stock WHERE goods_id = $i_goodsid AND size = '".$size."' AND agency_id = $i_agencyid";
			}
		}else{
			$sql = "SELECT SUM($s_field) FROM goods_stock WHERE goods_id = $i_goodsid";
		}
	
		$res = $this->store_db->getValue($sql);

		//$res = array_sum($res);
		
		if($res === false){
			return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql = '.$sql , date("Y-m-d H:i:s")));	
		}
		
		return $res;
    }
	
	/**
	 * 获取商品的仓库
	 * 
	 * @param  int     $i_goodsid  商品id
	 * @param  int     $i_batchid  批次id
	 * @return array|bool 
	 */
 	public function getGoodsStockAgency($i_goodsid,$i_batchid){
    	if($this->store_db == null){
			return false;
    	}

		$i_goodsid = (int)$i_goodsid;
		$i_batchid = (int)$i_batchid;
		
		if($i_goodsid == 0 || $i_batchid == 0){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' parameter error'.$i_goodsid , date("Y-m-d H:i:s")));
		}

		$sql = "SELECT agency_id FROM goods_stock WHERE goods_id = $i_goodsid AND batch_id = $i_batchid";

		$res = $this->store_db->getValue($sql);
		
		if($res === false){
			return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql = '.$sql , date("Y-m-d H:i:s")));	
		}
		
		return $res;
    }
    
    /**
     * 添加库存调拨
     * 
     * @param $ar_data 调拨数据
     * @return bool
     */
    public function insertAllocate($ar_data){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	if(!is_array($ar_data) || empty($ar_data)){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' parameter error' , date("Y-m-d H:i:s")));
    	}
    	
    	foreach($ar_data as $val){
    		$s_key = JOIN(',',array_keys($val));
    		$ar_val[] = "('".JOIN("','",array_values($val))."')";
    	}
    	
    	$sql = "INSERT INTO allot_log (".$s_key.") VALUES ".JOIN(",",$ar_val);
    	$res = $this->store_db->exec($sql);

    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql error'.$sql , date("Y-m-d H:i:s")));
    	}
    	
    	if($res){
    		importModule('LogSqs');
		 	$logsqs=new LogSqs;
    		return ture;
    	}
    	
    	return false;
    	
    }
    
     /**
     * 查询已调拨数据
     * 
     * @param string $s_where 查询条件
     * @return array
     */
    public function getAllcoateInfo($s_where,$do = ''){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	if(!is_string($s_where) || $s_where == ''){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' parameter error'.$s_where , date("Y-m-d H:i:s")));
    	}
    	
		if($do == 'num'){
			$sql = ' SELECT COUNT(allot_log_id)'.
			       ' FROM allot_log '.$s_where;
			$res = $this->store_db->getValue($sql);
		 }else{
			$sql = ' SELECT allot_log_id,allot_sn,goods_id,size,quantity,from_agency,to_agency,create_user_id,create_time,type,confirm_user_id,confirm_time,confirm_status '.
			       ' FROM allot_log '. $s_where; 
			$res = $this->store_db->getArray($sql);
		 }
		
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql error'.$sql , date("Y-m-d H:i:s")));
		}
		
		return $res;
    }
	
	/**
     * 是否可以审核
     * 
     * @param string $s_allotid id
     * @return bool		   
     */
    public function canAudit($s_allotid)
	{
		if($this->store_db == null)
		{
    		return false;
    	}
		
		$s_allotid = (string)$s_allotid;
		if(empty($s_allotid))
		{
			return false;	
		}
		
		$sql = "SELECT confirm_status FROM allot_log WHERE allot_log_id IN(". $s_allotid .")";
		$r   = $this->store_db->getColumn($sql);
		
		if($r === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'sql error ,sql = '.$sql , date("Y-m-d H:i:s")));
    	}
		
		if(!is_array($r) || count($r) == 0 || in_array(1 , $r) || in_array(2 , $r))
		{
			return false;	
		}
		
		return true;
	}
	
	
    /**
     * 调拨审核
     * 
     * @param string $s_allotid id
     * @param int    $i_userid  操作审核人id
     * @return int|bool		    成功返回影响行数,失败返回false
     */
    public function editAudit($s_allotid , $i_userid){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$s_allotid = (string)$s_allotid;
    	$i_userid  = (int)$i_userid;
    	
	    if(empty($s_allotid) || $i_userid == 0){
    		return false;
    	}
		
		//查询调拨数据
		$sql = "SELECT * FROM allot_log WHERE allot_log_id IN (" . $s_allotid . ")";
		$res = $this->store_db->getArray($sql);
		
    	if($res === false || !is_array($res)){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute update is fail:'.$res, date("Y-m-d H:i:s")));
    	}
		
		if(count($res) < 1) return '调拨数据为空,请仔细检查!';
		
		//构建数组
		$ar_data = array();
		
    	foreach($res as $val){
			$color = $this->getGoodsColor($val['goods_id']);
			$color = ($color) ? $color : '';
			
			$key = $val['from_agency'].'_'.$val['goods_id'].'_'.$val['size'];
			
    		$ar_data[$key]['goods_id'] = $val['goods_id'];
    		$ar_data[$key]['size'] = $val['size'];
			$ar_data[$key]['color'] = $color;
			$ar_data[$key]['agency_id'] = $val['from_agency'];
			$ar_data[$key]['go_agency'] = $val['to_agency'];
			$ar_data[$key]['type'] = $val['type'];
			
			if(array_key_exists($key,$ar_data)) {
				$ar_data[$key]['quantity'] += $val['quantity']; 
			} else {
				$ar_data[$key]['quantity'] = $val['quantity'];
			}
			
			unset($key);
    	}
		
		if(empty($ar_data))return '调拨数据为空,请仔细检查!';
		
		$this->success_log(array(date("Y-m-d H:i:s"),__CLASS__ . '.class.php line ' . __LINE__ , ' function '. __FUNCTION__ .' user_id:'.$i_userid.' data:'.serialize($ar_data)));
		
		$res = $this->allocateUpdateStock($ar_data);
		
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute update is fail:'.$res, date("Y-m-d H:i:s")));
		}

		if($res !== true) return $res;

    	$sql = "UPDATE allot_log SET confirm_status = 1,confirm_user_id = $i_userid,confirm_time = " . time() . " WHERE allot_log_id IN (" . $s_allotid . ")";
    	$res = $this->store_db->exec($sql);
 
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute update is fail:'.$res, date("Y-m-d H:i:s")));
    	}
    	importModule('LogSqs');
		$logsqs=new LogSqs;
    	return $res;
    }
	
	/**
     * 调拨移除
     * 
     * @param string $s_allotid id
     * @param int    $i_userid  操作审核人id
     * @return int|bool		    成功返回影响行数,失败返回false
     */
    public function removeAllocate($s_allotid , $i_userid){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$s_allotid = (string)$s_allotid;
    	$i_userid  = (int)$i_userid;
    	
	    if(empty($s_allotid) || $i_userid == 0){
    		return false;
    	}
    	
    	$sql = "UPDATE allot_log SET confirm_status = 2,confirm_user_id = $i_userid,confirm_time = " . time() . " WHERE allot_log_id IN (" . $s_allotid . ")";
    	$res = $this->store_db->exec($sql);
 
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute update is fail:'.$res, date("Y-m-d H:i:s")));
    	}
		importModule('LogSqs');
		$logsqs=new LogSqs;
    	
    	return $res;
    }
	
	/**
     * 更新解冻商品的数量
     * 
     * @param string $s_allotid id
     * @param int    $i_userid  操作审核人id
     * @return int|bool		    成功返回影响行数,失败返回false
     */
    public function updateRelease($fnum , $fid, $fsize, $fagency_id){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$s_allotid = (string)$s_allotid;
    	$i_userid  = (int)$i_userid;
    	
	    if(empty($fnum) || empty($fid) || empty($fsize) || empty($fagency_id)){
    		return false;
    	}
    	$sql="UPDATE `goods_stock` SET  `releaseQuantity` =  '$fnum' WHERE `goods_id` ='$fid' AND `size` =  '$fsize' AND  `agency_id` ='$fagency_id'";
    	//$sql = "UPDATE allot_log SET confirm_status = 2,confirm_user_id = $i_userid,confirm_time = " . time() . " WHERE allot_log_id IN (" . $s_allotid . ")";
    	$res = $this->store_db->exec($sql);
    	return $res;
    }
	
	/**
	 * 添加数据调拨
	 *
	 * @param $ar_data 调拨商品
	 * @return
	 */
	public function allocateUpdateStock($ar_data){
		if($this->store_db == null){
    		return false;
    	}
	
		if(empty($ar_data) || !is_array($ar_data)){
			return '调拨数据为空,请仔细检查!';
		}

		//盘断商品库存
		foreach($ar_data as $val){
    		if(empty($val['goods_id']) || empty($val['agency_id']) || empty($val['size'])) {
				$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'data is not full:'.serialize($val), date("Y-m-d H:i:s")));
				return '调拨数据不全,请仔细检查!';
    		}
			
			//查找商品 是否有库存 
			$r = $this->findStock($val,'goods_stock','sum(efficacious_quantity)');
			
			if(!$r || $r <  $val['quantity']){
				$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' stock is not empay ; data:'.serialize($val), date("Y-m-d H:i:s")));
				return '库存不足,请仔细检查!';
			}
    	}
		
		foreach($ar_data as $val){
			//减少来源库存 
			if($val['type']==1)//不良品
			{
				$sql = "UPDATE goods_stock SET quantity =quantity - ". $val['quantity'] .",bad_quantity = bad_quantity - ".$val['quantity'].
					" WHERE agency_id = ".$val['agency_id']." AND goods_id =". $val['goods_id'] ." AND size ='".$val['size']."'";
			}
			else{
				$sql = "UPDATE goods_stock SET quantity =quantity - ". $val['quantity'] .",efficacious_quantity = efficacious_quantity - ".$val['quantity'].
					" WHERE agency_id = ".$val['agency_id']." AND goods_id =". $val['goods_id'] ." AND size ='".$val['size']."'";
			}
			
			$res1 = $this->store_db->exec($sql);
			
			if(!$res1) {
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql:'.$sql, date("Y-m-d H:i:s")));
			}
			importModule('LogSqs');
			$logsqs=new LogSqs;
			
			$val['agency_id'] = !empty($val['go_agency']) ? $val['go_agency'] : $val['agency_id'];
			
			//增加发配库存 
			if($this->findStock($val,'goods_stock')){
				if($val['type']==1)//不良品
				{
					$sql = "UPDATE goods_stock SET quantity =quantity + ". $val['quantity'] .",bad_quantity = bad_quantity + ".$val['quantity'].
				" WHERE agency_id = ".$val['agency_id']." AND goods_id =". $val['goods_id'] ." AND size ='".$val['size']."'";
				}
				else{
					$sql = "UPDATE goods_stock SET quantity =quantity + ". $val['quantity'] .",efficacious_quantity = efficacious_quantity + ".$val['quantity'].
				" WHERE agency_id = ".$val['agency_id']." AND goods_id =". $val['goods_id'] ." AND size ='".$val['size']."'";
				}
			
			}else{
				if($val['type']==1)//不良品
				{
					$sql = "insert into goods_stock (goods_id,size,color,agency_id,quantity,bad_quantity) values ".
				"($val[goods_id],'".$val[size]."','".$val[color]."',$val[agency_id],$val[quantity],$val[quantity])";
				}
				else {
					$sql = "insert into goods_stock (goods_id,size,color,agency_id,quantity,efficacious_quantity) values ".
				"($val[goods_id],'".$val[size]."','".$val[color]."',$val[agency_id],$val[quantity],$val[quantity])";
				}
			
			}

			$res2 = $this->store_db->exec($sql);
							
			if(!$res2){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql:'.$sql, date("Y-m-d H:i:s")));
			}
			importModule('LogSqs');
			$logsqs=new LogSqs;
    	}
		importModule('LogSqs');
		$logsqs=new LogSqs;
		
		return true;
	}
	
	/**
	 * 库存查询
	 * 
	 * @param  array $ar_data
	 * @param  string $s_table 
	 * @return bool
	 */
	public function findStock($ar_data,$s_table,$s_filed = 'count(*)'){
		if($this->store_db == null){
    		return false;
    	}
		
		$s_table = (string)$s_table;
		
		if(!is_array($ar_data) || count($ar_data) < 1 || $s_table == ''){
			return false;
		}
    	
		$sql = "select $s_filed from $s_table where goods_id =".$ar_data['goods_id']." and size = '".$ar_data['size']."' and agency_id = ". $ar_data['agency_id'];

    	$r = $this->store_db->getValue($sql);

    	if($r && $r > 0){
    		return $r;
    	}
    	
    	return false;
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
	  * 根据商品id获取商品尺寸
	  * 
	  * @param int $goods_id
	  * @return 
	  */
	 public function getGoodsStockSizeById($goods_id) {
	 	if($this->store_db == null){
    		return false;
    	}
		
		if($goods_id < 1) {
			return false;
		}
		
		$sql = "select size from goods_stock where goods_id = $goods_id";
		$res = $this->store_db->getColumn($sql);
		
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute select is fail:'.$res, date("Y-m-d H:i:s")));
		}
		
		return $res;
	 }
     /**
      * 按条件查询库存
      */
	 public function getStockInfo($arr){
	    if($this->store_db == null){
            return false;
        }
        if(empty($arr) || !is_array($arr)){
            return false;
        } 
        //设置参数
        $agency_id = isset($arr['agency_id'])    ? $arr['agency_id']    : 0;
        $goods_id  = isset($arr['goods_id'])    ? $arr['goods_id']    : '';
        $color     = isset($arr['color'])       ? $arr['color']       : '';
        $color     = isset($arr['goods_color']) ? $arr['goods_color'] : $color;
        $size      = isset($arr['size'])        ? $arr['size']        : '';
        $size      = isset($arr['goods_size'])  ? $arr['goods_size']  : $size;
        
        $where = " where 1 ";
        if(!empty($agnecy_id)){
            if(is_array($agency_id)){
                $agency_id = implode(",", $agency_id);
                $where .= " and agency_id in($agency_id)";            
            }
            else{$where .= " and agency_id='$agency_id'";}
        }
        
        if(!empty($goods_id)){
            if(is_array($goods_id)){
                $goods_id = implode(",", $goods_id);
                $where .= " and goods_id in($goods_id)";            
            }
            else{$where .= " and goods_id='$goods_id'";}
        }
        if($color)$where .= " and color='$color'";
        if($size)$where .= " and size='$size'";
        
        $sql = "select * from goods_stock".$where;
        // exit;
        $res = $this->store_db->getArray($sql);
        if(!$res)return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'query stock error:'.$sql, date("Y-m-d H:i:s")));    
        
        return $res;
	 }
	 /**
	  * 查询库存数
	  */
	 public function getStockTotal() {
	 	if($this->store_db == null){
    		return false;
    	}
    	
    	$sql = "SELECT SUM(quantity) sum,SUM(bad_quantity) bad_quantity,SUM(efficacious_quantity) quantity FROM goods_stock";
    	$res = $this->store_db->getRow($sql);
    	
    	if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute select is fail:'.$res, date("Y-m-d H:i:s")));
		}
		
		return $res;
	 }
	 
 	/**
     * 查询被冻结库存
     * 
     * @param array $ar_where 
     * @return int|bool
     */
    public function getFreezeStock($ar_where) {
    	if($this->store_db == null || $this->union == null){
    		return false;
    	}
		
    	if(empty($ar_where)) return false;
		
		$s_where = "";
		
		foreach($ar_where as $key=>$val) {
			if(!empty($val)) {
				$s_where .= " AND $key = '$val'";
			}
		}
    	
		//查询提货未出库商品
/*    	$sql = "SELECT SUM(gsod.quantity) FROM goods_stock_out gso,goods_stock_out_details gsod ".
    		   " WHERE gsod.stock_out_id = gso.stock_out_id AND gso.confirm_status = 0 $s_where ";
    	$res = $this->store_db->getValue($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}*/
		
		
		
/*
		$sql = "SELECT stock_out_sn FROM goods_stock_out g, goods_stock_out_details gd WHERE gd.stock_out_id = g.stock_out_id ".
                "AND g.confirm_status IN (0,1) AND g.`stock_out_type` >= 6 $s_where ";
				*/
		//echo($sql);
		$sql ="SELECT a.stock_out_sn FROM goods_stock_out a  INNER JOIN (
						SELECT stock_out_sn 
						FROM 
							goods_stock_out g, goods_stock_out_details gd 
						WHERE 
							gd.stock_out_id = g.stock_out_id AND g.confirm_status IN (0,1) AND g.`stock_out_type` < 6 $s_where
					) b ON a.stock_out_sn = b.stock_out_sn WHERE a.`stock_out_type`>=6 and a.confirm_status =1 ORDER BY stock_out_id DESC";  // LIMIT 0,1


					
//echo($sql);
		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
		$res = $this->store_db->getColumn($sql);
		
		if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}

		$sql =  "SELECT SUM( gsod.quantity ) FROM goods_stock_out gso, goods_stock_out_details gsod WHERE gsod.stock_out_id = gso.stock_out_id ".
		        "AND gso.confirm_status IN (0,1) AND gso.`stock_out_type` < 6  $s_where ";
		
		if(!empty($res) && is_array($res)) {
			$res = array_unique($res);
			$sql .= " AND gso.stock_out_sn NOT IN (".JOIN(',',$res).")";
		}
		
		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
		$res = $this->store_db->getValue($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}

		
		//查询订单未出库商品
		/*$sql = "SELECT SUM(og.goods_number) FROM order_info oi,order_goods og WHERE og.order_id = oi.order_id ".
			   " AND og.extension_code = '' AND oi.order_status IN (0,1) AND oi.shipping_status IN (0,3) $s_where";
		
	$sql = "SELECT SUM(og.goods_number) FROM order_info oi,order_goods og WHERE og.order_id = oi.order_id ".
			   " AND og.extension_code = '' AND oi.order_status =1 AND oi.shipping_status IN (0,3) $s_where";
*/
	$sql = "SELECT SUM(og.goods_number) FROM order_info oi,order_goods og WHERE og.order_id = oi.order_id ".
			   " AND og.extension_code = '' AND oi.order_status =1 AND ( oi.shipping_status = 0 or oi.shipping_status =3 ) $s_where";			   
//2012-06-13 17:40    -- 谢佐福  Resion:   冻结库存应该是已经确认了的，而非未确认的
	
//die($sql);	
		$r_order = $this->union->getValue($sql);
    	
    	if($r_order === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}	   

    	return (int)($res+$r_order);
    }
    
    /**
     * 获取日志
     */
    public  function getLogs($condition)
    {   
    	if($this->store_db==null || $this->db == null) return false;
    	$sql = "SELECT goods_id FROM ecs_goods WHERE goods_sn ='".$condition['goods_sn']."'";
    	$res = $this->db->getValue($sql);
    
    	$sql = "SELECT * FROM `admin_log` WHERE log_info LIKE '%stock%商品编号:".$res."%' AND log_time > '".$condition[start]."' AND log_time < '".$condition[end]."' ORDER BY log_time DESC";
    	$r = $this->store_db->getArray($sql);
    	if($r === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $r;
    	
    }
    
    /**
     * 获取单品的库存
     */
    public function getOneStock($goods_id,$size)
    {
    	if($this->store_db==null) return false;
    	$sql = "SELECT SUM(quantity) FROM `goods_stock` WHERE goods_id = $goods_id AND size ='$size'";
    	$res = $this->store_db->getValue($sql);
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	return $res;
    }
    
    /**
     * 获取商品信息
     */
    public function getGoodsInfo($goods_id)
    {
    	if($this->db == null) return false;
    	$sql = "SELECT goods_sn,goods_name FROM ecs_goods WHERE goods_id = ".$goods_id;
    	$res = $this->db->getArray($sql);
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
    
    /**
     * 获取订单号
     */
    public function getOrder_sn($order_id)
    {
    	if($this->union == null) return false;
    	$sql = "SELECT order_sn FROM order_info WHERE order_id = ".$order_id;
    	$res = $this->union->getValue($sql);
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	return $res;
    }
    
    
    /**
     * 获取商品名称
     */
    public function getGoods_name($goods_id)
    {
    	if($this->db == null) return false;
    	$sql = "SELECT goods_name FROM ecs_goods WHERE goods_id = $goods_id";
    	
    	$res = $this->db->getValue($sql);
    	
    	if($res == false)
    	{
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
    
    /**
     * 获取商品颜色
     */
    public function getGoods_color($goods_id,$size)
    {
    	if($this->db == null) return false;
    	$sql = "SELECT color FROM ecs_goods_unique WHERE goods_id = $goods_id AND size = '".$size."'";
    	$res = $this->db->getValue($sql);
    	 
    	if($res == false)
    	{
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    	
    }
    
    /**
     * 获取订单出库信息
     */
    public function getOrderGoods($where)
    {
    	if($this->union == null) return false;
    	$sql = "SELECT ogld.goods_id,eg.goods_name,eg.goods_sn,ogld.size,ogld.quantity,FROM_UNIXTIME(ogl.confirm_time,'%Y-%m-%d') as time 
    	FROM lyceem_distribution.order_goods_logistics ogl,lyceem_distribution.order_goods_logistics_details ogld,lyceem.ecs_goods eg ".$where;
    	$res = $this->union->getArray($sql);
        if($res===false)
    	{
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	return $res;
    }
    
    /**
     * 获取进销存出库
     */
    public function getGoodserp($where)
    {
    	if($this->store_db == null) return false;
    	$sql = "SELECT gsod.goods_id,eg.goods_name,eg.goods_sn,gsod.size,abs(gsod.quantity) as quantity,FROM_UNIXTIME(gso.confirm_time,'%Y-%m-%d') as time
    	FROM goods_stock_out gso,goods_stock_out_details gsod,lyceem.ecs_goods eg".$where;
    	$res = $this->store_db->getArray($sql);
    	if($res===false)
    	{
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	return $res;
    }
    
    /**
     * 获取订单退货入库
     */
    public function getRefund($where)
    {
    	if($this->union == null) return false;
    	$sql = "SELECT T.goods_id,G.goods_name,G.goods_sn,T.size,(T.num+T.bad_num) as quantity,FROM_UNIXTIME(T.confirm_time,'%Y-%m-%d') AS time FROM order_refund T,lyceem.ecs_goods G ".$where;
        $res = $this->union->getArray($sql);
        if($res===false)
    	{
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	return $res;   	
    }
    
    /**
     * 获取进销存入库
     */
    public function getInGoods($where)
    {
    	if($this->store_db == null) return false;
    	$sql = "SELECT gsid.goods_id,eg.goods_name,eg.goods_sn,gsid.size,gsid.quantity,FROM_UNIXTIME(gsi.confirm_time,'%Y-%m-%d') AS time FROM goods_stock_in gsi,goods_stock_in_details gsid,lyceem.ecs_goods eg".$where;
    	$res = $this->store_db->getArray($sql);
    	if($res===false)
    	{
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute  sql error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	return $res; 
    }
    
    
    /**
     * 获取标准成本
     * @param  $goos_id
     * @return $res
     */
    public function getStandardCost($goods_id)
    {
    	if($this->db==NULL) return false;
    	$sql = "SELECT standard_cost FROM ecs_goods WHERE goods_id =".$goods_id;
    	$res = $this->db->getValue($sql);
    	if($res===false)
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
	    $log->reset()->setPath("success/StockInfo")->setData($data)->write();
	}
	
	/**
	 * 数据更新失败记录日志，并标识操作失败
	 *
	 * @param 	Array 	$data
	 * @return 	bool	false
	 */
	private function _log($data){
	    $log = $this->app->log();
	    $log->reset()->setPath("modules/StockInfo")->setData($data)->write();
	    
	    return false;
	}
	
	//日志测试
	public function writelog($data)
	{
		$sql = "INSERT INTO _t_stock.admin_log(log_info)VALUES('$data')";
		//echo $sql;exit;
		$this->store_db->exec($sql);
	}
}
?>	