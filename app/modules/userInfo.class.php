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
	
	//注册信息插入表中
	public function insert_user($data)
	{
		if($this->db == null){
    		return false;
    	}

		$sql  = "INSERT INTO hg_user(username, mobile, realname, password, user_type, email, company_name, company_addr, department, position, persons_num, head_img, birthday, create_time, last_login, last_ip, province, city, district, company_info)VALUES('".$data['username']."','".$data['mobile']."','".$data['realname']."', '".md5($data['password'])."', '".$data['user_type']."', '".$data['email']."', '".$data['company_name']."','".$data['address']."', '','".$data['job']."', '".$data['employee_num']."', '".$data['pic']."','".$data['birthday']."','".$data['create_time']."','".$data['last_login']."','".$data['last_ip']."','".$data['province']."','".$data['city']."','".$data['district']."','".$data['desc']."')";
		$res = $this->db->exec($sql);
    	
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    	}
    	
    	if($res > 0 ){
    		importModule('LogSqs');
			
			$logsqs=new LogSqs;
    		//return true;
    		return $this->db->getLastId();
		}
    	
    	return false;
	}
	
	//更新用户信息
	public function update_user($data)
	{
		if($this->db == null){
    		return false;
    	}

		$sql  = "UPDATE hg_user SET realname = '".$data['realname']."', user_type = {$data['user_type']}, email = '".$data['email']."', company_name = '".$data['company_name']."', company_addr = '".$data['address']."', head_img = '".$data['company_pic']."', company_info = '".$data['info']."', province = {$data['province']}, city = {$data['city']}, district = {$data['district']} WHERE user_id = {$data['user_id']}";
		//echo $sql;die;
		$res = $this->db->exec($sql);
    	
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    	}
    	
    	if($res > 0 ){
    		importModule('LogSqs');
			
			$logsqs=new LogSqs;
    		//return true;
    		return true;
		}
    	
    	return false;
	}

	//获取会员信息
	public function get_user_detail($user_id)
	{
		if($this->db == null){
    		return false;
    	}

    	$sql = "SELECT * FROM hg_user WHERE user_id = ".$user_id;

    	$res = $this->db->getArray($sql);

    	if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
		}
		
		return $res;
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
    	
    	$sql = "UPDATE hg_user SET last_login = ".time()." , last_ip = '".$s_ip."' WHERE user_id = $i_userid";
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
	

	//修改密码
	public function update_pwd($mobile, $pwd)
	{
		if($this->db == null){
    		return false;
    	}
    	
    	$sql = "UPDATE hg_user SET password = '".$pwd."' WHERE mobile = $mobile";
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
	
	//意见反馈
	public function feedback($user_id=0, $feedback='')
	{
		if($this->db == null){
    		return false;
    	}
    	
    	$sql = "INSERT INTO hg_feedback(user_id, content, create_time)VALUES($user_id, '".$feedback."', NOW()) ";
    	//echo $sql;die;
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