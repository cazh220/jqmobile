<?php 
/**
 * 管理员处理类
 * 
 * @package     modules
 * @author      鲍(chenglin.bao@lyceem.com)
 * @copyright   2010-3-22
 */

class AdminInfo {
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
     * 获取所有的管理员
     *
     * @access  public
     * @param   array      $ar_id 管理员id
     * 
     * @return  array|bool        成功返回管理员数组,失败返回false
     */
    public function getAdminInfo($ar_id = array()){
    	if($this->store_db == null){
    		return false;
    	}
 
    	if(is_array($ar_id) && count($ar_id) > 0){
    		$sql = "SELECT user_id,user_name,email FROM admin_user WHERE user_id IN (".JOIN(',',$ar_id).")";
    	}else{
    		$sql = "SELECT * FROM admin_user";
    	}
    	
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	if(!empty($res) && !is_array($res)){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'result is error. '.$res, date("Y-m-d H:i:s")));
    	}
    	
		$ar_admin = array();
		
		if(count($res) > 0){
			foreach($res as $v){
				$ar_admin[$v['user_id']] = $v;	
			}
		}
		
		unset($sql , $res , $v);
    	return $ar_admin;
    }
    
    /**
     * 根据管理员id查询管理员信息
     * 
     * @param int $i_adminid  管理员id
     * 
     * @return bool
     */
    public function getAdminUser($i_adminid) {
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$i_adminid = (int)$i_adminid;
    	
    	if($i_adminid == 0 || $i_adminid == '') {
    		return false;
    	}
    	
    	$sql = "SELECT id,user_name,email,password FROM admin_user WHERE id = $i_adminid";
    	$res = $this->store_db->getRow($sql);
    	
    	if ($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'result is error. '.$res, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
    
    /**
     * 添加管理员信息
     * 
     * @param array $ar_admin 管理员信息
     * 
     * @return bool 
     */
    public function addAdminInfo($ar_admin){
   	 	if($this->store_db == null){
    		return false;
    	}
    	
    	if(!is_array($ar_admin) || empty($ar_admin)) {
    		return  false;
    	}
    	
    	$sql = "INSERT INTO admin_user (".join(',',array_keys($ar_admin)).") VALUES('".join("','",array_values($ar_admin))."')";
    	$res = $this->store_db->exec($sql);
    	
    	if ($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	if($res > 0) {
    		importModule('LogSqs');
			$logsqs=new LogSqs;
    		return true;
    	}
    	
    	return false;
    }
    
    /**
     * 更管理员信息
     * 
     * @param int   $i_adminid    管理员id
     * @param array $ar_admininfo 待更新的管理员数据
     * 
     * @return bool
     */
    public function editAdminInfo($i_adminid,$ar_admininfo) {
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$i_adminid = (int)$i_adminid;
    	
    	if($i_adminid == 0 || $i_adminid == '') {
    		return false;
    	}
    	
    	if(empty($ar_admininfo) || !is_array($ar_admininfo)) {
    		return false;
    	}
    	
    	$res = $this->store_db->addTable('admin_user')->addWhere('id',$i_adminid)->update($ar_admininfo);

    	if ($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	if($res > 0) 
    		 return true;
		
    	return false;
    }
   
    
    /**
     * 删除管理员
     * 
     * @param int $i_userid 用户id
     * 
     * @return bool
     */
    public function delAdminUser($i_userid) {
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$i_userid = (int)$i_userid;
    	
    	if ($i_userid == 0 || $i_userid == '') {
    		return false;
    	}
    	
    	$sql = "DELETE FROM  admin_user WHERE id = $i_userid";
    	$res = $this->store_db->exec($sql);
    	
    	if ($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	if($res > 0) {
    		importModule('LogSqs');
		 	$logsqs=new LogSqs;
			return true;
    	}
    		 
		
    	return false;
    }
    
    /**
     * 获取最大id
     */
    public function getMaxID(){
    	if($this->store_db == null){
    		return false;
    	}
    	
    	$sql = "SELECT MAX(id) FROM admin_user";
    	$res = $this->store_db->getValue($sql);
    	
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
    }
    
 	/**
     * 判断表中某字段是否重复，若重复则中止程序，并给出错误信息
     *
     * @access  public
     * @param   string  $col    字段名
     * @param   string  $name   字段值
     *
     * @return void
     */
    public function is_only($col, $name) {
    	if($this->store_db == null){
    		return false;
    	}
    	
        $sql = "SELECT COUNT(*) FROM admin_user WHERE $col = '$name'";
        $res = $this->store_db->getValue($sql);
        
   		if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}

        return ($res == 0);
    }
    
    /**
     * 获取权限列表数据
     * 
     * @param int $i_adminid 管理员id
     * @return array|bool
     */
	public function getPrivList($ar_where = array()) {
		if($this->store_db == null){
    		return false;
    	}
    
    	if($ar_where['id'] > 0) {
    		$sql = "SELECT action_list FROM admin_user WHERE id = $ar_where[id]";
    	} else {
    		$sql = "SELECT action_id, parent_id, action_code,action_name,action_url FROM admin_action WHERE status = 1 ";
			if(!empty($ar_where['is_nav'])) {
				$sql .= "AND is_nav = $ar_where[is_nav]  ";
			}
			$sql .= "ORDER BY action_id ASC";
    	}
    
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}
    	return $res;
	}
	
	/**
	 * 根据用户权限查询权限列表
	 *
	 * @param string $s_where 管理员查询权限
	 * @return array|bool
	 */
	 public function getAdminPriv($s_where = '') {
	 	if($this->store_db == null){
    		return false;
    	}

    	if(!empty($s_where) && !is_string($s_where)) {
    		$s_where = (string)$s_where;
    	}

    	$sql = "SELECT action_id, parent_id, action_code,action_name,action_url FROM admin_action $s_where AND is_nav = 1 AND status = 1 ORDER BY action_id ASC";	
    	$res = $this->store_db->getArray($sql);
    	
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	return $res;
	 }
    
	/**
	 * 更新管理员权限列表
	 * 
	 * @param int     $i_adminid  管理员id
	 * @param string  $act_list   权限列表
	 * 
	 * @return bool
	 */
	public function updateAdminAllot($i_adminid, $act_list) {
		if($this->store_db == null){
    		return false;
    	}
    	
    	$sql = "UPDATE admin_user SET action_list = '".$act_list."' WHERE id = $i_adminid";//echo $sql;die;
    	$res = $this->store_db->exec($sql);
    	
    	if ($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}
    	
    	if($res > 0){
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
	    $log->reset()->setPath("modules/AdminInfo")->setData($data)->write();
	    
	    return false;
	}
}
?>