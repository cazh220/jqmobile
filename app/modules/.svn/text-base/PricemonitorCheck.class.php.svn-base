<?php 
/**
 * 价格监控商品对应信息录入
 * 
 */
class PricemonitorCheck{
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
    private $lyceem = null;

	private $distribution = null;

	private $stock  = null;
    
	/**
     * 构造函数
     *
     * @param String $mx_pfx
     */
    public function __construct()
    {
        global $app;
        $this->app = $app; 
        $this->lyceem = $app->orm($app->cfg['db'])->query();
		$this->distribution = $app->orm($app->cfg['distribution'])->query();
		$this->stock = $app->orm($app->cfg['stock'])->query();
		mysql_query("set names utf8");
    }

	/**
     * 获取所有Lyceen商品信息
     *
     */
    public function getGoodsInfo(){
    	if ( $this->lyceem == null ) return false;
    	$sql = "SELECT ul.goods_id,ul.goods_name,ol.color FROM ecs_goods as ul INNER JOIN ecs_goods_colors_img as ol ON ul.goods_id = ol.goods_id GROUP BY goods_id";
    	$ar_items = $this->lyceem->getArray($sql);
		if($ar_items === false)
		{
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql Error : '.$sql, date("Y-m-d H:i:s")));
		}

		if(empty($ar_items)) return false;
		return $ar_items;
    }

	/**
     * 获取已完成商品信息录入的总数
     *
     */
	public function getNum(){
    	if($this->distribution == null) return false;
    	$sql = "select * from outer_goods_relationship";
   		$info = $this->distribution->getArray($sql);
		if($info === false) 
		{
			$this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' exec is fail.'.mysql_error(),date("Y-m-d H:i:s")));
		}
		return $info;	
    }

    public function getInfo($pagee,$pagesize){	
    	if($this->distribution == null) return false;
    	$sql = "select ul.info_id,ul.partner_id,ul.goods_id,ul.property,ol.title,ol.url,ll.goods_name,tt.partner_name FROM outer_goods_relationship as ul INNER JOIN outer_goods_info as ol ON ul.info_id=ol.id INNER JOIN lyceem.ecs_goods as ll ON ul.goods_id=ll.goods_id INNER JOIN partner as tt ON tt.id= ul.partner_id GROUP BY ul.info_id order by ul.partner_id,ul.goods_id  limit $pagee,$pagesize";
   		$info = $this->distribution->getArray($sql);
		if($info === false) 
		{
			$this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' exec is fail.'.mysql_error(),date("Y-m-d H:i:s")));
		}
		return $info;	
    }

	/**
	 *指定分销渠道下分销商品查询
	 * 
	 * @param array  $id 查询条件 分销渠道ID
	 * @return array|bool
	 */    
	public function getOutInfo($id){	
		if($this->distribution == null) return false;
		if(empty($id)) return false;	
		$sql = "SELECT id,title,url from outer_goods_info where partner_id=$id and id NOT IN (select info_id from outer_goods_relationship)";
   		$info = $this->distribution->getArray($sql);		
		if($info === false) 
		{
			$this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' exec is fail.'.mysql_error(),date("Y-m-d H:i:s")));
		}
		return $info;		
	}

	/**
	 *商品名称
	 *Lyceem官方商品匹配查询
	 *模糊查询  like
	 *参数 $id
	 *
	 * @return array|bool
	 */ 
	public function getGoodsname($id){
		if($this->lyceem == null) return false;
		$sql = "SELECT ul.goods_id,ul.goods_name,ol.color FROM ecs_goods as ul INNER JOIN ecs_goods_colors_img as ol ON ul.goods_id = ol.goods_id where ul.goods_name like '%".$id."%' GROUP BY ul.goods_id" ;
   		$info = $this->lyceem->getArray($sql);		
		if($info === false) 
		{
			$this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' exec is fail.'.mysql_error(),date("Y-m-d H:i:s")));
		}
		return $info;	
	}

	/**
	 *对应商品尺寸
	 *参数 goods_id
	 * @return array|bool
	 */    
    public function getGoodssize($id){
    	if ( $this->stock == null ) return false;
    	$sql = "SELECT size from goods_stock_status where goods_id=".$id." group by size";
    	$ar_items = $this->stock->getArray($sql);    	
		if($ar_items === false)
		{
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql Error : '.$sql, date("Y-m-d H:i:s")));
		}
		if(empty($ar_items)) return false;
		return $ar_items;
    }

    /**
	 *对应商品颜色
	 *参数 goods_id
	 * @return array|bool
	 */ 
    public function getGoodscolor($id){
    	if ( $this->lyceem == null ) return false;
    	$sql = "SELECT color from ecs_goods_colors_img where goods_id=".$id." group by color";
    	$ar_items = $this->lyceem->getArray($sql);    	
		if($ar_items === false)
		{
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql Error : '.$sql, date("Y-m-d H:i:s")));
		}
		if(empty($ar_items)) return false;
		return $ar_items;
    }

    /**
	 *对应商品货号
	 *参数 goods_id
	 * @return array|bool
	 */ 
    public function getGoodssn($id){
    	if ( $this->lyceem == null ) return false;
    	$sql = "SELECT goods_sn from ecs_goods where goods_id=".$id." group by goods_sn";
    	$ar_items = $this->lyceem->getArray($sql);
	
		if($ar_items === false)
		{
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql Error : '.$sql, date("Y-m-d H:i:s")));
		}
		if(empty($ar_items)) return false;
		return $ar_items;
    }

	/**
	 * 商品信息录入
	 * 
	 * @value  $arr_set() 
	 * @return NO Return
	 */   	
	public function addOutInfo($arr_set){		
		if($this->distribution == null) return false;		
		if(empty($arr_set) || !is_array($arr_set)) return false;		
		$sql = "INSERT INTO outer_goods_relationship (info_id,partner_id,goods_id,property) VALUES ('".$arr_set['info_id']."','".$arr_set['partner_id']."','".$arr_set['goods_id']."','".$arr_set['property']."')";		
   	    $info = $this->distribution->exec($sql);   		
		if($info === false) 
		{
			$this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' exec is fail.'.mysql_error(),date("Y-m-d H:i:s")));
		    echo "<script laguage='javascript'> alert('写入数据失败')</script>";
		}
		else
		{
			echo "<script laguage='javascript'> alert('写入数据成功！');window.location.href='pricemonitorinsert.php'</script>";
		}		
	}

	/**
	 * 商品信息更新
	 * 
	 * @value  $arr_set() 
	 * @return NO Return
	 */   
    public function UpdateInfo($arr_set){    	
    	if($this->distribution == null) return false;    	
    	if(empty($arr_set) || !is_array($arr_set)) return false;    	
    	$sql = "UPDATE outer_goods_relationship SET goods_id='".$arr_set['goods_id']."',property='".$arr_set['property']."' where info_id='".$arr_set['info_id']."'";    	
    	$info = $this->distribution->exec($sql);    	
    	if($info === false) 
		{
			$this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' exec is fail.'.mysql_error(),date("Y-m-d H:i:s")));
		}
		else
		{
			echo "<script language=javascript>alert('修改数据成功');window.location.href='pricemonitorinsert.php'</script>";
		}
    }

	/**
	 * 商品信息删除
	 * 
	 * @value  $arr_set() 
	 * @return NO Return
	 */   
    public function DeleteInfo($info_id){    	
    	if($this->distribution == null) return false;    	
    	if($info_id == "") return false;    	
    	$sql = "DELETE FROM outer_goods_relationship WHERE info_id=".$info_id;    	
    	$info = $this->distribution->exec($sql);    	
    	if($info === false) 
		{
			$this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' exec is fail.'.mysql_error(),date("Y-m-d H:i:s")));
		}
		else
		{
			echo "<script language=javascript>alert('删除数据成功');window.location.href='pricemonitorinsert.php'</script>";
		}
    }
}
?>