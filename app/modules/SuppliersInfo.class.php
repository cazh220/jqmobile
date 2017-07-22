<?php 
/**
 * 供应商处理类
 * 
 * @package     modules
 * @author      鲍(chenglin.bao@lyceem.com)
 * @copyright   2010-3-22
 */

class SuppliersInfo {
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
     * 获取所有的供应商
     * 
     * @return array|bool  成功返回供应商数组,失败返回false
     */
    public function getSuppliers($ar_id=array()){
    	if($this->store_db == null){
    		return false;
    	}
    
    	if(!empty($ar_id) && is_array($ar_id)){
    		$sql = "SELECT supplier_id,supplier_name FROM suppliers WHERE supplier_id in (".join(',',$ar_id).")";
    	}else{
    		$sql = "SELECT supplier_id , supplier_name , add_user_id , contact_name , contact_email FROM suppliers WHERE status = 1";
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
     * 获取所有的供应商
     * 
     * @return array|bool  成功返回供应商数组,失败返回false
     */
    public function getSuppliersById(){
    	if($this->store_db == null){
    		return false;
    	}
    
    	if(!empty($ar_id) && is_array($ar_id)){
    		$sql = "SELECT supplier_id,supplier_name FROM suppliers WHERE supplier_id in (".join(',',$ar_id).")";
    	}else{
    		$sql = "SELECT supplier_id , supplier_name , add_user_id , contact_name , contact_email FROM suppliers WHERE status = 1";
    	}

    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	if(!empty($res) && !is_array($res)){
    		return false;
    	}
		
		$ar_temp = array();
		foreach($res as $r){
			$ar_temp[$r['supplier_id']] = $r;
		}

    	return $ar_temp;
    }

    /**
     * 添加供应商
     * 
     * @param array $ar_data 供应商信息
     * @return bool 
     */
   public function addSupplierInfo($ar_data) {
   		if($this->store_db == null){
    		return false;
    	}
    	
    	if(!is_array($ar_data) && empty($ar_data)) {
    		return false;
    	}
    	
    	$res = $this->store_db->addTable('suppliers')->insert($ar_data);
    	
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error.'.$res, date("Y-m-d H:i:s")));
    	}
    	
    	if($res > 0) {
    		return true;
    	}
    	
    	return false;
   }
   
	/**
     * 供应商管理查询
     * 
     * @param array  $ar_where 查询条件
     * @return array|bool  成功返回供应商数组,失败返回false
     */
    public function getSupplierList($ar_where = array()){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$s_where = '';
    	
    	if(!empty($ar_where) && is_array($ar_where)){
    		$s_where = join(' AND ',$ar_where);
    	}
    		
    	$sql = "SELECT * FROM suppliers WHERE $s_where";
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
	 * 数据更新失败记录日志，并标识操作失败
	 *
	 * @param 	Array 	$data
	 * @return 	bool	false
	 */
	private function _log($data){
	    $log = $this->app->log();
	    $log->reset()->setPath("modules/SuppliersInfo")->setData($data)->write();
	    
	    return false;
	}
}
?>