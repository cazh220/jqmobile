<?php 
/**
 * 分销商处理类
 * 
 * @package     modules
 * @author      鲍<chenglin.bao@lyceem.com>
 * @copyright   2010-9-25
 */

class DistributorInfo {
	/**
	 * 应用程序对象
	 * @var Application
	 */
	private $app = null;
	
	/**
	 * 数据库操作对象
	 * @var OrmQuery
	 */
	private $stock = null;
	
	private $union = null;
	
	
 	/**
     * 构造函数，获取数据库连接对象
     *
     */
    public function __construct(){
        global $app;
        
        $this->app = $app;
        
        $this->stock = $app->orm($app->cfg['store_db'])->query();
		
        mysql_query("set names utf8");
		
		$this->union = $app->orm($app->cfg['union'])->query();
		
        mysql_query("set names utf8");
    }
    /**
     * 获取一个分销商
     */
    
    public function getDistributor($s_id){
        if($this->stock == null){
            return false;
        }
        $sql = "select * from distributors where distributor_id =".$s_id;
        $res = $this->stock->getRow($sql);
        if(!$s_id)return false;
        if($res === false){
            return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
        }
        if(!empty($res) && !is_array($res)){
            return false;
        }
        
        return $res;
        
    }
    /**
     * 更新一个分销商
     */
    public function upDistributorInfo($ar_data){
        if($this->stock == null){
            return false;
        }
        if(!empty($ar_data) && is_array($ar_data)){
            $res = $this->stock->addTable('distributors')->addWhere('distributor_id',$ar_data['distributor_id'])->update($ar_data);
            if ($res === false) {
            return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. data = '.$ar_data, date("Y-m-d H:i:s")));
            }        
            if($res > 0) 
                 return true;
            return false;
        }
        return false;
    }
	/**
     * 获取所有的分销商
     * 
     * @return array|bool  成功返回供应商数组,失败返回false
     */
    public function getDistributors($ar_id=array()){
    	if($this->stock == null){
    		return false;
    	}
    
    	if(!empty($ar_id) && is_array($ar_id)){
    		$sql = "SELECT distributor_id,distributor_name FROM distributors WHERE distributor_id in (".join(',',$ar_id).")";
    	}else{
    		$sql = "SELECT distributor_id,distributor_name,add_user_id,contact_name,contact_email,agency_id FROM distributors WHERE status = 1";
    	}

    	$res = $this->stock->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	if(!empty($res) && !is_array($res)){
    		return false;
    	}
        
    	return $res;
    }

    /**
     * 添加分销商
     * 
     * @param array $ar_data 分销商信息
     * @return bool 
     */
   public function addDistributorInfo($ar_data) {
   		if($this->stock == null){
    		return false;
    	}
    	
    	if(!is_array($ar_data) && empty($ar_data)) {
    		return false;
    	}
    	//echo '<pre>';print_r($ar_data);die;
    	$res = $this->stock->addTable('distributors')->insert($ar_data);
    	
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error.'.$res, date("Y-m-d H:i:s")));
    	}
    	
    	if($res > 0) {
    		return true;
    	}
    	
    	return false;
   }
   
	/**
     * 分销商管理查询
     * 
     * @param array  $ar_where 查询条件
     * @return array|bool  成功返回供应商数组,失败返回false
     */
    public function getDistributorList($ar_where = array()){
    	if($this->stock == null){
    		return false;
    	}
    	
    	$s_where = '';
    	
    	if(!empty($ar_where) && is_array($ar_where)){
    		$s_where = join(' AND ',$ar_where);
    	}
    		
    	$sql = "SELECT * FROM distributors WHERE $s_where";
    	$res = $this->stock->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	if(!empty($res) && !is_array($res)){
    		return false;
    	}
    	return $res;
    }
	
	/**
	 * 查询分销渠道
	 */
	public function getUnions() {
		if($this->union == null){
    		return false;
    	}

		$sql = "SELECT id, pid, partner_name name FROM partner";
		$res = $this->union->getArray($sql);

    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}
    	
  		$ar_union = array();
		
		if($res && is_array($res)) {
		
			foreach($res as $key=>$val) {
				$ar_union[$val['id']] = $val;
				
			}
			foreach($ar_union as $key=>$val) {
				if($val['pid'] != 0) {
					if(!empty($ar_union[$val['pid']])) {
						$ar_union[$val['pid']]['children'][] = $val;
					}
					unset($ar_union[$key]);
				} 
			}
		}
		
		return $ar_union;
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