<?php
/**
 * ����
 */
 
class AreaInfo
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
	
	//��ȡʡ
	public function get_province()
	{
		if($this->db == null)
		{
    		return false;
    	}

		$sql = "SELECT * FROM hg_region WHERE parent_id = 1";

		$r = $this->db->getArray($sql);
		
		if($r === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    	}
		
		if(!is_array($r) || count($r) == 0)
		{
			return false;
		}
		
		return $r;
	}
	
	//��ȡ����
	public function get_city($province_id)
	{
		$r = array();
		if($province_id)
		{
			if($this->db == null)
			{
				return false;
			}

			$sql = "SELECT * FROM hg_region WHERE parent_id = ".$province_id;

			$r = $this->db->getArray($sql);
			
			if($r === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
			}
			
			if(!is_array($r) || count($r) == 0)
			{
				return false;
			}
		}
		return $r;
	}
	//��ȡ����
	public function get_district($city_id)
	{
		$r = array();
		if(city_id)
		{
			if($this->db == null)
			{
				return false;
			}

			$sql = "SELECT * FROM hg_region WHERE parent_id = ".$city_id;

			$r = $this->db->getArray($sql);
			
			if($r === false){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
			}
			
			if(!is_array($r) || count($r) == 0)
			{
				return false;
			}
		}
		return $r;
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