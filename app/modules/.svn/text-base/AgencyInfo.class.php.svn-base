<?php 
/**
 * 仓库处理类
 * 
 * @package     modules
 * @author      鲍(chenglin.bao@lyceem.com)
 * @copyright   2010-3-24 
 */

class AgencyInfo {
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
     * 获取仓库信息
     * 
     * @return array|bool  成功返回数组,失败返回false
     */
    public function getAgency($status=''){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$sql = "SELECT agency_id,agency_name,contact_name,contact_tel,agency_desc,add_user,add_time,status FROM agency";
        if($status)$sql = "SELECT agency_id,agency_name,contact_name,contact_tel,agency_desc,add_user,add_time,status FROM agency where status=".$status;
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute select faile.'.$sql, date("Y-m-d H:i:s")));
    	}

    	if(!empty($res) && !is_array($res)){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'result is error.'.$res, date("Y-m-d H:i:s")));
    	}
    	
    	$ar_agency = array();
    	
    	if(count($res) > 0){
    		foreach($res as $r){
    			$ar_agency[$r['agency_id']] = $r; 
    		}
    	}
    	
    	unset($res);
    	return $ar_agency;
    }
	
	/**
	 * 查询仓库
	 * 
	 * @param array $ar_agencyid
	 * @return
	 */
	public function getAgencyName($ar_agencyid = array()){
		if($this->store_db == null){
    		return false;
    	}
		
		if(!is_array($ar_agencyid)){
			return  false;
		}

		if(count($ar_agencyid) > 0){
			$sql = "select agency_id,agency_name from agency where agency_id in (".join(',',$ar_agencyid).")";
		}else{
			$sql = "select agency_id,agency_name from agency ";
		}
		$res = $this->store_db->getArray($sql);
		
		if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute select faile.'.$sql, date("Y-m-d H:i:s")));
    	}
		
		if(!empty($res) && is_array($res)){
			$ar_agency = array();
			foreach($res as $r){
				$ar_agency[$r['agency_id']] = $r['agency_name'];
			}
		}
	
		return $ar_agency;
	}
	
	/**
	 * 查询仓库列表
	 */
	public function getAgencyInfo($s_where='') {
		if($this->store_db == null) return false; 
		$s_where = (string)$s_where; 
		if($s_where == '') return false;
		
		$sql = 'SELECT agency_id,agency_name,contact_name,contact_tel,agency_desc,add_user,add_time,status FROM agency  '.$s_where;
		 
		$res = $this->store_db->getArray($sql);
		
		if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute select faile.'.$sql, date("Y-m-d H:i:s")));
    	}
		
    	return $res;
	
	}
	
 	/**
     * 添加仓库
     * 
     * @param array $ar_data 仓库信息
     * @return bool 
     */
	public function addAgencyInfo($ar_data) {
   		if($this->store_db == null){
    		return false;
    	}
    	
    	if(!is_array($ar_data) && empty($ar_data)) {
    		return false;
    	}
    	//print_r($ar_data);die;
    	$sql = "INSERT INTO agency (".JOIN(',',array_keys($ar_data)).") VALUES ('".JOIN("','",array_values($ar_data))."')";
    	$res = $this->store_db->exec($sql);
    	//$res = $this->store_db->clear()->addTable('agency')->insert($ar_data);
    	
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error.'.$res, date("Y-m-d H:i:s")));
    	}
    	
    	if($res > 0) {
    		importModule('LogSqs');
			$logsqs=new LogSqs;
    		return true;
    	}
    	
    	return false;
	}
	
	/**
	 * 数据更新失败记录日志，并标识操作失败
	 *
	 * @param 	Array 	$data
	 * @return 	bool	false
	 */
	private function _log($data){
	    $log = $this->app->log();
	    $log->reset()->setPath("modules/AgencyInfo")->setData($data)->write();
	    
	    return false;
	}
}
?>