<?php
/**
 * ������֤��
 */
 
class SmsCode
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
	public function generate_code($mobile, $code)
	{
		if($this->db == null)
		{
    		return false;
    	}

		$sql = "INSERT INTO hg_sms_code(mobile, code, update_time)VALUES('".$mobile."', $code, NOW())";

		$r = $this->db->exec($sql);
		
		if($r === false) {
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    	}
		
		return $r;
	}
	
	//��֤��֤���Ƿ���ȷ
	public function validate_code($mobile)
	{
		if($this->db == null)
		{
    		return false;
    	}
		$sql = "SELECT code FROM hg_sms_code WHERE mobile = '".$mobile."' ORDER BY mid DESC LIMIT 1";
		$result = $this->db->getArray($sql);
		return $result;
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