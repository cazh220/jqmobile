<?php 
/**
 * 商品分类信息查询
 * 
 * @package controller
 * @author 李<shihai.li@lyceem.com>
 * @copyright 10-10-2011
 */
class Productinfo {
	
	/**
     * 应用程序对像
     *
     * @var Application
     */
    private $app = null;
    
    /**
     * 数据库操作对像
     *
     * @var OrmQuery
     */
    private $product_info = null;
    
	/**
     * 构造函数
     *
     * @param String $mx_pfx
     */
    public function __construct() {
        global $app;
        
        $this->app = $app; 
        
        $this->product_info = $app->orm($app->cfg['db'])->query();
        
		mysql_query("set names utf8");
    }
    
        
	/**
	 * 根据大类ID获取产品小类
	 */   
    
	public function getTypeInfo($categoryid){
		
		if($this->product_info == null) return false;

		if($categoryid == '' || $categoryid == NULL) return false;	

		$sql = "select cat_id,cat_name from ecs_category where parent_id=".$categoryid;

		$info = $this->product_info->getArray($sql);
		
		if($info === false) {
			$this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' exec is fail.'.mysql_error(),date("Y-m-d H:i:s")));
		}

		return $info;
	}
	

	/**
	 * 根据小类ID获取产品列表
	 */   
    
	public function getProductInfo($typeid){
		
		if($this->product_info == null) return false;

		if($typeid == '' || $typeid == NULL) return false;	

		$sql = "select goods_id,goods_name,goods_sn from ecs_goods where cat_id=".$typeid;

		$info = $this->product_info->getArray($sql);
		
		if($info === false) {
			$this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' exec is fail.'.mysql_error(),date("Y-m-d H:i:s")));
		}

		return $info;
	}
    
    /**
     * 得到商品列表　
     */
    public function getGoodsList($category,$type,$goods_id){
        if($goods_id){
            $sql = "select goods_id,goods_name,goods_sn from ecs_goods where goods_id='$goods_id'";
            $res = $this->product_info->getArray($sql);
            if(!$res)return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' exec is fail.'.mysql_error(),date("Y-m-d H:i:s")));
            return $res;
        }
        
        if($type){
            $sql = "select goods_id,goods_name,goods_sn from ecs_goods where cat_id=".$type;
            $res = $this->product_info->getArray($sql);
            if(!$res)return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' exec is fail.'.mysql_error(),date("Y-m-d H:i:s")));
            return $res;
        }
        
        if($category){
            $sql = "select cat_id from ecs_category where parent_id=".$category;
        }else{
            $sql = "select cat_id from ecs_category where 1";
        }
        $sql = "select goods_id,goods_name,goods_sn from ecs_goods where cat_id in ($sql)";
        $res = $this->product_info->getArray($sql);
        if(!$res)return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' exec is fail.'.mysql_error(),date("Y-m-d H:i:s")));
        return $res;
    }
	/**
     * getGoodsByGoodsSn
     */
    public function getGoodsByGoodsSn($goods_sn){
        $sql = "select goods_id,goods_name,goods_sn from ecs_goods where goods_sn in ('$goods_sn')";
        $res = $this->product_info->getArray($sql);
        if(!$res)return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' exec is fail.'.mysql_error(),date("Y-m-d H:i:s")));
        return $res;
    }
    
    public function getGoodsByGoodsId($goods_id){
        $sql = "select goods_id,goods_name,goods_sn from ecs_goods where goods_id in ($goods_id)";
        $res = $this->product_info->getArray($sql);
        if(!$res)return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' exec is fail.'.mysql_error(),date("Y-m-d H:i:s")));
        return $res;
    }
	/**
	 * 数据更新失败记录日志，并标识操作失败
	 *
	 * @param Array $data
	 * @return false
	 */
	private function _log($data) {
	    $log = $this->app->log();
	    $log->reset()->setPath("Productinfo")->setData($data)->write();
        return false;
	}
}
?>