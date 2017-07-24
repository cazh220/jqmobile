<?php
/**
 * ������֤��
 */
 
class Vcode
{
	/**
	 * Ӧ�ó������
	 * @var Application
	 */
	private $app = null;
	
	/**
	 * ���ݿ��������
	 * @var OrmQuery
	 */
	private $db = null;
	
	/**
     * ���캯������ȡ���ݿ����Ӷ���
     *
     */
    public function __construct(){
        global $app;
        
        $this->app = $app;
        
        $this->db = $app->orm($app->cfg['db'])->query();
		
        mysql_query("set names utf8");
    }
	
	//���ɶ�����֤��
	public function generate_code()
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
	 * ���ݸ���ʧ�ܼ�¼��־������ʶ����ʧ��
	 *
	 * @param 	Array 	$data
	 * @return 	bool	false
	 */
	private function _log($data){
	    $log = $this->app->log();
	    $log->reset()->setPath("modules/Vcode")->setData($data)->write();
	    
	    return false;
	}
}
?>