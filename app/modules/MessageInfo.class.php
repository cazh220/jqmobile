<?php
/**
 * 消息处理类
 * 
 * @package     modules
 */
 
class MessageInfo
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
    
    public function write_message($data=array())
    {
    	if($this->db == null)
		{
    		return false;
    	}
    	
    	
    	$sql  = "INSERT INTO hg_message(type, from, to, message, error_info, correct_info, create_time)VALUES('".$data['type']."', '".$data['from']."', '".$data['to']."', '".$data['message']."', '".$data['correct_info']."','".$data['create_time']."')";
		echo $sql;die;
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