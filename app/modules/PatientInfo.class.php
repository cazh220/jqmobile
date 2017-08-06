<?php
/**
 * 病人处理类
 * 
 * @package     modules
 */
 
class PatientInfo
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

    //插入病人
    public function insert_patient($data)
    {
    	if($this->db == null)
		{
    		return false;
    	}

    	$sql  = "INSERT INTO hg_patient(name, sex, birthday, hospital, doctor, tooth_position, production_unit, create_time, operator, false_tooth, repairosome_pic, security_code, mobile, email, update_time)VALUES('".$data['name']."', '".$data['sex']."', '".$data['birthday']."', '".$data['hospital']."', '".$data['doctor']."','".$data['tooth_position']."', '".$data['production_unit']."','".$data['create_time']."', '".$data['operator']."', '".$data['false_tooth']."', '".$data['repaire_pic']."','".$data['security_code']."','".$data['mobile']."','".$data['email']."','".$data['update_time']."')";
		$res = $this->db->exec($sql);
    	
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    	}
    	
    	//增加积分
    	$sql = "UPDATE hg_user SET total_credits = total_credits + ".$data['credits']." WHERE user_id = ".$data['user_id'];
    	
    	$res = $this->db->exec($sql);
    	
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    	}
    	
    	if($res > 0 ){
    		importModule('LogSqs');
			
			$logsqs=new LogSqs;
    		return true;
    		//return $this->db->getLastId();
		}
    	
    	return false;
    }
    
    //更新病人
    public function update_patient($data)
    {
    	if($this->db == null)
		{
    		return false;
    	}

    	$sql  = "UPDATE hg_patient SET name='".$data['patient_name']."', sex='".$data['sex']."',birthday='".$data['patient_age']."',hospital='".$data['hospital']."',doctor='".$data['doctor']."',tooth_position='".$data['tooth_position']."', false_tooth='".$data['false_tooth']."', repairosome_pic='".$data['repaire_pic']."' WHERE security_code = '".$data['security_code']."'";
    	//echo $sql;die;
		$res = $this->db->exec($sql);
    	
    	if($res === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    	}
    	
    	
    	if($res > 0 ){
    		importModule('LogSqs');
			
			$logsqs=new LogSqs;
    		return true;
    		//return $this->db->getLastId();
		}
    	
    	return false;
    }
    
    //获取患者信息
    public function get_patient($qrcode='')
    {
    	if ($qrcode)
    	{
    		if($this->db == null){
	    		return false;
	    	}
	
	    	$sql = "SELECT * FROM hg_patient a LEFT JOIN hg_false_tooth b ON a.false_tooth = b.false_tooth_id WHERE security_code = '".$qrcode."'";
			
	    	$res = $this->db->getArray($sql);
	
	    	if($res === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
			}
			
			return $res;
    	}
    	else
    	{
    		return false;
    	}
    }
    
    //获取患者列表
    public function patient_list($data)
    {
    	if($this->db == null){
    		return false;
    	}
		
    	$sql = "SELECT * FROM hg_patient WHERE is_delete = 0 ";
		if($data['hospital'])
		{
			$sql .= " AND hospital LIKE '%".$data['hospital']."%'";
		}
		$start = ($data['page']-1)*$data['page_size'];
		$page_size = $data['page_size'];
		$sql .= " LIMIT $start,$page_size";
		
    	$res = $this->db->getArray($sql);

    	if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
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
	    $log->reset()->setPath("modules/UserInfo")->setData($data)->write();
	    
	    return false;
	}
}
?>