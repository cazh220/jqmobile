<?php 
/**
 */

class StockTransferInfo {
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
     * 查询记录
     */
    public function getTranserLogs($ar_where){
        if($this->store_db == null){
            return false;
        }
        $agency_id = !empty($ar_where['agency_id']) ? $ar_where['agency_id'] : 0;
        $start_time = !empty($ar_where['start_time']) ? strtotime($ar_where['start_time']) : 0;
        $end_time = !empty($ar_where['end_time']) ? strtotime($ar_where['end_time']) : 0;
        $goods_id = !empty($ar_where['goods_id']) ? $ar_where['goods_id'] : 0;
        $size = !empty($ar_where['size']) ? $ar_where['size'] : 0;
        
        $s_where = " where 1";
        if($agency_id)$s_where .= " and gstl.agency_id='$agency_id'";
        if($goods_id)$s_where .= " and goods_id='$goods_id'";
        if($size)$s_where .= " and size='$size'";
        if($start_time)$s_where .= " and act_time >='$start_time'";
        if($end_time)$s_where .= " and act_time <='$end_time'";
        $sql = "select agency_name,a.agency_id,from_unixtime(act_time) as act_time,goods_id,size,color,increment,current_quantity from goods_stock_transfer_log as gstl left join agency as a on a.agency_id=gstl.agency_id".$s_where;
        // exit;
        $res = $this->store_db->getArray($sql);
        if(!$res)return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
        return $res;
        
    }
   
    /**
     * 数据更新失败记录日志，并标识操作失败
     *
     * @param   Array   $data
     * @return  bool    false
     */
    private function _log($data){
        $log = $this->app->log();
        $log->reset()->setPath("modules/StockTransferInfo")->setData($data)->write();
        
        return false;
    }
}
?>