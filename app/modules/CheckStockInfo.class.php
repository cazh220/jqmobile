<?php 
/**
 * 库存盘点处理类
 * 
 * @package     modules
 * @author      鲍(chenglin.bao@lyceem.com)
 * @copyright   2010-3-22
 */

class CheckStockInfo {

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
	
 	/**
     * 构造函数，获取数据库连接对象
     *
     */
    public function __construct(){
        global $app;
  
        $this->app = $app;
		
		$this->store_db = $app->orm($app->cfg['store_db'])->query();
		
        mysql_query("set names utf8");
    }
    
    /**
     * 获取库存盘点信息
     * 
     * @param string $s_where 查询条件
     * @return array|bool
     */
    public function getCheckInfo($s_where){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$s_where = (string)$s_where;
    	
    	if(trim($s_where) == ''){
    		return $this->_log(array( __CLASS__ .'.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' parameter error' , date("Y-m-d H:i:s")));
    	}
    	
    	$sql = "SELECT * FROM goods_stock_check $s_where";
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql = '.$sql , date("Y-m-d H:i:s")));	
    	}
    	
    	if(!empty($res) && !is_array($res)){
    		return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' result is error '.$res , date("Y-m-d H:i:s")));	
    	}
    	
    	return $res;
    }
	
	/**
	 * 获取库存盘点总数量
	 *
	 * @return array|bool
	 */
	public function getCheckSumNum($s_where){
		if($this->store_db == null){
    		return false;
    	}
    	
		$s_where = (string)$s_where;
    	
    	if(trim($s_where) == ''){
    		return $this->_log(array( __CLASS__ .'.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' parameter error' , date("Y-m-d H:i:s")));
    	}
		
		$sql = "select count(stock_check_id) from goods_stock_check $s_where";
		$res = $this->store_db->getValue($sql);
		
		if($res === false){
    		return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql = '.$sql , date("Y-m-d H:i:s")));	
    	}
		
		return $res;
	}
    
    /**
     * 根据库存盘点id查询盘点信息
     * 
     * @param int $i_checkid 盘点id
     * @return array|bool
     */
    public function getCheckInfoById($i_checkid){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$i_checkid = (int)$i_checkid;
    	
    	if($i_checkid == 0){
    		return $this->_log(array( __CLASS__ .'.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' parameter error' , date("Y-m-d H:i:s")));
    	}
    	
    	$sql = "SELECT * FROM goods_stock_check WHERE stock_check_id = $i_checkid";
        
    	$res = $this->store_db->getRow($sql);
    	
    	if($res === false){
    		return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql = '.$sql , date("Y-m-d H:i:s")));	
    	}
    	
    	if(!empty($res) && !is_array($res)){
    		return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' result is error '.$res , date("Y-m-d H:i:s")));	
    	}
    	
    	return $res;
    }
	
	/**
     * 迁入盘点详细
     * 
     * @param array $ar_goodsdetail   盘点商品详细
     * @return bool
     */
    public function addStockCheckDetail($ar_goodsdetail)
	{
		if($this->store_db == null){
    		return false;
    	}
		
		if(!is_array($ar_goodsdetail) || count($ar_goodsdetail) == 0)
		{
			return false;	
		}
		foreach($ar_goodsdetail as $key=>$val){
			
			$ar_quan = array();
			foreach($val[2] as $k=>$v) {
				$k = !empty($k) ? trim($k) : '';
				$ar_size = explode('-', $k);
				$size = $ar_size[0];
				if(count($ar_size) == 1){
					$type = 'good';
					$quantity = (int)$v;
					$bad_quantity = 0;
					if($quantity < 0)
					break;
				$ar_quan[quantity] = $quantity;
				}else{
					$type = 'bad';
					$quantity = 0;
					$bad_quantity = (int)$v;
					if($bad_quantity < 0)
					break;
				$ar_quan[bad_quantity] = $bad_quantity;
				}
				$a = array(
					'agency_id'   => (int)$val[11],
					'agency_name' => !empty($val[0]) ? trim($val[0]) : '',
					'type'  => $type,
					'goods_id'    => (int)$val[10],
					'goods_sn'    => !empty($val[1]) ? trim($val[1]) : '',
					'goods_name'  => !empty($val[9]) ? trim($val[9]) : '',
					'size'        => $size,
					'quantity'    => $quantity?$quantity:$bad_quantity,
					'create_user_id' => (int)$_SESSION['user_id'],
					'create_date' => time(),
				);
				
				$s_key_a = "(" . implode("," , array_keys($a)) . ")";
				$ar_val_a[] = "('" . implode("','" , $a) . "')";
				
				
			}	
		 }
		
		 $b = array(
					'agency_id'   => (int)$val[11],
					'type'        => '',
					'goods_id'    => (int)$val[10],
					'size'        => $size,
					'quantity'    => $ar_quan[quantity],
					'bad_quantity'    => $ar_quan[bad_quantity],
					'create_user_id' => (int)$_SESSION['user_id'],
					'create_time' => time(),
				);
		
		 $s_key_b = "(" . implode("," , array_keys($b)) . ")";
		 $ar_val_b[] = "('" . implode("','" , $b) . "')";
		 
		 $sql = "INSERT INTO goods_temp_stock_check ".$s_key_a." VALUES " . implode(',' , $ar_val_a);
		 $r   = $this->store_db->exec($sql);

		 if($r === false){
    		return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql = '.$sql , date("Y-m-d H:i:s")));	
    	 }
		 importModule('LogSqs');
		 $logsqs=new LogSqs;
		 
		 $sql = "INSERT INTO goods_stock_check ".$s_key_b." VALUES " . implode(',' , $ar_val_b);
		 $r   = $this->store_db->exec($sql);
		 
		 if($r === false){
    		return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql = '.$sql , date("Y-m-d H:i:s")));	
    	 }
		 importModule('LogSqs');
		 $logsqs=new LogSqs;
		 
		 return true;
	}
	
	/**
	 * 插入库存盘点数据
	 * 
	 * @param array $ar_data 盘点数据
	 * @return bool
	 */
	public function addStockCheck($ar_data) {
		if($this->store_db == null){
    		return false;
    	}
    	
    	if(!$ar_data || !is_array($ar_data)) {
    		return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' check data is error.', date("Y-m-d H:i:s")));	
    	}
    	
    	$s_key = '';
    	$ar_val = array();
    	
		foreach($ar_data as $key=>$val){		
			$s_key = "(" . implode("," , array_keys($val)) . ")";
			$ar_val[] = "('" . implode("','" , $val) . "')";
	
		 }
    	
    	 $sql = "INSERT INTO goods_stock_check ".$s_key." VALUES " . join(',' , $ar_val);
		 $r   = $this->store_db->exec($sql);
		 
		 if($r === false){
    		return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' exec is fail; sql: '.$sql.' mysql_error:'.mysql_error(), date("Y-m-d H:i:s")));	
    	 }
		 importModule('LogSqs');
		 $logsqs=new LogSqs;
		 return true;
	}
	
	/**
	 * 检查是否审核
	 *
	 * @param string $s_checkid
	 * @return 
	 */
	public function canAudit($s_checkid) {
		if($this->store_db == null){
    		return false;
    	}
    	
    	$s_checkid = (string)$s_checkid;
    	
	    if(empty($s_checkid)){
    		return false;
    	}

		$sql = "SELECT COUNT(*) FROM goods_stock_check WHERE stock_check_id IN (" . $s_checkid . ") AND confirm_status = 1";
    	$res = $this->store_db->getValue($sql);
 
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute update is fail:'.$res, date("Y-m-d H:i:s")));
    	}
		
		if($res > 0){
			return false;
		}
    	
    	return true;
	}
	
	/**
     * 盘点审核
     * 
     * @param string $s_checkid 盘点id
     * @param int    $i_userid  操作审核人id
     * @return int|bool		    成功返回影响行数,失败返回false
     */
    public function editAudit($s_checkid , $i_userid){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$s_checkid = (string)$s_checkid;
    	$i_userid  = (int)$i_userid;
    	
	    if(empty($s_checkid) || $i_userid == 0){
    		return false;
    	}
    	
    	$sql = "UPDATE  goods_stock_check  SET confirm_status = 1,confirm_user_id = $i_userid,confirm_time = " . time() . " WHERE stock_check_id IN (" . $s_checkid . ")";
    	$res = $this->store_db->exec($sql);
 
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute update is fail:'.$res, date("Y-m-d H:i:s")));
    	}
		importModule('LogSqs');
		$logsqs=new LogSqs;
    	
    	return $res;
    }
	
	/**
	 * 查询盘点要更新的数据
	 *
	 * @param string $s_checkid 盘点id
	 * @return 
	 */
	public function getCheckData($s_checkid){
		if($this->store_db == null){
    		return false;
    	}
    	
    	$s_checkid = (string)$s_checkid;

	    if(empty($s_checkid)){
    		return false;
    	}
		
		$sql = "SELECT agency_id,goods_id,size,quantity,bad_quantity FROM goods_stock_check WHERE stock_check_id IN (".$s_checkid.") AND confirm_status = 0";
		$res = $this->store_db->getArray($sql);
		
		if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute update is fail:'.$res, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
	}
	
	/**
	 * 盘点更新库存
	 *
	 * @param array $ar_data 要更新的数据
	 * @return 
	 */
	public function editStock($ar_data){
		if($this->store_db == null){
    		return false;
    	}
		
		if(empty($ar_data) && !is_array($ar_data)){
			return false;
		}
		
		$this->success_log(array(date("Y-m-d H:i:s"),__CLASS__ . '.class.php line ' . __LINE__ , ' function '. __FUNCTION__ .' user_id:'.$_SESSION['user_id'].' data:'.serialize($ar_data)));
		
		foreach($ar_data as $data){
			if(empty($data['goods_id']) || empty($data['agency_id']) || empty($data['size'])) {
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'data is not full:'.serialize($data), date("Y-m-d H:i:s")));
			}
		
			if($this->findStock($data,'goods_stock')){
				$sql = "UPDATE goods_stock SET efficacious_quantity = $data[quantity], bad_quantity = $data[bad_quantity], quantity = efficacious_quantity + bad_quantity WHERE agency_id = $data[agency_id] AND".
					" goods_id = $data[goods_id] AND size = '".$data['size']."'";
				$r = $this->store_db->exec($sql); 
				
				if(!$r){
					return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'update is fail:'.$sql, date("Y-m-d H:i:s")));
				}
				importModule('LogSqs');
				$logsqs=new LogSqs;
			
			}else{
				$sql = "insert into goods_stock (goods_id,size,color,agency_id,quantity,efficacious_quantity,bad_quantity) values ".
						"($data[goods_id],'".$data[size]."','".$data[color]."',$data[agency_id],$data[quantity]+$data[bad_quantity],$data[quantity],$data[bad_quantity])";
				$r1 = $this->store_db->exec($sql);
				
				if(!$r1){
					return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'insert is fail:'.$sql, date("Y-m-d H:i:s")));
				}
				importModule('LogSqs');
				$logsqs=new LogSqs;

				$sql = "insert into goods_stock_detail(agency_id,batch_id,goods_id,size,quantity,add_user_id,add_time)values".
						"('$data[agency_id]','$data[batch_id]','$data[goods_id]','$data[size]','$data[quantity]','".$_SESSION['user_id']."',".time().")";

				$r2 = $this->store_db->exec($sql);
		
				if(!$r2){
					return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'insert is fail:'.$sql, date("Y-m-d H:i:s")));
				}
				importModule('LogSqs');
		 	    $logsqs=new LogSqs;

			}
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
		
		if($r === false) {
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'select exec fail:'.$sql, date("Y-m-d H:i:s")));
		}

    	if($r > 0){
    		return true;
    	}
    	
    	return false;
	}
	
  	/**
     * 移除盘点
     * 
     * @param string $s_checkid   盘点单号
     * @return int|bool           成功返回影响行数，失败返回false
     */
    public function removeCheck($s_checkid){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$s_checkid = (string)trim($s_checkid);

    	$sql = "UPDATE goods_stock_check SET confirm_status = 2 , confirm_user_id = " . $_SESSION['user_id'] . " , confirm_time = " . time() . " WHERE stock_check_id IN (" . $s_checkid . ")";

    	$res = $this->store_db->exec($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute delete is fail:'.$res, date("Y-m-d H:i:s")));
    	}
		importModule('LogSqs');
		$logsqs=new LogSqs;
    	
    	return $res;
    }
	
	/**
	 * 盘点库存清零
	 *
	 * @param void
	 * @return bool
	 */
	public function clearStock() {
		if($this->store_db == null){
    		return false;
    	}
		
		$sql = "delete from goods_stock where agency_id <> 1";
		$res = $this->store_db->exec($sql);
		
		if($res === false) {
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'delete exec fail:'.$sql, date("Y-m-d H:i:s")));
		}
		importModule('LogSqs');
		$logsqs=new LogSqs;
		$sql = "delete from goods_stock where efficacious_quantity < 1 and bad_quantity < 1";
		$r = $this->store_db->exec($sql);
		
		if($r === false) {
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'delete exec fail:'.$sql, date("Y-m-d H:i:s")));
		}
		importModule('LogSqs');
		$logsqs=new LogSqs;
		
		return true;
	}
	
	
	/**
	 * 转移仓库
	 *
	 */
	public function TransAgencyStock($agency)
	{
		if($this->store_db == null)  return false;
		$sql = "SELECT goods_id,size,color,SUM(quantity) as quantity, SUM(bad_quantity) as bad_quantity, SUM(efficacious_quantity) as efficacious_quantity FROM `goods_stock` WHERE agency_id <> $agency GROUP BY goods_id,size";
	
		$res = $this->store_db->getArray($sql);

		if($res === false) {
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'delete exec fail:'.$sql, date("Y-m-d H:i:s")));
		}
		
		return $res;
		
	}
	
	/**
	 * 转移库存
	 */
	public function TansferStock($val,$ustock,$agency_id)
	{
		if($this->store_db == null)  return false;
		if($ustock['out'])
		{
			$c = $ustock['out']['transfer_out'];
		}

		$sql = "INSERT INTO goods_stock(goods_id,size,color,agency_id,quantity,bad_quantity,efficacious_quantity)
		VALUES($val[goods_id],'$val[size]','$val[color]',$agency_id,$c,0,$c )";

		$res = $this->store_db->exec($sql);
		
		if($res === false) {
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'delete exec fail:'.$sql, date("Y-m-d H:i:s")));
		}
		
		return $res;
	}
	
	/**
	 * 获取对应goods_id的库存
	 */
	public function getStock_ids($goods_id,$size,$rate,$agency_id)
	{
		if($this->store_db == null)  return false;
		$sql = "SELECT goods_id,size,agency_id,quantity,efficacious_quantity FROM `goods_stock` WHERE goods_id = ".$goods_id." AND size = '".$size."' AND agency_id <> ".$agency_id." ";
		$res = $this->store_db->getArray($sql);
		
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'delete exec fail:'.$sql, date("Y-m-d H:i:s")));
		}

		foreach ($res as $k=>$val)
		{
			$res[$k]['tranfers_out']  =  round($val['efficacious_quantity'] * $rate*0.1);
		}
		
		return $res;
	}
	
	/**
	 * 更新分库的库存
	 */
	public function updateAgency_stock($stock,$c)
	{
//		if($c !=0)
//		{
//			$stock['tranfers_out'] = 0;
//		}
		if($this->store_db == null)  return false;
		if($stock)
		{
			$goods_q = $stock['quantity'] - $stock['tranfers_out'] - $c;
			$goods_e = $stock['efficacious_quantity'] - $stock['tranfers_out'] - $c;
		}
		else {
			return false;
		}

		$sql = "UPDATE goods_stock SET quantity = '".$goods_q."',efficacious_quantity = '".$goods_e."' 
		WHERE goods_id = $stock[goods_id] AND agency_id = $stock[agency_id] AND size = '".$stock[size]."'";
		
		$res = $this->store_db->exec($sql);
		if($res == false)
		{
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'delete exec fail:'.$sql, date("Y-m-d H:i:s")));
		}
		
		return $res;
	}
	
	/**
	 * 获取仓库信息
	 */
	public function getAgencyInfo()
	{
		if($this->store_db == null)  return false;
		$sql = "SELECT agency_id,agency_name FROM agency WHERE agency_desc = 'TR'";
		$res = $this->store_db->getArray($sql);
		if($res === false)
		{
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'delete exec fail:'.$sql, date("Y-m-d H:i:s")));
		}
		return $res;
	}
	
	/**
	 * 获取虚拟代销仓库
	 */
	public function getNewAgency($stock,$agency)
	{
		if($this->store_db == null)  return false;
		$sql = "SELECT * FROM goods_stock WHERE goods_id = '".$stock[0][goods_id]."' AND size = '".$stock[0][size]."' AND agency_id = $agency";
		$res = $this->store_db->getArray($sql);
		if($res === false)
		{
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'delete exec fail:'.$sql, date("Y-m-d H:i:s")));
		} 
	
		return $res;
		
	}
	
	/**
	 * 更新虚拟销售库存
	 */
	public function UpdateNewAgency($stock,$u_stock,$agency)
	{
	    if($u_stock['out'])
	    {
	    	$quantity = $u_stock['out']['transfer_out'] + $stock[0]['quantity'];
	    	$efficacious_quantity = $u_stock['out']['transfer_out'] + $stock[0]['efficacious_quantity'];
	    }
		if($this->store_db == null)  return false;
		$sql = "UPDATE goods_stock SET quantity = $quantity,efficacious_quantity = $efficacious_quantity WHERE goods_id = '".$stock[0][goods_id]."' AND agency_id = '$agency' AND size = '".$stock[0]['size']."' ";
		$res = $this->store_db->exec($sql);
		
		if($res === false)
		{
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'delete exec fail:'.$sql, date("Y-m-d H:i:s")));
		}
		
		return $res;
	}
	
	/**
	 * 获取指定仓库名称
	 */
	public function getAgency_name($agency_id)
	{
		if($this->store_db == null)  return false;
		$sql = "SELECT agency_name FROM agency WHERE agency_id = $agency_id";
		$res = $this->store_db->getValue($sql);

		if($res === false)
		{
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'delete exec fail:'.$sql, date("Y-m-d H:i:s")));
		}
		
		return $res;
	}
	
	 /**
	  * 写成功日志
	  */
	public function writetruelog($data)
	{
		if($this->store_db == null)  return false;
		return $this->success_log($data);
	}
	
	/**
	 * 写失败日志
	 */
	public function writefalselog($data)
	{
		if($this->store_db == null)  return false;
		return $this->_log($data);
	}
	
	/**
	 * 记录库存操作日志
	 *
	 * @param 	Array 	$data
	 * @return 	bool	false
	 */
	private function success_log($data){
	    $log = $this->app->log();
	    $log->reset()->setPath("success/CheckStockInfo")->setData($data)->write();
	}
	
	
	/**
	 * 数据更新失败记录日志，并标识操作失败
	 *
	 * @param 	Array 	$data
	 * @return 	bool	false
	 */
	private function _log($data){
	    $log = $this->app->log();
	    $log->reset()->setPath("modules/CheckStockInfo")->setData($data)->write();
	    
	    return false;
	}
}
?>