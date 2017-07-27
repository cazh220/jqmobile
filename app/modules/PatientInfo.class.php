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

    	$sql  = "INSERT INTO hg_patient(name, sex, birthday, hospital, doctor, tooth_position, production_unit, create_time, operator, false_tooth, repaire_pic)VALUES('".$data['name']."', '".$data['sex']."', '".$data['birthday']."', '".$data['hospital']."', '".$data['doctor']."','".$data['tooth_position']."', '".$data['production_unit']."','".$data['create_time']."', '".$data['operator']."', '".$data['false_tooth']."', '".$data['repaire_pic']."')";
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