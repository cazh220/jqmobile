<?php
/**
 * 用户处理类
 * 
 * @package     modules
 * @author      sam(sam.ma@lyceem.com)
 * @copyright   2010-4-12
 */
 
class userInfo
{
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
	
	/**
     * 构造函数，获取数据库连接对象
     *
     */
    public function __construct(){
        global $app;
        
        $this->app = $app;
        
        $this->db = $app->orm($app->cfg['db'])->query();
		
        mysql_query("set names utf8");
    }
	
	
	/**
     * 获取所有管理员信息
     * 
     * @return array|bool  成功返回批次数组,失败返回false
     */
    public function getUserInfo()
	{
		if($this->db == null)
		{
    		return false;
    	}
		
		$sql = "SELECT user_id , user_name FROM admin_user";
		$r   = $this->db->getArray($sql);
		
		if($r === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    	}
		
		if(!is_array($r) || count($r) == 0)
		{
			return false;
		}
		
		$ar_admin = array();
		foreach($r as $v)
		{
			$ar_admin[$v['user_id']] = $v['user_name'];	
		}
		
		return $ar_admin;
	}
	
	/**
	 * 登录查询
	 * 
	 * @param string $s_username 用户名
	 * @param string $s_password 密码
	 * @return array|bool
	 */
	public function findLogin($s_username,$s_password){
		if($this->db == null){
    		return false;
    	}
    		
    	$s_username = (string)trim($s_username);
		$s_password = md5((string)trim($s_password));
		
		if(empty($s_username)){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'username is empty. ' . $s_username, date("Y-m-d H:i:s")));
		}
		
		if(empty($s_password)){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'password is empty. ' . $s_password, date("Y-m-d H:i:s")));
		}
		
		$sql = "SELECT * FROM hg_user WHERE username = '$s_username' AND password = '$s_password'";
		//$sql = "SELECT user_id,user_name,email,action_list,last_login,agency_id FROM admin_user WHERE user_name = '$s_username' AND password = '$s_password'";
		//echo $sql;die;
		$res = $this->db->getRow($sql);
		
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
		}
		
		return $res;
	}
	
	/**
	 * 更新登录时间或ip
	 * 
	 * @param string $i_userid 用户名
	 * @param string $s_ip 	        客户端IP
	 * 
	 * @return bool
	 */
	public function updateLoginInfo($i_userid, $s_ip) {
		if($this->db == null){
    		return false;
    	}
    	
    	$sql = "UPDATE admin_user SET last_login = ".time()." , last_ip = '".$s_ip."' WHERE user_id = $i_userid OR id = $i_userid";
    	$res = $this->db->exec($sql);
    	
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    	}
    	
    	if($res > 0 ){
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
	    $log->reset()->setPath("modules/UserInfo")->setData($data)->write();
	    
	    return false;
	}
}
?>