<?php 
/**
 * 商品批次处理类
 * 
 * @package     modules
 * @author      鲍(chenglin.bao@lyceem.com)
 * @copyright   2010-3-22
 */

class BatchInfo {
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
     * 获取所有商品的批次
     * 
     * @param  array $ar_batchid 批次id
     * @return array|bool  成功返回批次数组,失败返回false
     */
    public function getBatch($ar_batchid=array()){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	
    	if(is_array($ar_batchid) && count($ar_batchid) > 0){
    		$sql = "SELECT * FROM batch WHERE batch_id IN (" . implode(',' , $ar_batchid) . ")";
    	}else{
    		$sql = "SELECT batch_id,batch_code FROM batch ";
    	}	
    	
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	if(!empty($res) && !is_array($res)){
    		return false;
    	}
    	
    	return $res;
    }
    
    /**
     * 查询商品的批次信息
     * 
     * @param string $s_do 	      执行的操作
     * @param string $s_where 查询条件
     * @return array|bool     成功返回商品批次数组,失败返回false
     */
    public function getBatchInfo($s_where,$s_do=''){
    	if($this->store_db == null){
    		return false;
    	} 
    	$s_where = (string)$s_where; 
    	
    	if($s_where == ''){
    		return false;
    	} 
    	if($s_do == 'batchid'){
    		$sql = "SELECT * FROM batch b WHERE b.batch_code = '$s_where'";  
    		$res = $this->store_db->getRow($sql);
    	}elseif($s_do == 'allbatchid'){
    		$sql = "SELECT * FROM batch WHERE batch_code in ('".$s_where."')"; 
    		$res = $this->store_db->getArray($sql);
    	}elseif($s_do == 'stockin'){ 
    		$sql = "SELECT gso.* FROM batch gso $s_where AND gso.confirm_status = 1 ORDER BY gso.batch_id DESC";
    		$res = $this->store_db->getArray($sql);
    	}else{ 
    		$sql = "SELECT b.*, s.supplier_name, s.supplier_name , a.user_name AS user_name FROM batch b, suppliers s, admin_user a $s_where ";//echo $sql;die;
    		$res = $this->store_db->getArray($sql);
    	}

    	if($res === false || !is_array($res)){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute error : '.$sql, date("Y-m-d H:i:s")));
    	}

		$ar_batchid = array();
		if(count($res) > 0)
		{
			foreach($res as $v)
			{
				if(is_array($v)){
					$ar_batchid[$v['batch_id']] = $v;
				}else{
					$ar_batchid = $res;
				}
			}
		}
	
		unset($s_where, $s_do, $sql, $res, $v);
    	return $ar_batchid;
    }
    
	/**
	 * 查询批次总数
	 * 
     * @param string $s_where 查询条件
     * @return int|bool     成功返回商品批次总数,失败返回false
	 */
	public function getBatchTotalNum($s_where){
		if($this->store_db == null){
    		return false;
    	}
    	
    	$s_where = (string)$s_where;
    	
    	if($s_where == ''){
    		return false;
    	}
    	
    	$sql = "SELECT COUNT(b.batch_id) FROM batch b $s_where";
    	$res = $this->store_db->getValue($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute error : '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
	}
    
    /**
     * 添加批次
     * 
     * @param array $ar_purchase  要添加的批次信息
     * @return int|bool 		  成功返回影响行数,失败返回false
     */
    public function addBatch($ar_purchase){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	if(!is_array($ar_purchase) || count($ar_purchase) < 1){
    		return false;
    	}
    	
    	$res = $this->store_db->addTable('batch')->insert($ar_purchase);
    	$res = $this->store_db->getLastId();
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute insert is fail:'.$res, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
    
    /**
     * 添加批次详细
     * 
     * @param array $ar_purchase 要添加的批次信息
     * @return int|bool 		  成功返回影响行数,失败返回false
     */
    public function addBatchDetail($ar_purchase){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	if(!is_array($ar_purchase) || count($ar_purchase) < 1){
    		return false;
    	}
    	
    	$sql = "INSERT INTO  batch_details (batch_id,goods_id,size,cost_price,quantity)VALUES ".join(',',$ar_purchase);
    	$res = $this->store_db->exec($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute insert is fail:'.$res, date("Y-m-d H:i:s")));
    	}
		importModule('LogSqs');
		$logsqs=new LogSqs;
    	
    	return $res;
    }
    
    /**
     * 查询批次详细
     * 
     * @param array  $ar_batchid 批次id
     * @return array|bool
     */
    public function getBatchDetail($ar_batchid){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	if(!is_array($ar_batchid)){
    		$ar_batchid = (array)$ar_batchid;
    	}
    	
    	if(count($ar_batchid) < 1){
    		return false;
    	}
    	
    	$sql = "SELECT * FROM batch_details WHERE batch_id IN (".join(',',$ar_batchid).")";
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute select is fail:'.$res, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }

    
    /**
     * 查询采购详细
     * 
     * @param int $i_batchid  批次id
     * @return array|bool     成功返回数组,失败返回false
     */
    public function getPurchaseDetail($i_batchid,$do=''){
    	if($this->store_db == null){
    		return false;
    	}

    	$i_batchid = (int)$i_batchid;
    	
    	if($i_batchid == 0){
    		return false;
    	}
    	
    	if($do == 'num'){
    		$sql = "SELECT sum(quantity) FROM batch_details WHERE batch_id = $i_batchid ";
    		$res = $this->store_db->getValue($sql);
    	}else{
    		$sql = "SELECT * FROM batch_details WHERE batch_id = $i_batchid ";
    		$res = $this->store_db->getArray($sql);
    	}

    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute sql is fail:'.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
    
	
	/**
     * 是否可以审核
     * 
     * @param string $s_batchid 批次id
     * @return bool		   
     */
    public function canAudit($s_batchid)
	{
		if($this->store_db == null)
		{
    		return false;
    	}
		
		$s_batchid = (string)$s_batchid;
		if(empty($s_batchid))
		{
			return false;	
		}
		
		$sql = "SELECT confirm_status FROM batch WHERE batch_id IN(". $s_batchid .")";
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
     * 采购审核
     * 
     * @param string $s_batchid 批次id
     * @param int    $i_userid  操作审核人id
     * @return int|bool		    成功返回影响行数,失败返回false
     */
    public function editAudit($s_batchid , $i_userid){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$s_batchid = (string)$s_batchid;
    	$i_userid  = (int)$i_userid;
    	
	    if(empty($s_batchid) || $i_userid == 0){
    		return false;
    	}
    	
    	$sql = "UPDATE batch SET confirm_status = 1,confirm_user_id = $i_userid,confirm_time = " . time() . " WHERE batch_id IN (" . $s_batchid . ")";
    	$res = $this->store_db->exec($sql);
 
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute update is fail:'.$res, date("Y-m-d H:i:s")));
    	}
		importModule('LogSqs');
		$logsqs=new LogSqs;
    	
    	return $res;
    }
    
 	/**
     * 移除采购
     * 
     * @param  string $s_batchid    采购批次id
     * @return bool            
     */
    public function removePurcase($s_batchid){
    	if($this->store_db == null){
    		return false;
    	}
		
		if(empty($s_batchid)){
    		return false;
    	}
		
		$sql = "UPDATE batch SET confirm_status = 2 , confirm_user_id = " . $_SESSION['user_id'] . " , confirm_time = " . time() . " WHERE batch_id IN (" . $s_batchid . ")";
    	$r   = $this->store_db->exec($sql);
    	
    	if($r === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute delete is fail:'.$res, date("Y-m-d H:i:s")));
    	}
		importModule('LogSqs');
		$logsqs=new LogSqs;
    	
    	return $r;
    }
	
	/**
     * 搜索批次
     * 
     * @param  array  $ar_condition  搜索条件
     * @return bool            
     */
	public function searchBatch($ar_condition)
	{
		if( $this->store_db == null ){
    		return false;
    	}
		
		if( !is_array($ar_condition) || count($ar_condition) == 0 )
		{
			return false;	
		}
		
		$s_condition = implode(' AND ' , $ar_condition);
		$sql = "SELECT batch_id, batch_code FROM batch WHERE " . $s_condition;
		$r   = $this->store_db->getArray($sql);

		if($r === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute delete is fail:'.$res, date("Y-m-d H:i:s")));
    	}
    	return $r;
	}
	
	/**
     * 根据商品的批次获商品批次id
	 *
	 * @param array  $ar_batchcode
	 * @return 
	 */
	public function getBatchId($ar_batchcode){
		if( $this->store_db == null ){
    		return false;
    	}
		
		if( !is_array($ar_batchcode) || count($ar_batchcode) == 0 )
		{
			return false;	
		}
		
		$sql = "select batch_id,batch_code from batch where batch_code in ('".join("','",$ar_batchcode)."')";
		$res = $this->store_db->getArray($sql);
		
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
		}
		
		$ar_batch = array();
		if(!empty($res)){
			foreach($res as $v){
				$ar_batch[$v['batch_code']] = $v['batch_id'];
			}
		}
		
		return $ar_batch;
	}
	
	/**
	 * 获取批次和供应商
	 * 
	 * @param array $ar_batchid 批次id
	 * @return array|bool
	 */
	public function getBatchAndSupplier($ar_batchid) {
		if( $this->store_db == null ){
    		return false;
    	}
		
		if(!is_array($ar_batchid) || count($ar_batchid) == 0 )
		{
			return false;	
		}
		
		$sql = "select batch_id,supplier_name,batch_code from batch as b left join suppliers as s on b.supplier_id=s.supplier_id where b.batch_id in (".join(',',$ar_batchid).")";
		$res = $this->store_db->getArray($sql);
		
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
		}
		
		$ar_res = array();
		if(!empty($res) && is_array($res)){
			foreach($res as $r){
				$ar_res[$r['batch_id']] =$r;	
			}
		}
		
		return $ar_res;
	}
	
	/**
	 * 根据供应商查询商品id
	 *
	 * @param int $i_suppler
	 * @return
	 */
	public function getGoodsBySupplier($i_suppler){
		if( $this->store_db == null ){
    		return false;
    	}
		
		$i_suppler = (int)$i_suppler;
		
		$sql = "SELECT distinct(goods_id) FROM batch_details WHERE batch_id IN (SELECT distinct(batch_id) FROM batch WHERE supplier_id = $i_suppler)";
		$res = $this->store_db->getColumn($sql);
		
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
		}
		
		return $res;
	}
	
	/**
	 * 判断批次是否存在
	 * 
	 * @param string $s_batch
	 * @return bool|int
	 */
	public function isExitBatch($s_batch) {
		if( $this->store_db == null ){
    		return false;
    	}
		
		$s_batch = (string)trim($s_batch);
		
		if(!$s_batch) return false;
		
		$sql = "select batch_id,confirm_status from batch where batch_code = '$s_batch'";
		$res = $this->store_db->getRow($sql);
		
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
		}

		return $res;
	}
	
	/**
	 * 查询批次价格
	 * 
	 * @param array $ar_goodsid 商品id
	 * @return array|bool
	 */
	public function getBatchPrice($ar_goodsid) {
		if( $this->store_db == null ){
    		return false;
    	}
    	
    	if(empty($ar_goodsid) || !is_array($ar_goodsid)) {
    		return false;
    	}
    	
    	//$sql = "SELECT 	goods_id,size,batch_id,cost_price FROM batch_details WHERE goods_id in (".JOIN(',',$ar_goodsid).")";   // 把 in 转 = ， 谢佐福   2012-6-14 15:06
		
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
		//die("bbb");
			return false;
		}  

	$sql = "SELECT 	goods_id,size,batch_id,cost_price FROM batch_details WHERE ". $whereOr ; 

  $res = $this->store_db->getArray($sql);
    	
    	if($res === false || !is_array($res)) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}
    
    	return $res;
	}
	/**
	 * 查询价格
	 * 
	 * @param array $ar_goodsid 商品id
	 * @return array|bool
	 */
	public function getPrice($s_goodssn,$s_batchid) {
		if( $this->store_db == null ){
    		return false;
    	}
    	$sql = "SELECT 	`batch_id`,`goods_sn`,`size`,`cost_price`,`quantity` FROM stock.batch_details as sb inner join lyceem.ecs_goods as le on le.goods_id=sb.goods_id WHERE 1";
    	if(!empty($s_goodssn))$sql.=" and goods_sn='".$s_goodssn."'";
    	if(!empty($s_batchid))$sql.=" and batch_id='".$s_batchid."'";
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false || !is_array($res)) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}
    
    	return $res;
	}
	
	/**
	 * 根据where条件查询条件查询商品批次码
	 * 
	 * @param string  $s_where 
	 * @return array|bool
	 */
	public function getBatchCode($s_where) {
		if( $this->store_db == null ){
    		return false;
    	}
    	
    	if(empty($s_where)) {
    		return false;
    	}
    	
    	$sql = "SELECT batch_code FROM batch WHERE batch_id IN (SELECT distinct(batch_id) FROM batch_details $s_where)";
    	$res = $this->store_db->getColumn($sql);
    	
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
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
	    $log->reset()->setPath("modules/BatchInfo")->setData($data)->write();
	    
	    return false;
	}
}
?>